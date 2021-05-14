<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sample_list extends BE_Controller {

	/**
	 * Constructs required variables
	 */
	function __construct() {
		parent::__construct( MODULE_CONTROL, 'SAMPLE LIST' );
	}

	function index() {

		//registered samples filter
		$conds = array( 'is_trashed' => 0 );

		// get rows count
		$this->data['rows_count'] = $this->Sample->count_all_by($conds);

		// get samples
		$this->data['samples'] = $this->Sample->get_all_by($conds, $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::index();
	}

	/**
	 * Searches for the first match in samples
	 */
	function search() {

		// breadcrumb urls
		$data['action_title'] = get_msg( 'samples_search' );

		// handle search term
		$search_term = $this->searchterm_handler( $this->input->post( 'searchterm' ));
		
		// condition
		$conds = array( 'searchterm' => $search_term );

		$this->data['rows_count'] = $this->Sample->count_all_by( $conds );

		$this->data['samples'] = $this->Sample->get_all_by( $conds, $this->pag['per_page'], $this->uri->segment( 4 ));
		
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
	function edit( $id, $current_tab = 'sampleinfo' ) {

		// breadcrumb
		$this->data['action_title'] = get_msg( 'edit' );

		// load sample
		$this->data['sample'] = $this->Sample->get_one( $id );
		// // get team members

		// $conds = array();
		// $this->data['images'] = $this->Team_member->getmembers( $id, $conds );

		// // get idle users to add more
		// $this->data['idle_users'] = $this->Team_member->getIdleUsers();
		// get team samples
		$this->data['current_tab'] = $current_tab;
		$this->data['path_form'] = '/entry_editform';
		// call update logic
		parent::edit( $id );
	}

	function addmember( $id ) {
		// load team
		$team = $this->Team->get_one( $id );
		
		$team_member_data = array();
		if ( $this->has_data( 'member_user' )) {
			$team_member_data['user_id'] = $this->get_data( 'member_user' );
		}
		$team_member_data['team_id'] = $id;
		if ( $this->Team_member->save_member($team_member_data) ) {
			$this->set_flash_msg( 'success', get_msg( 'success' ));
		} else {
			$this->set_flash_msg( 'error', get_msg( 'failed' ));
		}

		$this->data['current_tab'] = 'members';
		redirect( site_url('/admin/registered_teams/edit/' . $id ."/members") );
	}

	function delete_member( $team_id, $user_id ) {
		
		if ( $this->Team_member->delete( $team_id, $user_id) ) {
			$this->set_flash_msg( 'success', get_msg( 'success' ));
		} else {
			$this->set_flash_msg( 'error', get_msg( 'failed' ));
		}

		$this->data['current_tab'] = 'members';
		redirect( site_url('/admin/registered_teams/edit/' . $team_id ."/members") );
	}
	
	function search_member( $id, $current_tab = 'teaminfo' ) {

		// breadcrumb
		$this->data['action_title'] = get_msg( 'edit' );

		// load team
		$this->data['team'] = $this->Team->get_one( $id );
		// get team members

		$conds = array();
		if ( $this->has_data( 'searchterm' )) {
			// handle search term
			$search_term = $this->searchterm_handler( $this->input->post( 'searchterm' ));
			// condition
			$conds = array( 'searchterm' => $search_term );
		}
		$this->data['members'] = $this->Team_member->getmembers( $id, $conds );

		// get idle users to add more
		$this->data['idle_users'] = $this->Team_member->getIdleUsers();
		// get team samples
		$this->data['current_tab'] = $current_tab;
		$this->data['path_form'] = '/entry_editform';
		
		// load entry form
		$this->load_form( $this->data );
	}
	/**
	 * Delete the sample
	 */
	function delete( $id ) {

		// start the transaction
		$this->db->trans_start();

		// check access
		$this->check_access( DEL );
		
		if ( !$this->Sample->delete( $id )) {

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
			parent::delete( $id );
		}
		
		redirect( $this->module_site_url());
	}

	/**
	 * @param      boolean  $team_id  The user identifier
	 */
	function save( $sample_id = false ) {
		// prepare user object and permission objects
		$sample_data = array();

		if ( $this->has_data( 'sample_index' )) {
			$sample_data['sample_index'] = $this->get_data( 'sample_index' );
		}

		if( $this->has_data( 'inspection_time' )) {
			$sample_data['inspection_time'] = $this->get_data( 'inspection_time' );
			$date_time = explode("T", $sample_data['inspection_time']);
			// $dateTime = DateTime::createFromFormat('Y-m-d H:i', $date_time[0]." ".$date_time[1]); 
			$sample_data['inspection_time'] = $date_time[0]." ".$date_time[1];
		}

		if( $this->has_data( 'num_of_imgs_each_col' )) {
			$sample_data['num_of_imgs_each_col'] = $this->get_data( 'num_of_imgs_each_col' );
		}

		if( $this->has_data( 'num_of_imgs_each_row' )) {
			$sample_data['num_of_imgs_each_row'] = $this->get_data( 'num_of_imgs_each_row' );
		}
		
		
		// save data
		if ( ! $this->Sample->save( $sample_data, $sample_id )) {
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

		$this->form_validation->set_rules( 'sample_index', get_msg( 'sample_index' ), $rule);
		$this->form_validation->set_rules( 'inspection_time', get_msg( 'inspection_time' ), $rule);
		$this->form_validation->set_rules( 'num_of_imgs_each_col', get_msg( 'num_of_imgs_each_col' ), $rule);
		$this->form_validation->set_rules( 'num_of_imgs_each_row', get_msg( 'num_of_imgs_each_row' ), $rule);

		if ( $this->form_validation->run() == FALSE ) {
			return false;
		}

		return true;
	}
}