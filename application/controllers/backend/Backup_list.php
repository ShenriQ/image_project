<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup_list extends BE_Controller {

	/**
	 * Constructs required variables
	 */
	function __construct() {
		parent::__construct( MODULE_CONTROL, 'BACKUP LIST' );
	}

	function index() {
		// get rows count
		$this->data['rows_count'] = $this->Backup->count_all_by($conds);

		// get tasks
		$this->data['backups'] = $this->Backup->get_all_by($conds, $this->pag['per_page'], $this->uri->segment( 4 ) );

		// load index logic
		parent::index();
	}

	/**
	 * Searches for the first match in tasks
	 */
	function search() {

		// breadcrumb urls
		$data['action_title'] = get_msg( 'backups_search' );

		// handle search term
		$search_term = $this->searchterm_handler( $this->input->post( 'searchterm' ));
		
		// condition
		$conds = array( 'searchterm' => $search_term );

		$this->data['rows_count'] = $this->Backup->count_all_by( $conds );

		$this->data['backups'] = $this->Backup->get_all_by( $conds, $this->pag['per_page'], $this->uri->segment( 4 ));
		
		parent::search();
	}

	/**
	 * Create the backup
	 */
	function add() {
		// check access
		$this->check_access( ADD );

		// Load the DB utility class
		$this->load->dbutil();
		// Backup your entire database and assign it to a variable
		$export = $this->dbutil->backup(array(
			'format'		=> 'txt', // gzip, zip, txt
		));

		$dir = "./backups";
		$file_name = 'db_backup_'.(new DateTime())->format('YmdHisv').'.sql';
		if(!is_dir($dir)) mkdir($dir);

		$save_result = file_put_contents("$dir/$file_name", $export);
		if($save_result == false) {
			$this->set_flash_msg( 'error', get_msg( 'backup_file_saving_error' ));
		}
		else {
			$backup_data = array(
				'path' => "$dir/$file_name",
				'size' => $save_result
			);
			// save data
			if ( ! $this->Backup->save( $backup_data )) {
				$this->set_flash_msg( 'error', get_msg( 'err_model' ));
			} else {
				// if no eror in inserting
				$this->set_flash_msg( 'success', get_msg( 'success' ));
			}
		}

		redirect( $this->module_site_url());
	}

	/**
	 * Update the user
	 */
	function download( $id ) {
		$backup_data = $this->Backup->get_one( $id );
		
		$this->load->helper('download');
		force_download($backup_data->path, NULL);

		redirect( $this->module_site_url());		
	}

	/**
	 * Delete the task
	 */
	function delete( $id ) {
		// check access
		$this->check_access( DEL );

		$backup_data = $this->Backup->get_one( $id );
		
		if(unlink( $backup_data->path ) == false || $this->Backup->delete( $id ) == false ) {
			// set error message
			$this->set_flash_msg( 'error', get_msg( 'error_deleting_backup_file' ));
		}
		else {
			$this->set_flash_msg( 'success', get_msg( 'success' ));
			parent::delete( $id );
		}

		redirect( $this->module_site_url());		
	}

}