<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Team Model for core_teams table
 */
class Team extends PS_Model {

	protected $user_table_name;
	protected $members_table_name;

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'core_teams', 'id' );

		// initialize table names
		$this->user_table_name = "core_users";
		$this->members_table_name = "core_team_members";
	}

	/**
	 * Implement the where clause
	 *
	 * @param      array  $conds  The conds
	 */
	function custom_conds( $conds = array())
	{
		// default where clause
		// $this->db->where( 'is_trashed', 0 );

		// id condition
		if ( isset( $conds['id'] )) {
			$this->db->where( $this->primary_key, $conds['id'] );
		}

		// is_trashed condition
		if ( isset( $conds['is_trashed'] )) {
			$this->db->where( 'is_trashed', $conds['is_trashed'] );
		}

		// created_by_id condition
		if ( isset( $conds['created_by_id'] )) {
			$this->db->where( 'created_by_id', $conds['created_by_id'] );
		}

		// trashed_by_id condition
		if ( isset( $conds['trashed_by_id'] )) {
			$this->db->where( 'trashed_by_id', $conds['trashed_by_id'] );
		}

		// name condition
		if ( isset( $conds['name'] )) {
			$this->db->where( 'name', $conds['name'] );
		}

		// searchterm
		if ( isset( $conds['searchterm'] )) {
			$this->db->like( 'name', $conds['searchterm'] );
			$this->db->or_like( 'description', $conds['searchterm'] );
		}

		$this->db->order_by( 'created_at', 'desc' );
	}

	/**
	 * Save function creates/updates the team data to teams table.
	 * If the team_id is already exist in the teams table, update user data
	 * else, the function will create new data row
	 * 
	 * @param ref array $team_data
	 * @param int $team_id
	 * @return bool
	 */
	function save( &$team_data, $team_id = false )
	{
		// start the transaction
		$this->db->trans_start();

		if ( !$team_id ) { // insert new			
			$logged_in_user = $this->ps_auth->get_user_info();
			
			$team_data['created_at'] = date("Y-m-d H:i:s");
			$team_data['created_by_id'] = $logged_in_user->user_id;
			if ( ! $this->db->insert( $this->table_name, $team_data )) {
				// if error in inserting new, rollback
				$this->db->trans_rollback();
        		return false;
			}

		} else {
			//else update the data
		
			$this->db->where( 'id', $team_id );
			
			if ( ! $this->db->update( $this->table_name, $team_data )) {
				// if error in updating, rollback
				$this->db->trans_rollback();
        		return false;
			}
		}
		// commit the transaction
		return $this->db->trans_commit();
	}

	/**
	 * Delete function update 1 to deleted fields from teams table
	 * according to the team_id
	 * 
	 * @param int $team_id
	 * @return bool
	 */
	function delete( $team_id )
	{
		// start the transaction
		$this->db->trans_start();
		
		if ( ! $this->db->delete( $this->members_table_name, array( 'team_id' => $team_id ))) {
			// if error in deleteing assigned members, rollback
			$this->db->trans_rollback();
			return false;
		}
		$logged_in_user = $this->ps_auth->get_user_info();

		$this->db->where( $this->primary_key, $team_id );

		$delete_data = array(
			'is_trashed' => 1, 
			'trashed_by_id' => $logged_in_user->user_id
		);

		if ( ! $this->db->update( $this->table_name, $delete_data)) {
		// if error in updating user status,

			$this->db->trans_rollback();
			return false;
		}

		// commit the transaction
		return $this->db->trans_commit();
	}

	
			
}