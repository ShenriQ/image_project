<div class="table-responsive animated fadeInRight">
	<table class="table m-0 table-striped table-bordered">
	<tr>
		<th><?php echo get_msg('sample_id')?></th>
		<th><?php echo get_msg('inspection_time')?></th>
		<th><?php echo get_msg('sample_index')?></th>
		<th><?php echo get_msg('operator_check')?></th>
		<th><?php echo get_msg('operator_ip')?></th>
		<th><?php echo get_msg('operator')?></th>
		<th><?php echo get_msg('View Details')?></th>

		<?php if ( $this->ps_auth->has_access( DEL )): ?>
			<th><span class="th-title"><?php echo get_msg('btn_delete')?></span></th>
		<?php endif; ?>

	</tr>

	<?php $count = $this->uri->segment(4) or $count = 0; ?>

	<?php if ( !empty( $samples ) && count( $samples->result()) > 0 ): ?>
			
		<?php foreach($samples->result() as $sample): ?>
			
			<tr>
				<!-- <td><?php echo ++$count;?></td> -->
				<td><?php echo $sample->id;?></td>
				<td><?php echo $sample->inspection_time;?></td>
				<td><?php echo $sample->sample_index;?></td>
				<td><?php echo $sample->operator_check;?></td>
				<td><?php echo $sample->operator_ip;?></td>
				<td><?php echo $this->User->get_one($sample->operator_id)->user_name;?></td>
				<td>
					<a href='<?php echo $module_site_url .'/edit/'. $sample->id; ?>'>
						<i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>
					</a>
				</td>

				<?php if ( $this->ps_auth->has_access( DEL )): ?>
					
					<td>
						<a herf='#' class='btn-delete' data-toggle="modal" data-target="#myModal" id="<?php echo "$sample->id";?>">
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