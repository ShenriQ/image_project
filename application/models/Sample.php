<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sample Model for core_samples table
 */
class Sample extends PS_Model {

	protected $user_table_name;
	protected $task_samples_table_name;
	protected $images_table_name;
	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'core_samples', 'id' );

		// initialize table names
		$this->user_table_name = "core_tasks";
		$this->images_table_name = "core_imgs";
		$this->task_samples_table_name = "core_task_samples";
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
		// sample_index condition
		if ( isset( $conds['sample_index'] )) {
			$this->db->where( 'sample_index', $conds['sample_index'] );
		}
		// operator_check condition
		if ( isset( $conds['operator_check'] )) {
			$this->db->where( 'operator_check', $conds['operator_check'] );
		}
		// operator_ip condition
		if ( isset( $conds['operator_ip'] )) {
			$this->db->where( 'operator_ip', $conds['operator_ip'] );
		}
		// operator_id condition
		if ( isset( $conds['operator_id'] )) {
			$this->db->where( 'operator_id', $conds['operator_id'] );
		}

		// searchterm
		if ( isset( $conds['searchterm'] )) {
			$this->db->like( 'sample_index', $conds['searchterm'] );
			$this->db->or_like( 'operator_check', $conds['searchterm'] );
		}

		$this->db->order_by( 'created_at', 'desc' );
	}

	/**
	 * @param ref array $sample_data
	 * @param int $sample_id
	 * @return bool
	 */
	function save( &$sample_data, $sample_id = false )
	{
		// start the transaction
		$this->db->trans_start();

		if ( !$sample_id ) { // insert new			
			$logged_in_user = $this->ps_auth->get_user_info();
			
			$sample_data['created_at'] = date("Y-m-d H:i:s");
			$sample_data['created_by_id'] = $logged_in_user->user_id;
			if ( ! $this->db->insert( $this->table_name, $sample_data )) {
				// if error in inserting new, rollback
				$this->db->trans_rollback();
        		return false;
			}

		} else {
			//else update the data
			$this->db->where( 'id', $sample_id );
			if ( ! $this->db->update( $this->table_name, $sample_data )) {
				// if error in updating, rollback
				$this->db->trans_rollback();
        		return false;
			}
		}
		// commit the transaction
		return $this->db->trans_commit();
	}

	/**
	 * @param int $sample_id
	 * @return bool
	 */
	function delete( $sample_id )
	{
		// start the transaction
		$this->db->trans_start();
		
		if ( ! $this->db->delete( $this->images_table_name, array( 'sample_id' => $sample_id ))) {
			// if error in deleteing assigned members, rollback
			$this->db->trans_rollback();
			return false;
		}
		if ( ! $this->db->delete( $this->task_samples_table_name, array( 'sample_id' => $sample_id ))) {
			// if error in deleteing assigned members, rollback
			$this->db->trans_rollback();
			return false;
		}

		$logged_in_user = $this->ps_auth->get_user_info();
		$this->db->where( $this->primary_key, $sample_id );

		$delete_data = array(
			'is_trashed' => 1, 
			'trashed_by_id' => $logged_in_user->user_id
		);

		if ( ! $this->db->update( $this->table_name, $delete_data)) {
	
			$this->db->trans_rollback();
			return false;
		}

		// commit the transaction
		return $this->db->trans_commit();
	}

	
			
}