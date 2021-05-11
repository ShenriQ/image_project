<script>

	<?php if ( $this->config->item( 'client_side_validation' ) == true ): ?>
	
	function jqvalidate() {
		$('#team-form').validate({
			rules:{
				team_name:{
					required: true,
					// minlength: 4
				},
				description:{
					required: true,
					// minlength: 4
				},
			},
			messages:{
				team_name:{
					required: "<?php echo get_msg( 'err_field_blank' ); ?>",
					// minlength: "<?php echo get_msg( 'err_user_name_len' ); ?>"
				},
				description:{
					required: "<?php echo get_msg( 'err_field_blank' ); ?>",
					// minlength: "<?php echo get_msg( 'err_user_name_len' ); ?>"
				},
			},
			errorPlacement: function(error, element) {
				console.log( $(error).text());
				if (element.attr("name") == "permissions[]" ) {
					console.log( $(error).text());
					$("#perm_err label").html($(error).text());
					$("#perm_err").show();
				} else {
					error.insertAfter(element);
				}
			}
		});
	}

	<?php endif; ?>

</script>