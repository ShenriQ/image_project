<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Systemlog_list extends BE_Controller {

	/**
	 * Constructs required variables
	 */
	function __construct() {
		parent::__construct( MODULE_CONTROL, 'SYSTEM LOG LIST' );
	}

	function getSystemLogFiles() {
		$path = "./application/logs";

		$data = array();
		foreach (glob("$path/*.log") as $filename) {
			$item = array(
				'name' => $filename,
				'size' => filesize($filename),
				'date' => date ("F d Y", filemtime($filename)),
				'time' => date ("H:i:s", filemtime($filename))
			);
			array_push($data, $item);
		}
		return $data;
	}

	function index() {

		$log_files = $this->getSystemLogFiles();

		// get rows count
		$this->data['rows_count'] = count($log_files);

		$limit = count($log_files);
		if ( $this->pag['per_page'] ) {
			$limit = $this->pag['per_page'];
		}
		
		$offset = 0;
		if ( $this->uri->segment( 4 ) ) { // offset
			$offset = $this->uri->segment( 4 );
		}
		// get logs
		$this->data['logs'] = array_slice($log_files, $offset, $limit); 

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
		
		$log_files = $this->getSystemLogFiles();

		$filtered_files = array();
		if ($search_term != NULL && $search_term != '') {
			foreach ($log_files as $file) {
				if(strpos($file["name"], $search_term)) {
					array_push($filtered_files, $file);
				}
			}
		}
		else {
			$filtered_files = $log_files;
		}
		// get rows count
		$this->data['rows_count'] = count($filtered_files);

		$limit = count($filtered_files);
		if ( $this->pag['per_page'] ) {
			$limit = $this->pag['per_page'];
		}
		
		$offset = 0;
		if ( $this->uri->segment( 4 ) ) { // offset
			$offset = $this->uri->segment( 4 );
		}
		// get logs
		$this->data['logs'] = array_slice($filtered_files, $offset, $limit); 

		parent::search();
	}

	function download( $name ) {
		$file_full_name = "./application/logs/$name.log";
		$this->load->helper('download');
		force_download($file_full_name, NULL);

		redirect( $this->module_site_url());		
	}

	/**
	 * Delete the task
	 */
	function delete( $name ) {
		$name = str_replace('%20', ' ', $name);
		$file_full_name = "./application/logs/$name.log";
		// check access
		$this->check_access( DEL );

		if(unlink( $file_full_name ) == false) {
			// set error message
			$this->set_flash_msg( 'error', get_msg( 'error_deleting_system_log_file' ));
		}
		else {
			$this->set_flash_msg( 'success', get_msg( 'success' ));
		}

		redirect( $this->module_site_url());		
	}

}