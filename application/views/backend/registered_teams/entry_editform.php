<?php
$attributes = array('id' => 'team-form' );

echo form_open(  site_url('/admin/registered_teams/edit/' . @$team->id), $attributes);
?>
<section class="content animated fadeInRight">
    <div class="card card-info">
        <ul class="nav nav-tabs" id="myTab">

            <?php
	    		$active_tab_teaminfo="";
	    		if($current_tab == "teaminfo") {
	    			$active_tab_teaminfo = "active";
	    		}
	    		
	    		if($current_tab == "members") {
	    			$active_tab_members = "active";
	    		}

	    		if($current_tab == "tasks") {
	    			$active_tab_tasks = "active";
	    		}

	    		if($active_tab_teaminfo == "" && $active_tab_members == "" &&  $active_tab_tasks == "") {

	    			$active_tab_teaminfo = "active";
	    		} 

	    	?>

            <li class="nav-item"><a class="nav-link <?php echo $active_tab_teaminfo;?>" href="#teaminfo"
                    value="teaminfo" data-toggle="tab">Team information</a></li>
            <li class="nav-item"><a class="nav-link <?php echo $active_tab_members;?>" href="#members" value="members"
                    data-toggle="tab">Team members</a></li>
            <li class="nav-item"><a class="nav-link <?php echo $active_tab_tasks;?>" href="#tasks" value="tasks"
                    data-toggle="tab">Team tasks</a></li>
        </ul>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane <?php echo $active_tab_teaminfo;?>" id="teaminfo">
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
                    <div class="card-footer">
                        <button type="submit" name="save" class="btn btn-primary">
                            <?php echo get_msg('btn_save')?>
                        </button>

                        <a href="<?php echo site_url('admin/registered_teams');?>"
                            class="btn btn-primary"><?php echo get_msg('btn_cancel'); ?></a>

                    </div>
                </div>
                <div class="tab-pane <?php echo $active_tab_members;?>" id="members">
                    <form> </form>
                    <?php 
						$search_form = $template_path .'/'. $module_path .'/search_form_members';
						$team_data = array(
							'team' => @$team
						);
						if ( is_view_exists( $search_form )) $this->load->view( $search_form , $team_data); 
					?>

                    <?php 
						$member_listview = $template_path .'/'. $module_path .'/member_list';
						$members_data = array(
							'users' => @$members,
							'team' => @$team
						);
						if ( is_view_exists( $member_listview )) $this->load->view( $member_listview, $members_data ); 
					?>

                </div>

                <div class="tab-pane <?php echo $active_tab_tasks;?>" id="tasks">

                </div>
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- /.card-body -->

    </div>
    <!-- card info -->

    <input type="hidden" id="current_tab" name="current_tab" value="current_tab">

</section>
<?php echo form_close(); ?>

<?php 
	// replace cover photo modal
	$data = array(
		'title' => get_msg('upload_photo'),
		'img_type' => 'shop',
		'img_parent_id' => @$shop->id
	);
	
	$this->load->view( $template_path .'/components/shop_photo_upload_modal', $data );

	// replace cover photo modal
	$data = array(
		'title' => get_msg('upload_photo'),
		'img_type' => 'shop-icon',
		'img_parent_id' => @$shop->id
	);
	
	$this->load->view( $template_path .'/components/icon_upload_modal', $data );
	
	// delete cover photo modal
	$this->load->view( $template_path .'/components/delete_cover_photo_modal' ); 

	// Delete Confirm Message Modal
	$data = array(
		'title' => get_msg( 'delete_a_member' ),
		'message' =>  get_msg( 'are_you_sure_to_delete_this_member_from_team' ),
	);
	
	$this->load->view( $template_path .'/components/delete_confirm_modal', $data );
?>

<div class="modal fade" id="addMember">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
				$attributes = array('id' => 'add-member-form');
				echo form_open( site_url('/admin/registered_teams/addmember/'.@$team->id), $attributes);
			?>
            <div class="modal-header">
                <h4 class="modal-title"><?php echo get_msg('add_new_member')?></h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body" style="text-align: center; padding-top : 20px; padding-bottom: 20px;">
                <p><?php echo get_msg('select user to add to team as a member')?></p>
				<select id="user_list_select_for_team_member" name="member_user" style="width: 250px;">
                <?php foreach($idle_users as $user): ?>
                    <option value="<?php echo $user->user_id; ?>" data-select2-id="<?php echo $user->user_id; ?>">
                        <?php echo $user->user_name; ?></option>
                <?php endforeach; ?>
				</select>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-default btn-add-member">Yes</a>
                <a type="button" class="btn btn-default" data-dismiss="modal">Cancel</a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>


<script>
$('.delete-img').click(function(e) {
    e.preventDefault();
    // get id and image
    var id = $(this).attr('id');
    // do action
    var action = '<?php echo $module_site_url .'/delete_cover_photo/'; ?>' + id + '/<?php echo @$shop->id; ?>';
    console.log(action);
    $('.btn-delete-image').attr('href', action);
});

$('.btn-add-member').click(function(e) {
    e.preventDefault();
    if ($("#user_list_select_for_team_member").val() == "") {
        return
    }
    $('#add-member-form').submit();
});

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    var target = $(e.target).attr("value") // activated tab
    $('#current_tab').val(target);
});

function matchStart(params, data) {
    // If there are no search terms, return all of the data
    if ($.trim(params.term) === '') {
        return data;
    }

    // Do not display the item if there is no 'text' property
    if (typeof data.text === 'undefined') {
        return null;
    }

    // `params.term` should be the term that is used for searching
    // `data.text` is the text that is displayed for the data object
    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
        var modifiedData = $.extend({}, data, true);
        modifiedData.text += ' (matched)';

        // You can return modified objects from here
        // This includes matching the `children` how you want in nested data sets
        return modifiedData;
    }

    // Return `null` if the term should not be displayed
    return null;
}
</script>