<!-- <table class="table table-striped table-bordered"> -->
<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-striped table-bordered">
	<tr>
		<th><?php echo get_msg('no')?></th>
		<th><?php echo get_msg('user_name')?></th>
		<th><?php echo get_msg('user_email')?></th>
		<th><?php echo get_msg('user_phone')?></th>
		<th><?php echo get_msg('role')?></th>
		<th><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
	</tr>

	<?php $count = $this->uri->segment(6) or $count = 0; ?>
	<?php if ( !empty( $images ) && count( $images) > 0 ): ?>
			
		<?php foreach($images as $image): ?>
			
			<tr>
				<td><?php echo ++$count;?></td>
				<td><?php echo $image->user_name;?></td>
				<td><?php echo $image->user_email;?></td>
				<td><?php echo $image->user_phone;?></td>

				<td><?php echo $this->Role->get_name( $image->role_id );?></td>

				<td>
					<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo "$image->user_id";?>">
						<i class='fa fa-trash-o'></i>
					</a>
				</td>
			</tr>
		
		<?php endforeach; ?>

	<?php else: ?>
			
		<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

	<?php endif; ?>

</table>
</div>

<script>
function runAfterJQ() {

	$(document).ready(function(){

		// Delete Trigger
		$('.btn-delete').click(function(){
		
			// get id and links
			var user_id = $(this).attr('id');

			var post_url = "<?php 
			echo site_url('/admin/task_list/delete_image/'.@$image->id.'/'); ?>"

			// modify link with id
			$('.btn-yes').attr( 'href', post_url + user_id );
			$('.btn-no').attr( 'href', post_url + user_id );
		});

	});
}
</script>
