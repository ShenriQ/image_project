<?php
	$attributes = array('id' => 'sample-form');
	echo form_open( '', $attributes );
?>
<section class="content animated fadeInRight">

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><?php echo get_msg('Sample Info')?></h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo get_msg('sample_index')?></label>
                        <?php echo form_input( array(
                            'type' => 'number',
 							'name' => 'sample_index',
							'value' => set_value( 'sample_index', show_data( @$sample->sample_index ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'sample_index' ),
							'id' => 'sample_index',
                            'style' => 'margin-top: 8px;'
						)); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo get_msg('inspection_time')?></label>
                        <div class="input-group" style="padding-top: 5px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            <?php echo form_input(array(
                                'type' => 'datetime-local',
                                'name' => 'inspection_time',
                                'value' => set_value( 'inspection_time' , $sample->inspection_time ),
                                'class' => 'form-control',
                                'placeholder' => '',
                                // 'id' => 'date_one_picker',
                                'size' => '20',
                                // 'readonly' => 'readonly'
                            )); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> 
                            <?php echo get_msg('num_of_imgs_each_col')?>
                        </label>
                        <?php echo form_input( array(
                            'type' => 'number',
 							'name' => 'num_of_imgs_each_col',
							'value' => set_value( 'num_of_imgs_each_col', show_data( @$sample->num_of_imgs_each_col ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'num_of_imgs_each_col' ),
							'id' => 'num_of_imgs_each_col',
                            'style' => 'margin-top: 8px;'
						)); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            <?php echo get_msg('num_of_imgs_each_row')?>
                        </label>
                        <?php echo form_input( array(
                            'type' => 'number',
 							'name' => 'num_of_imgs_each_row',
							'value' => set_value( 'num_of_imgs_each_row', show_data( @$sample->num_of_imgs_each_row ), false ),
							'class' => 'form-control form-control-sm',
							'placeholder' => get_msg( 'num_of_imgs_each_row' ),
							'id' => 'num_of_imgs_each_row',
                            'style' => 'margin-top: 8px;'
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