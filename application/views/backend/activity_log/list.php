<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-striped table-bordered">
	<tr>
		<th><?php echo get_msg('date')?></th>
		<th><?php echo get_msg('description')?></th>
		<th><?php echo get_msg('user_name')?></th>
		<th><?php echo get_msg('user_role')?></th>
		<th><?php echo get_msg('request_url')?></th>
		<th><?php echo get_msg('request_type')?></th>
		<th><?php echo get_msg('request_ip')?></th>

		<?php if ( $this->ps_auth->has_access( DEL )): ?>
			<th><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
		<?php endif; ?>

	</tr>

	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $logs ) && count( $logs->result()) > 0 ): ?>
			
		<?php foreach($logs->result() as $log): ?>
			
			<tr>
				<!-- <td><?php echo ++$count;?></td> -->
				<td><?php echo $log->datetime;?></td>
				<td><?php echo $log->name;?></td>
				<td><?php echo $this->User->get_one($log->causer_id)->user_name ;?></td>
				<td><?php echo $this->Role->get_name($log->causer_role) ;?></td>
				<td><?php echo $log->request_url;?></td>
				<td><?php echo $log->request_type;?></td>
				<td><?php echo $log->causer_ip;?></td>
				
				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td>
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo "$log->id";?>">
							<i class='fa fa-trash-o'></i>
						</a>
					</td>
				
				<?php endif; ?>

			</tr>
		
		<?php endforeach; ?>

	<?php else: ?>
			
		<?php $this->load->view( $template_path .'/partials/no_data' ); ?>

	<?php endif; ?>

</table>
</div>