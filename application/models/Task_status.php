<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_status extends PS_Model {

	/**
	 * Constructs the required data
	 */
	function __construct() 
	{
		parent::__construct( 'core_task_status', 'id' );
	}

}