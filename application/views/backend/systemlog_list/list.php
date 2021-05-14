<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-striped table-bordered">
	<tr>
		<th><?php echo get_msg('no')?></th>
		<th><?php echo get_msg('location')?></th>
		<th><?php echo get_msg('date')?></th>
		<th><?php echo get_msg('last_change')?></th>
		<th><?php echo get_msg('size')?></th>
		<th><?php echo get_msg('download')?></th>

		<?php if ( $this->ps_auth->has_access( DEL )): ?>
			<th><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
		<?php endif; ?>

	</tr>

	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $logs ) && count( $logs) > 0 ): ?>
			
		<?php foreach($logs  as $log): ?>
			<tr>
				<td><?php echo ++$count;?></td>
				<td><?php echo $log["name"];?></td>
				<td><?php echo $log["date"];?></td>
				<td><?php echo $log["time"];?></td>
				<td><?php echo $log["size"]." Bytes";?></td>
				<td>
					<a href='<?php echo $module_site_url .'/download/'.basename($log["name"], ".log"); ?>'>
						<i class="fa fa-download" aria-hidden="true"></i>
					</a>
				</td>

				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td>
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo basename($log["name"], ".log");?>">
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