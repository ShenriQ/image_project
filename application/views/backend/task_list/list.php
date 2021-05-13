<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-striped table-bordered">
	<tr>
		<th><?php echo get_msg('no')?></th>
		<th><?php echo get_msg('name')?></th>
		<th><?php echo get_msg('description')?></th>
		<th><?php echo get_msg('priority')?></th>
		<th><?php echo get_msg('task_status')?></th>
		<th><?php echo get_msg('View Details')?></th>

		<?php if ( $this->ps_auth->has_access( DEL )): ?>
			<th><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
		<?php endif; ?>

	</tr>

	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $tasks ) && count( $tasks->result()) > 0 ): ?>
			
		<?php foreach($tasks->result() as $task): ?>
			
			<tr>
				<td><?php echo ++$count;?></td>
				<td><?php echo $task->name;?></td>
				<td><?php echo $task->description;?></td>
				<td><?php echo $this->Task_priority->get_one($task->priority)->name;?></td>
				<td><?php echo $this->Task_status->get_one($task->status)->name;?></td>
				
				<td>
					<a href='<?php echo $module_site_url .'/edit/'. $task->id; ?>'>
						<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
					</a>
				</td>

				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td>
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo "$task->id";?>">
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