<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-striped table-bordered">
	<tr>
		<th><?php echo get_msg('no')?></th>
		<th><?php echo get_msg('location')?></th>
		<th><?php echo get_msg('date')?></th>
		<th><?php echo get_msg('size')?></th>
		<th><?php echo get_msg('download')?></th>

		<?php if ( $this->ps_auth->has_access( DEL )): ?>
			<th><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
		<?php endif; ?>

	</tr>

	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $backups ) && count( $backups->result()) > 0 ): ?>
			
		<?php foreach($backups->result() as $backup): ?>
			
			<tr>
				<td><?php echo ++$count;?></td>
				<td><?php echo $backup->path;?></td>
				<td><?php echo $backup->created_at;?></td>
				<td><?php echo $backup->size." Bytes";?></td>
				<td>
					<a href='<?php echo $module_site_url .'/download/'. $backup->id; ?>'>
						<i class="fa fa-download" aria-hidden="true"></i>
					</a>
				</td>

				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td>
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo "$backup->id";?>">
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