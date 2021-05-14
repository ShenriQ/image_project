<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_log extends BE_Controller {

	/**
	 * Constructs required variables
	 */
	function __construct() {
		parent::__construct( MODULE_CONTROL, 'ACTIVITY LOGS' );
	}

	function index() {

		//registered tasks filter
		$conds = array();

		// get rows count
		$this->data['rows_count'] = $this->Activitylog->count_all_by($conds);

		// get logs
		$this->data['logs'] = $this->Activitylog->get_all_by($conds, $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::index();
	}

	/**
	 * Searches for the first match in tasks
	 */
	function search() {

		// breadcrumb urls
		$data['action_title'] = get_msg( 'tasks_search' );

		// handle search term
		$search_term = $this->searchterm_handler( $this->input->post( 'searchterm' ));
		
		// condition
		$conds = array( 'searchterm' => $search_term );

		// get rows count
		$this->data['rows_count'] = $this->Activitylog->count_all_by($conds);

		// get logs
		$this->data['logs'] = $this->Activitylog->get_all_by($conds, $this->pag['per_page'], $this->uri->segment( 4 ) );
		
		parent::search();
	}

	
	/**
	 * Delete the log
	 */
	function delete( $id ) {

		// start the transaction
		$this->db->trans_start();

		// check access
		$this->check_access( DEL );
		
		if ( !$this->Activitylog->delete( $id )) {

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

}