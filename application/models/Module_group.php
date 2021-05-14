<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model class for module group table
 */
class Module_group extends PS_Model {

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'core_menu_groups', 'group_id', 'group' );
	}

	/**
	 * Implement the where clause
	 *
	 * @param      array  $conds  The conds
	 */
	function custom_conds( $conds = array())
	{

		// group_id condition
		if ( isset( $conds['group_id'] )) {
			$this->db->where( 'group_id', $conds['group_id'] );
		}

		// group_name condition
		if ( isset( $conds['group_name'] )) {
			$this->db->where( 'group_name', $conds['group_name'] );
		}

		// group_icon condition
		if ( isset( $conds['group_icon'] )) {
			$this->db->where( 'group_icon', $conds['group_icon'] );
		}

		// group_lang_key condition
		if ( isset( $conds['group_lang_key'] )) {
			$this->db->where( 'group_lang_key', $conds['group_lang_key'] );
		}

		// searchterm
		if ( isset( $conds['searchterm'] )) {
			$this->db->group_start();
			$this->db->like( 'group_name', $conds['searchterm'] );
			$this->db->or_like( 'group_name', $conds['searchterm'] );
			$this->db->group_end();
		}

	}

	function save( &$data, $group_id = false )
	{
		// start the transaction
		$this->db->trans_start();

		if ( !$group_id ) { // insert new			
			if ( ! $this->db->insert( $this->table_name, $data )) {
				// if error in inserting new, rollback
				$this->db->trans_rollback();
        		return false;
			}

		} else {
			//else update the data
		
			$this->db->where( 'group_id', $group_id );
			
			if ( ! $this->db->update( $this->table_name, $data )) {
				// if error in updating, rollback
				$this->db->trans_rollback();
        		return false;
			}
		}
		// commit the transaction
		return $this->db->trans_commit();
	}
}