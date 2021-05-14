<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activitylog extends PS_Model {

	protected $user_table_name;
	protected $role_table_name;
	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'core_activity_logs', 'id' );

		// initialize table names
		$this->user_table_name = "core_users";
		$this->role_table_name = "core_roles";
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
		// name condition
		if ( isset( $conds['name'] )) {
			$this->db->where( 'name', $conds['name'] );
		}
		// request_url condition
		if ( isset( $conds['request_url'] )) {
			$this->db->where( 'request_url', $conds['request_url'] );
		}
		// causer_id condition
		if ( isset( $conds['causer_id'] )) {
			$this->db->where( 'causer_id', $conds['causer_id'] );
		}
		// causer_role condition
		if ( isset( $conds['causer_role'] )) {
			$this->db->where( 'causer_role', $conds['causer_role'] );
		}
		// causer_ip condition
		if ( isset( $conds['causer_ip'] )) {
			$this->db->where( 'causer_ip', $conds['causer_ip'] );
		}
		// datetime condition
		if ( isset( $conds['datetime'] )) {
			$this->db->where( 'datetime', $conds['datetime'] );
		}

		// searchterm
		if ( isset( $conds['searchterm'] )) {
			$this->db->like( 'name', $conds['searchterm'] );
			$this->db->or_like( 'description', $conds['searchterm'] );
			$this->db->or_like( 'request_url', $conds['searchterm'] );
			$this->db->or_like( 'causer_ip', $conds['searchterm'] );
			$this->db->or_like( 'datetime', $conds['searchterm'] );
		}

		$this->db->order_by( 'datetime', 'desc' );
	}

	/**
	 * @param ref array $log_data
	 * @param int $log_id
	 * @return bool
	 */
	function save( &$log_data, $log_id = false )
	{
		// start the transaction
		$this->db->trans_start();

		if ( !$log_id ) { // insert new			
			$logged_in_user = $this->ps_auth->get_user_info();
			
			$log_data['datetime'] = date("Y-m-d H:i:s");
			$log_data['causer_id'] = $logged_in_user->user_id;
			if ( ! $this->db->insert( $this->table_name, $log_data )) {
				// if error in inserting new, rollback
				$this->db->trans_rollback();
        		return false;
			}

		} else {
			//else update the data
			$this->db->where( 'id', $log_id );
			if ( ! $this->db->update( $this->table_name, $log_data )) {
				// if error in updating, rollback
				$this->db->trans_rollback();
        		return false;
			}
		}
		// commit the transaction
		return $this->db->trans_commit();
	}

	/**
	 * @param int $log_id
	 * @return bool
	 */
	function delete( $log_id )
	{
		// start the transaction
		$this->db->trans_start();
		
		$this->db->where( $this->primary_key, $log_id );
		if ( ! $this->db->delete( $this->table_name )) {
			// if error in deleteing assigned members, rollback
			$this->db->trans_rollback();
			return false;
		}

		// commit the transaction
		return $this->db->trans_commit();
	}

	
			
}