<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Team members Model for core_team_members table
 */
class Team_member extends PS_Model {

	protected $user_table_name;
	protected $team_table_name;

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'core_team_members', 'id' );

		// initialize table names
		$this->user_table_name = "core_users";
		$this->team_table_name = "core_teams";
	}

	/**
	 * Implement the where clause
	 *
	 * @param      array  $conds  The conds
	 */
	function custom_conds( $conds = array())
	{
		// id condition
		if ( isset( $conds['id'] )) {
			$this->db->where( $this->primary_key, $conds['id'] );
		}

		// role_id condition
		if ( isset( $conds['role_id'] )) {
			$this->db->where( 'role_id', $conds['role_id'] );
		}

		// team_id condition
		if ( isset( $conds['team_id'] )) {
			$this->db->where( 'team_id', $conds['team_id'] );
		}

		// user_id condition
		if ( isset( $conds['user_id'] )) {
			$this->db->where( 'user_id', $conds['user_id'] );
		}

		// created_by_id condition
		if ( isset( $conds['created_by_id'] )) {
			$this->db->where( 'created_by_id', $conds['created_by_id'] );
		}

		$this->db->order_by( 'created_at', 'desc' );
	}

	function getTeamMemberCnt ($team_id) {
		return $this->count_all_by(array('team_id', $team_id));
	}
	/**
	 * Save function creates/updates the team member data to table.
	 * If the id is already exist in the teams table, update user data
	 * else, the function will create new data row
	 * 
	 * @param ref array $team_member_data
	 * @param int $id
	 * @return bool
	 */
	function save_team( &$team_member_data, $id = false )
	{
		// start the transaction
		$this->db->trans_start();

		if ( !$id ) { // insert new			
			
			if ( ! $this->db->insert( $this->table_name, $team_member_data )) {
				// if error in inserting new, rollback
				$this->db->trans_rollback();
        		return false;
			}

		} else {
			//else update the data
		
			$this->db->where( 'id', $id );
			
			if ( ! $this->db->update( $this->table_name, $team_member_data )) {
				// if error in updating, rollback
				$this->db->trans_rollback();
        		return false;
			}
		}
		// commit the transaction
		return $this->db->trans_commit();
	}

	/**
	 * Delete function update 1 to deleted fields from team members table
	 * according to the $team_id, $user_id
	 * 
	 * @param int $team_id, $user_id
	 * @return bool
	 */
	function delete( $team_id = false, $user_id = false )
	{
		if (!$team_id && !$user_id) {
			return false;
		}

		// start the transaction
		$this->db->trans_start();
		
		if ($team_id != false) {
			$this->db->where( 'team_id', $team_id );
		} 
		if ($user_id != false) {
			$this->db->where( 'user_id', $user_id );
		} 

		if ( ! $this->db->delete( $this->table_name)) {
			$this->db->trans_rollback();
			return false;
		}

		// commit the transaction
		return $this->db->trans_commit();
	}
}