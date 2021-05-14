<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Backup Model for core_backup table
 */
class Backup extends PS_Model {

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'core_backup', 'id' );
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
		// created_by_id condition
		if ( isset( $conds['created_by_id'] )) {
			$this->db->where( 'created_by_id', $conds['created_by_id'] );
		}
		// path condition
		if ( isset( $conds['path'] )) {
			$this->db->where( 'path', $conds['path'] );
		}
		// size condition
		if ( isset( $conds['size'] )) {
			$this->db->where( 'size', $conds['size'] );
		}
		
		// searchterm
		if ( isset( $conds['searchterm'] )) {
			$this->db->like( 'path', $conds['searchterm'] );
			$this->db->or_like( 'created_at', $conds['searchterm'] );
		}

		$this->db->order_by( 'created_at', 'desc' );
	}

	/**
	 * @param ref array $backup_data
	 * @param int $backup_id
	 * @return bool
	 */
	function save( &$backup_data, $backup_id = false )
	{
		// start the transaction
		$this->db->trans_start();

		if ( !$backup_id ) { // insert new			
			$logged_in_user = $this->ps_auth->get_user_info();
			
			$backup_data['created_at'] = date("Y-m-d H:i:s");
			$backup_data['created_by_id'] = $logged_in_user->user_id;

			var_dump($backup_data);
			if ( ! $this->db->insert( $this->table_name, $backup_data )) {
				// if error in inserting new, rollback
				$this->db->trans_rollback();
        		return false;
			}

		} else {
			//else update the data
			$this->db->where( 'id', $backup_id );
			if ( ! $this->db->update( $this->table_name, $backup_data )) {
				// if error in updating, rollback
				$this->db->trans_rollback();
        		return false;
			}
		}
		// commit the transaction
		return $this->db->trans_commit();
	}
		
}