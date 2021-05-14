<script>
function runAfterJQ() {

	$(document).ready(function(){

		// Delete Trigger
		$('.btn-delete').click(function(){
		
			// get id and links
			var id = $(this).attr('id');
			var btnYes = $('.btn-yes').attr('href');
			var btnNo = $('.btn-no').attr('href');

			// modify link with id
			$('.btn-yes').attr( 'href', btnYes + id );
			$('.btn-no').attr( 'href', btnNo + id );
		});

	});
}
</script>

<?php
	// Delete Confirm Message Modal
	$data = array(
		'title' => get_msg( 'delete_activitylog' ),
		'message' =>  get_msg( 'delete_activitylog_confirm_message' ),
	);
	
	$this->load->view( $template_path .'/components/delete_confirm_modal', $data );
?>