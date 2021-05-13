<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Task Model for core_tasks table
 */
class Task extends PS_Model {

	protected $user_table_name;
	protected $assigns_table_name;
	protected $task_images_table_name;
	protected $images_table_name;
	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'core_tasks', 'id' );

		// initialize table names
		$this->user_table_name = "core_users";
		$this->assigns_table_name = "core_tasks_assign";
		$this->task_images_table_name = "core_task_images";
		$this->images_table_name = "core_imgs";
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
		// completed_by_id condition
		if ( isset( $conds['completed_by_id'] )) {
			$this->db->where( 'completed_by_id', $conds['completed_by_id'] );
		}
		// updated_by_id condition
		if ( isset( $conds['updated_by_id'] )) {
			$this->db->where( 'updated_by_id', $conds['updated_by_id'] );
		}

		// name condition
		if ( isset( $conds['name'] )) {
			$this->db->where( 'name', $conds['name'] );
		}
		// priority condition
		if ( isset( $conds['priority'] )) {
			$this->db->where( 'priority', $conds['priority'] );
		}
		// status condition
		if ( isset( $conds['status'] )) {
			$this->db->where( 'status', $conds['status'] );
		}

		// searchterm
		if ( isset( $conds['searchterm'] )) {
			$this->db->like( 'name', $conds['searchterm'] );
			$this->db->or_like( 'description', $conds['searchterm'] );
		}

		$this->db->order_by( 'created_at', 'desc' );
	}

	/**
	 * @param ref array $task_data
	 * @param int $task_id
	 * @return bool
	 */
	function save( &$task_data, $task_id = false )
	{
		// start the transaction
		$this->db->trans_start();

		if ( !$task_id ) { // insert new			
			$logged_in_user = $this->ps_auth->get_user_info();
			
			$task_data['created_at'] = date("Y-m-d H:i:s");
			$task_data['created_by_id'] = $logged_in_user->user_id;
			if ( ! $this->db->insert( $this->table_name, $task_data )) {
				// if error in inserting new, rollback
				$this->db->trans_rollback();
        		return false;
			}

		} else {
			//else update the data
			$this->db->where( 'id', $task_id );
			if ( ! $this->db->update( $this->table_name, $task_data )) {
				// if error in updating, rollback
				$this->db->trans_rollback();
        		return false;
			}
		}
		// commit the transaction
		return $this->db->trans_commit();
	}

	/**
	 * @param int $task_id
	 * @return bool
	 */
	function delete( $task_id )
	{
		// start the transaction
		$this->db->trans_start();
		
		if ( ! $this->db->delete( $this->assigns_table_name, array( 'task_id' => $task_id ))) {
			// if error in deleteing assigned members, rollback
			$this->db->trans_rollback();
			return false;
		}
		if ( ! $this->db->delete( $this->task_images_table_name, array( 'task_id' => $task_id ))) {
			// if error in deleteing assigned members, rollback
			$this->db->trans_rollback();
			return false;
		}

		$logged_in_user = $this->ps_auth->get_user_info();
		$this->db->where( $this->primary_key, $task_id );

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