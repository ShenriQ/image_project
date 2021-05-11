<?php
	$attributes = array('id' => 'team-form');
	echo form_open( '', $attributes );
?>
<section class="content animated fadeInRight">

	<div class="card card-info">
	    <div class="card-header">
	      <h3 class="card-title"><?php echo get_msg('Team Info')?></h3>
	    </div>

  		<div class="card-body">
    		<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label><?php echo get_msg('name'); ?></label>
						<?php echo form_input( array(
							'name' => 'team_name',
							'value' => set_value( 'team_name', show_data( @$team->name ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'name' ),
							'id' => 'team_name'
						)); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label><?php echo get_msg('description'); ?></label>
						<?php echo form_input( array(
							'name' => 'description',
							'value' => set_value( 'description', show_data( @$team->description ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'description' ),
							'id' => 'description'
						)); ?>
					</div>
				</div>
			</div>
		</div>
		 <!-- /.card-body -->

		<div class="card-footer">
            <button type="submit" class="btn btn-sm btn-primary">
				<?php echo get_msg('btn_save')?>
			</button>

			<a href="<?php echo $module_site_url; ?>" class="btn btn-sm btn-primary">
				<?php echo get_msg('btn_cancel')?>
			</a>
        </div>
	</div>
</section>

<?php echo form_close(); ?>