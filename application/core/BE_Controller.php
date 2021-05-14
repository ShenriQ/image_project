<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Backend Controller which extends PS main Controller
 * 1) Loading Template
 */
class BE_Controller extends PS_Controller {

	/**
	 * constructs required variables
	 * 1) template path
	 * 2) base url
	 * 3) site url
	 *
	 * @param      <type>  $auth_level   The auth level
	 * @param      <type>  $module_name  The module name
	 */
	function __construct( $auth_level, $module_name )
	{
		parent::__construct( $auth_level, $module_name );

		$this->load->helper('url');

		// template path
		$this->template_path = $this->config->item( 'be_view_path' );

		// base url & site url
		$be_url = $this->config->item( 'be_url' );

		if ( !empty( $be_url )) {
		// if fe controller path is not empty,
			
			$this->module_url = $be_url .'/'. $this->module_url;
		}

		// load meta data
		$this->load_metadata();

		// get paignation config
		$this->pag = $this->config->item('pagination');
	}

	/**
	 * Loads a template.
	 *
	 * @param      <type>  $view   The view
	 */
	function load_template( $view = false, $data = false, $is_shops = false, $load_side_menus = true ) 
	{
		// load header
		$this->load_view( 'partials/header' );

		// load view
		if ( !empty( $view )){
			if($is_shops) {
				$this->load_view( 'partials/shop_structure', array( 'view' => $view, 'data' => $data ));
			} else {
				
				$this->load_view( 'partials/structure', array( 'view' => $view, 'data' => $data ), $load_side_menus);
			}
		}

		// load footer
		$this->load_view( 'partials/footer' );
	}

	/**
	 * Index
	 */
	function index()
	{
		$logged_in_user = $this->ps_auth->get_user_info();
		$request_ip = $this->input->ip_address();
		$request_url = current_url();
		$request_type = "GET";
		if ( $this->is_POST()) {
			$request_type = "POST";
		}
		save_activity_log( 'Viewed', $logged_in_user->user_id, $logged_in_user->role_id, $request_url, $request_type, $request_ip );
		$this->list_view( $this->module_site_url( 'index' ));
	}

	function delete( $id ) {
		$logged_in_user = $this->ps_auth->get_user_info();
		$request_ip = $this->input->ip_address();
		$request_url = current_url();
		$request_type = "GET";
		if ( $this->is_POST()) {
			$request_type = "POST";
		}
		save_activity_log( 'Deleted', $logged_in_user->user_id, $logged_in_user->role_id, $request_url, $request_type, $request_ip );
	}
	/**
	 * Index
	 */
	function string_index($language_id=false)
	{
		$this->language_list_view( $this->module_site_url( 'index' ),$language_id);
	}

	/**
	 * Search
	 */
	function string_search($language_id)
	{
		$this->language_list_view( $this->module_site_url( 'search' ),$language_id);	
	}


	/**
	 * Index
	*/
	function att_header_index($product_id=false)
	{
		$this->att_header_list_view( $this->module_site_url( 'index' ),$product_id);
	}

	/**
	 * Index
	 */
	function att_detail_index($id=false, $product_id=false)
	{
		$this->att_detail_list_view( $this->module_site_url( 'index' ), $id,$product_id);
	}

	/**
	 * Search
	 */
	function search()
	{
		$logged_in_user = $this->ps_auth->get_user_info();
		$request_ip = $this->input->ip_address();
		$request_url = current_url();
		$request_type = "GET";
		if ( $this->is_POST()) {
			$request_type = "POST";
		}
		save_activity_log( 'Searched', $logged_in_user->user_id, $logged_in_user->role_id, $request_url, $request_type, $request_ip );

		$this->list_view( $this->module_site_url( 'search' ));	
	}

	function usersearch()
	{
		$this->load_template( 'system_users/search', $this->data, true );	
	}

	/**
	 * Search
	 */
	function attribute_search($product_id=false)
	{
		$this->att_header_search( $this->module_site_url( 'search' ), $product_id);	
	}

	/**
	 * Search
	*/
	function att_detail_search($id=false, $product_id=false)
	{
		$this->attribute_detail_search( $this->module_site_url( 'search' ) , $id, $product_id);	
	}

	/**
	 * List View
	 *
	 * @param      <type>  $base_url  The base url
	 */
	function list_view( $base_url )
	{
		// pagination
		$rows_count = $this->data['rows_count'];
		$this->load_pag( $base_url, $rows_count );

		// load add list
		$this->load_list( $this->data );
	}

	/**
	 * List View
	 *
	 * @param      <type>  $base_url  The base url
	 */
	function language_list_view( $base_url , $language_id=false )
	{
		// pagination
		$rows_count = $this->data['rows_count'];
		$this->load_pag( $base_url, $rows_count );
		
		$this->data['language_id'] = $language_id;

		// load add list
		$this->load_list_language( $this->data );
	}

	/**
	 * List View
	 *
	 * @param      <type>  $base_url  The base url
	 */
	function att_header_list_view( $base_url , $product_id=false)
	{
		// pagination
		$rows_count = $this->data['rows_count'];
		$this->load_pag( $base_url, $rows_count );
		
		$this->data['product_id'] = $product_id;

		// load add list
		$this->load_list_att_header( $this->data );
	}

	/**
	 * List View
	 *
	 * @param      <type>  $base_url  The base url
	 */
	function att_detail_list_view( $base_url , $id=false, $product_id=false)
	{
		// pagination
		$rows_count = $this->data['rows_count'];
		$this->load_pag( $base_url, $rows_count );
		
		$this->data['header_id'] = $id;
		$this->data['product_id'] = $product_id;

		// load add list
		$this->load_list_att_detail( $this->data );
	}

	/**
	 * List View
	 *
	 * @param      <type>  $base_url  The base url
	 */
	function att_header_search( $base_url , $product_id=false )
	{
		// pagination
		$rows_count = $this->data['rows_count'];
		$this->load_pag( $base_url, $rows_count );

		$this->data['product_id'] = $product_id;

		// load add list
		$this->load_attribute_search( $this->data );
	}


	/**
	 * List View
	 *
	 * @param      <type>  $base_url  The base url
	 */
	function attribute_detail_search( $base_url, $id=false, $product_id=false )
	{
		// pagination
		$rows_count = $this->data['rows_count'];
		$this->load_pag( $base_url, $rows_count );

		$this->data['header_id'] = $id;
		$this->data['product_id'] = $product_id;

		// load add list
		$this->load_attribute_search( $this->data );
	}

	/**
	 * Add a new record
	 */
	function add()
	{
		// check access
		$this->check_access( ADD );

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->is_valid_input()) {
				// save user info
				$this->save();

				$logged_in_user = $this->ps_auth->get_user_info();
				$request_ip = $this->input->ip_address();
				$request_url = current_url();
				$request_type = "GET";
				if ( $this->is_POST()) {
					$request_type = "POST";
				}
				save_activity_log( 'Created', $logged_in_user->user_id, $logged_in_user->role_id, $request_url, $request_type, $request_ip );
			}
		}
		// load entry form
		$this->load_form( $this->data );
	}

	/**
	 * Add a new record
	 */
	function add_language($language_id = 0)
	{
		// check access
		$this->check_access( ADD );

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->is_valid_input()) {

				// save user info
				$this->save(false,$language_id);

				$logged_in_user = $this->ps_auth->get_user_info();
				$request_ip = $this->input->ip_address();
				$request_url = current_url();
				$request_type = "GET";
				if ( $this->is_POST()) {
					$request_type = "POST";
				}
				save_activity_log( 'Added a language', $logged_in_user->user_id, $logged_in_user->role_id, $request_url, $request_type, $request_ip );
			}
		}

		// load entry form
		$this->load_language_form( $this->data );
	}
		

	/**
	 * Add a new record
	 */
	function shopadd()
	{
		// check access
		$this->check_access( ADD );

		if ( $this->is_POST()) {
		// if the method is post
			
			// server side validation
			if ( $this->is_valid_input()) {
				
				// save user info
				$this->save();
			}
		}

		// load entry form
		$this->load_template( 'shops/entry_form',$this->data, true );
	}

	/**
	 * Add a new record
	 */
	function useradd()
	{

		// check access
		$this->check_access( ADD );

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->is_valid_input()) {

				// save user info
				$this->save();
			}
		}

		// load entry form
		$this->load_template( 'system_users/entry_form',$this->data, true );
	}

	/**
	 * Add a new record
	 */
	function add_att_header($product_id = 0)
	{

		// check access
		$this->check_access( ADD );

		if ( $this->is_POST()) {
		// if the method is post
			// server side validation
			if ( $this->is_valid_input()) {
				// save user info
				$this->save(false,$product_id);
				
			}

		}

		// load entry form
		$this->load_att_form( $this->data );
	}

	/**
	 * Add a new record
	 */
	function detail_add($header_id = 0,$product_id = 0)
	{

		// check access
		$this->check_access( ADD );

		if ( $this->is_POST()) {
		// if the method is post
			// server side validation
			if ( $this->is_valid_input()) {
				// save user info
				$this->save(false,$header_id,$product_id);
				
			}

		}

		// load entry form
		$this->load_list_addatt_detail( $this->data );
	}

	/**
	 * Edit the exiting record
	 */
	function edit( $id )
	{
		// check access
		$this->check_access( EDIT );

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->is_valid_input( $id )) {

				// save user info
				$this->save( $id );

				$logged_in_user = $this->ps_auth->get_user_info();
				$request_ip = $this->input->ip_address();
				$request_url = current_url();
				$request_type = "GET";
				if ( $this->is_POST()) {
					$request_type = "POST";
				}
				save_activity_log( 'Updated', $logged_in_user->user_id, $logged_in_user->role_id, $request_url, $request_type, $request_ip );
			}
		}
		// load entry form
		$this->load_form( $this->data );
	}

	/**
	 * Edit the exiting record
	 */
	function string_edit( $id )
	{
		// check access
		$this->check_access( EDIT );

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->is_valid_input( $id )) {

				// save user info
				$this->save( $id, $language_id );
			}
		}

		// load entry form
		$this->load_language_form( $this->data );
	}

	/**
	 * Edit the exiting record
	 */
	function shopedit( $id )
	{
		// check access
		$this->check_access( EDIT );

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->is_valid_input( $id )) {

				// save user info
				$this->save( $id );
			}
		}

		// load entry form
		$this->load_form( $this->data );
	}

		/**
	 * Edit the exiting record
	 */
	function useredit( $user_id )
	{
		// check access
		$this->check_access( EDIT );

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->is_valid_input( $user_id )) {
				
				// save user info
				$this->save( $user_id );
			}
		}

		// load entry form
		$this->load_template( 'system_users/entry_form',$this->data, true );
	}

	/**
	 * Edit the exiting record
	 */
	function att_edit( $id ,$product_id = 0 )
	{
		
		// check access
		$this->check_access( EDIT );

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->is_valid_input( $id )) {

				// save user info
				$this->save( $id ,$product_id);
			}
		}

		// load entry form
		$this->load_list_addatt( $this->data );
	}

	/**
	 * Edit the exiting record
	 */
	function detail_edit( $id, $header_id , $product_id)
	{
		// check access
		$this->check_access( EDIT );

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->is_valid_input( $id )) {

				// save user info
				$this->save( $id, $header_id, $product_id);
			}
		}

		// load entry form
		$this->load_list_addatt_detail( $this->data );

	}

	/**
	 * Delete Cover Photo
	 *
	 * @param      <type>  $img_id  The image identifier
	 */
	function delete_cover_photo( $img_id, $id )
	{
		// check edit access
		$this->check_access( EDIT );

		// start the db transaction
		$this->db->trans_start();

		// delete image
		if ( !$this->delete_images_by( array( 'img_id' => $img_id ))) {

			// rollback
			$this->trans_rollback();

			//redirect
			redirect( $this->module_site_url( '/edit/'. $id ));
		}


		// commit the transaction
		if ( ! $this->check_trans()) {
        	
			// set flash error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
		} else {

			$this->set_flash_msg( 'success', get_msg( 'success_img_delete' ));
		}

		redirect( $this->module_site_url( '/edit/'. $id ));
	}

	/**
	 * Upload image
	 *
	 * @param      integer  $id  The category identifier
	 */
	function replace_profile_photo( $id )
	{
		// check edit access
		$this->check_access( EDIT );

		// start the db transaction
		$this->db->trans_start();

		/**
		 * Delete Images
		 */

		// prepare condition
		$user = $this->User->get_one( $id );
		
		$this->ps_image->delete_images( $user->user_profile_photo );
		
		/**
		 * Insert New Image
		 */
		if ( ! $this->insert_profile_images( $_FILES, $id )) {
		// if error in saving image

			// commit the transaction
			$this->db->trans_rollback();
			
			redirect( $this->module_site_url( ));
		}

		// commit the transaction
		if ( ! $this->check_trans()) {
        	
			// set flash error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
		} else {

			$this->set_flash_msg( 'success', get_msg( 'success_upload' ));
		}

		redirect( $this->module_site_url( ));
	}

	/**
	 * Upload image
	 *
	 * @param      integer  $id  The category identifier
	 */
	function replace_cover_photo( $img_type, $id )
	{
		// check edit access
		$this->check_access( EDIT );

		// start the db transaction
		$this->db->trans_start();

		/**
		 * Delete Images
		 */

		// prepare condition
		$conds = array( 'img_type' => $img_type, 'img_parent_id' => $id );
		if ( !$this->delete_images_by( $conds )) {
		// if error in deleting image, redirect
			// rollback
			$this->db->trans_rollback();

			redirect( $this->module_site_url( '/edit/'. $id ));
		}
		
		/**
		 * Insert New Image
		 */
		if ( ! $this->insert_images( $_FILES, $img_type, $id )) {
		// if error in saving image
			// commit the transaction
			$this->db->trans_rollback();
			redirect( $this->module_site_url( '/edit/'. $id ));
			
		}
		// commit the transaction
		if ( ! $this->check_trans()) {
        	
			// set flash error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
		} else {

			$this->set_flash_msg( 'success', get_msg( 'success_upload' ));
		}

		redirect( $this->module_site_url( '/edit/'. $id ));
	}

	/**
	 * Insert the image records to image table
	 *
	 * @param      <type>   $upload_data    The upload data
	 * @param      <type>   $img_type       The image type
	 * @param      <type>   $img_parent_id  The image parent identifier
	 *
	 * @return     boolean  ( description_of_the_return_value )
	 */
	function insert_images( $files, $img_type, $img_parent_id )
	{
		// return false if the image type is empty
		if ( empty( $img_type )) return false;

		// return false if the parent id is empty
		if ( empty( $img_parent_id )) return false;

		// upload images
		$upload_data = $this->ps_image->upload( $files );
		
		if ( isset( $upload_data['error'] )) {
		// if there is an error in uploading

			// set error message
			$this->data['error'] = $upload_data['error'];
			
			return;
		}

		// save image 
		foreach ( $upload_data as $upload ) {
			if ($upload['image_width'] == "" && $upload['file_ext'] == ".ico") {
				// prepare image data
				$image = array(
					'img_parent_id'=> $img_parent_id,
					'img_type' => $img_type,
					'img_desc' => "",
					'img_path' => $upload['file_name'],
					'img_width'=> "",
					'img_height'=> ""
				);
			} else {
				// prepare image data
				$image = array(
					'img_parent_id'=> $img_parent_id,
					'img_type' => $img_type,
					'img_desc' => "",
					'img_path' => $upload['file_name'],
					'img_width'=> $upload['image_width'],
					'img_height'=> $upload['image_height']
				);
			}

			if ( ! $this->Image->save( $image )) {
			// if error in saving image
				
				// set error message
				$this->data['error'] = get_msg( 'err_model' );
				
				return false;
			}
		}

		return true;
	}

	function insert_profile_images( $files, $user_id )
	{

		// return false if the parent id is empty
		if ( empty( $user_id )) return false;

		// upload images
		//print_r($files); die;
		$upload_data = $this->ps_image->upload( $files );
			
		if ( isset( $upload_data['error'] )) {
		// if there is an error in uploading

			// set error message
			$this->data['error'] = $upload_data['error'];
			
			return;
		}

		// save user
		foreach ( $upload_data as $upload ) {
			$image = array(
				'user_profile_photo'=> $upload['file_name']
			);

			if ( ! $this->User->save( $image,$user_id )) {
			// if error in saving image
				
				// set error message
				$this->data['error'] = get_msg( 'err_model' );
				
				return false;
			}
		}

		return true;
	}

	/**
	 * Delete Cover Photo
	 *
	 * @param      <type>  $img_id  The image identifier
	 */
	function delete_profile_photo( $id )
	{
		// check edit access
		$this->check_access( EDIT );

		// start the db transaction
		$this->db->trans_start();

		// delete image
		$user = $this->User->get_one( $id );
		
		$this->ps_image->delete_images( $user->user_profile_photo );

		$image = array(
			'user_profile_photo'=> ""
		);

		$this->User->save( $image,$id );

		// commit the transaction
		if ( ! $this->check_trans()) {
        	
			// set flash error message
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
		} else {

			$this->set_flash_msg( 'success', get_msg( 'success_img_delete' ));
		}

		redirect( $this->module_site_url( ));
	}

	/**
	 * Delete Image by id and type
	 *
	 * @param      <type>  $conds  The conds
	 */
	function delete_images_by( $conds )
	{
		/**
		 * Delete Images from folder
		 *
		 */
		$images = $this->Image->get_all_by( $conds );
		if ( !empty( $images )) {
			foreach ( $images->result() as $img ) {
				if ( ! $this->ps_image->delete_images( $img->img_path ) ) {
				// if there is an error in deleting images

					$this->set_flash_msg( 'error', get_msg( 'err_del_image' ));
					return false;
				}
			}
		}

		/**
		 * Delete images from database table
		 */
		if ( ! $this->Image->delete_by( $conds )) {
			$this->set_flash_msg( 'error', get_msg( 'err_model' ));
			return false;
		}

		return true;
	}

	function insert_images_icon_and_cover( $files, $img_type, $img_parent_id, $type )
	{
		
		// return false if the image type is empty
		if ( empty( $img_type )) return false;

		// return false if the parent id is empty
		if ( empty( $img_parent_id )) return false;

		
		if($type == "cover") {

			// upload images
			$upload_data = $this->ps_image->upload_cover( $files );
				
			if ( isset( $upload_data['error'] )) {
			// if there is an error in uploading

				// set error message
				$this->data['error'] = $upload_data['error'];
				
				return;
			}
			$image = array(
				'img_parent_id'=> $img_parent_id,
				'img_type' => $img_type,
				'img_desc' => "",
				'img_path' => $upload_data[0]['file_name'],
				'img_width'=> $upload_data[0]['image_width'],
				'img_height'=> $upload_data[0]['image_height']
			);
			if ( ! $this->Image->save( $image )) {
			// if error in saving image
				
				// set error message
				$this->data['error'] = get_msg( 'err_model' );
				
				return false;
			}
		} else if($type == "icon") {

			// upload images
			$upload_data = $this->ps_image->upload_icon( $files );
				
			if ( isset( $upload_data['error'] )) {
			// if there is an error in uploading

				// set error message
				$this->data['error'] = $upload_data['error'];
				
				return;
			}
			$image = array(
				'img_parent_id'=> $img_parent_id,
				'img_type' => $img_type,
				'img_desc' => "",
				'img_path' => $upload_data[0]['file_name'],
				'img_width'=> $upload_data[0]['image_width'],
				'img_height'=> $upload_data[0]['image_height']
			);

			
			if ( ! $this->Image->save( $image )) {
			// if error in saving image
				
				// set error message
				$this->data['error'] = get_msg( 'err_model' );
				
				return false;
			}

		}
		
		return true;
	}

	/**
	 * Edit the status record
	 */
	function status_edit( $id , $status_id )
	{	
		// check access
		$this->check_access( EDIT );

		if ( $this->is_POST()) {
		// if the method is post

			// server side validation
			if ( $this->input->post()) {

				// save user info
				$this->save( $id, $status_id);
			}
		}

		// load entry form
		redirect(site_url('admin/transactions/detail/'. $id));
	}
}