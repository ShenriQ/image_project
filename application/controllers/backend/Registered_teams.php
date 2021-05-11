<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Users crontroller for BE_USERS table
 */
class Registered_teams extends BE_Controller {

	/**
	 * Constructs required variables
	 */
	function __construct() {
		parent::__construct( MODULE_CONTROL, 'REGISTERED_TEAMS' );
	}

	/**
	 * List down the registered users
	 */
	function index() {

		//registered teams filter
		$conds = array( 'is_trashed' => 0 );

		// get rows count
		$this->data['rows_count'] = $this->Team->count_all_by($conds);

		// get teams
		$this->data['teams'] = $this->Team->get_all_by($conds, $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::index();
	}

	/**
	 * Searches for the first match in system users
	 */
	function search() {

		// breadcrumb urls
		$data['action_title'] = get_msg( 'team_search' );

		// handle search term
		$search_term = $this->searchterm_handler( $this->input->post( 'searchterm' ));
		
		// condition
		$conds = array( 'searchterm' => $search_term );

		$this->data['rows_count'] = $this->Team->count_all_by( $conds );

		$this->data['teams'] = $this->Team->get_all_by( $conds, $this->pag['per_page'], $this->uri->segment( 4 ));
		
		parent::search();
	}

	/**
	 * Create the user
	 */
	function add() {

		// breadcrumb
		$this->data['action_title'] = get_msg( 'add' );

		// call add logic
		parent::add();
	}

	/**
	 * Update the user
	 */
	function edit( $id ) {

		// breadcrumb
		$this->data['action_title'] = get_msg( 'edit' );

		// load team
		$this->data['team'] = $this->Team->get_one( $id );

		// call update logic
		parent::edit( $id );
	}

	/**
	 * Delete the team
	 */
	function delete( $id ) {

		// start the transaction
		$this->db->trans_start();

		// check access
		$this->check_access( DEL );
		
		// delete categories and images
		if ( !$this->Team->delete( $id )) {

			// set error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));

			// rollback
			$this->trans_rollback();

			// redirect to list view
			redirect( $this->module_site_url());
		}
			
		/**
		 * Check Transcation Status
		 */
		if ( !$this->check_trans()) {

			$this->set_flash_msg( 'error', get_msg( 'err_model' ));	
		} else {
        	
			$this->set_flash_msg( 'success', get_msg( 'success' ));
		}
		
		redirect( $this->module_site_url());
	}

	/**
	 * @param      boolean  $team_id  The user identifier
	 */
	function save( $team_id = false ) {
		// prepare user object and permission objects
		$team_data = array();

		if ( $this->has_data( 'team_name' )) {
			$team_data['name'] = $this->get_data( 'team_name' );
		}

		if( $this->has_data( 'description' )) {
			$team_data['description'] = $this->get_data( 'description' );
		}
		
		// save data
		// print_r($team_data);die;
		if ( ! $this->Team->save( $team_data, $team_id )) {
			// if there is an error in inserting user data,	
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
		} else {
			// if no eror in inserting
			$this->set_flash_msg( 'success', get_msg( 'success' ));
		}

		redirect( $this->module_site_url());
	}


	/**
	 * Determines if valid input.
	 *
	 * @return     boolean  True if valid input, False otherwise.
	 */
	function is_valid_input() {
		
		$rule = 'required';

		$this->form_validation->set_rules( 'team_name', get_msg( 'name' ), $rule);

		if ( $this->form_validation->run() == FALSE ) {
		// if there is an error in validating,

			return false;
		}

		return true;
	}
}