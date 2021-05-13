<?php
	$attributes = array('id' => 'task-form');
	echo form_open( '', $attributes );
?>
<section class="content animated fadeInRight">

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><?php echo get_msg('Task Info')?></h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><span style="font-size: 17px; color: red;">*</span><?php echo get_msg('name'); ?></label>
                        <?php echo form_input( array(
							'name' => 'task_name',
							'value' => set_value( 'task_name', show_data( @$task->name ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'name' ),
							'id' => 'task_name'
						)); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><span style="font-size: 17px; color: red;">*</span><?php echo get_msg('description'); ?></label>
                        <?php echo form_input( array(
							'name' => 'description',
							'value' => set_value( 'description', show_data( @$task->description ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'description' ),
							'id' => 'description'
						)); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> <span style="font-size: 17px; color: red;">*</span>
                            <?php echo get_msg('priority')?>
                        </label>

                        <?php
							$options=array();
							$pritorities = $this->Task_priority->get_all();
							foreach($pritorities->result() as $priority) {
									$options[$priority->id]=$priority->name;
							}

							echo form_dropdown(
								'priority',
								$options,
								set_value( 'priority', show_data( @$task->priority), false ),
								'class="form-control form-control-sm mr-3" id="priority"'
							);
						?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> <span style="font-size: 17px; color: red;">*</span>
                            <?php echo get_msg('task_status')?>
                        </label>

                        <?php
							$options=array();
							$statuses = $this->Task_status->get_all( );
							foreach($statuses->result() as $status) {
									$options[$status->id]=$status->name;
							}

							echo form_dropdown(
								'status',
								$options,
								set_value( 'status', show_data( @$task->status), false ),
								'class="form-control form-control-sm mr-3" id="status"'
							);
						?>
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