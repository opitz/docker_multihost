//--------------------------------------------------------------------------
function admin_mode(status){
	if(status === 0){
		$('.disable_button').fadeOut(1000)
		$('#disabled_section').slideUp(1000)
		$('#manage_section').slideUp(1000)
		$('#cli_section').slideUp(1000)
		$('#cli_enable_section').slideUp(1000)
		$('#loginbtn').addClass('orange');
		$('#loginbtn').html("Login");
		$('#editusersbtn').hide();
	} else if(status === 1) {
		$('.disable_button').fadeIn(1000)
		$('#disabled_section').slideDown(1000)
		$('#manage_section').slideDown(1000)
		$('#cli_section').slideDown(1000)
		$('#cli_enable_section').slideDown(1000)
		$('#loginbtn').removeClass('orange');
		$('#loginbtn').html("Logout");
		$('#editusersbtn').show();
	} else {
		$('.disable_button').show()
		$('#disabled_section').show()
		$('#manage_section').show()
		$('#cli_section').show()
		$('#cli_enable_section').show()
		$('#loginbtn').removeClass('orange');
		$('#loginbtn').html("Logout " + $('#logged_in').html());
		$('#editusersbtn').show();
	}
}

//--------------------------------------------------------------------------
function login (username, password){
	$.ajax({
		url: 'lib/ajax_user.php',
		data: {action: 'login', username: username, password: password},
		success: function(result) {
			if(result === ''){
				$('#login_msg').html('Login failed!');
			} else {
				admin_mode(1);
				$('#loginbtn').html("Logout " + result);
				$('#login_box').hide();
				console.log('--> logging in user ' + result);
			}
		},
		error: function(error){
			console.log('Error:' + error);
			$('#login_msg').html('Login failed!');
		}
	});
}

//--------------------------------------------------------------------------
function logout (){
	$.ajax({
		url: 'lib/ajax_user.php',
		data: {action: 'logout'},
		success: function(result) {
			if(result === ''){
				console.log('--> logout failed?! wtf...');
			} else {
				user = $('#logged_in').html();
				$('.user_box').hide();
				admin_mode(0);
				console.log('--> logging out user ' + user);
			}
		}
	});
}

//--------------------------------------------------------------------------
function append_html(file, section){
	$.ajax({
		url: file,
		success: function(result) {
			$(section).html('').append(result);
		}
	});
}

//--------------------------------------------------------------------------
function load_static_content(){
	append_html('lib/intro.php', '#intro_section');
	append_html('lib/details.php', '#details_section');
	append_html('lib/manage.php', '#manage_section');
	append_html('lib/cli.php', '#cli_section');
	append_html('lib/cli_enable.php', '#cli_enable_section');
}

var focused = true;

function onBlur() {
	focused = false;	
}

function onFocus() {
	focused = true;
}

//--------------------------------------------------------------------------
function reload_vhosts(){
	if(focused === true){
		$.ajax({
			url: 'lib/enabled.php',
			success: function(result) {
				$('#enabled_section').html('').append(result);
				$.ajax({
					url: 'lib/disabled.php',
					success: function(result) {
						$('#disabled_section').html('').append(result);
						if($('#logged_in').length) {
							admin_mode(2);
						}
					}
				});
			}
		});
	}
}
//--------------------------------------------------------------------------
function enable_vhost(vhost){
	$.ajax({
		url: 'lib/ajax_vhosts.php',
		data: {action: 'enable_vhost', vhost: vhost},
		success: function(result) {
			reload_vhosts();
		}
	});
}

//--------------------------------------------------------------------------
function disable_vhost(vhost){
	$.ajax({
		url: 'lib/ajax_vhosts.php',
		data: {action: 'disable_vhost', vhost: vhost},
		success: function(result) {
			reload_vhosts();
		}
	});
}

//--------------------------------------------------------------------------
function purge_moodlecache(vhost){
	$.ajax({
		url: 'lib/ajax_vhosts.php',
		data: {action: 'purge_moodlecache', vhost: vhost},
		success: function(result) {
			console.log(result);
			reload_vhosts();
		}
	});
}

//=========================================================================
$(document).ready(function(){

	load_static_content();
	reload_vhosts();

	var myVar = setInterval(reload_vhosts, 30000); // reload the vhosts part every 30 seconds to reflect cache changes

//--------------------------------------------------------------------------
	$(document).on('keydown', '.user_box', function(e){
      if(e.keyCode==13) {
      	$('#login_submit_btn').click();
      	$('#save_add_user_btn').click();
      	$('#save_edit_user_btn').click();
      }
    });

//--------------------------------------------------------------------------
	$(document).on('keydown', document, function(e){
      if(e.keyCode==27) {
      	$('.cancel_button').click();	
      }
    });

//--------------------------------------------------------------------------
	$(document).on('click', ".enable_button", function(){
		enable_vhost($(this).attr('id'));
	});

//--------------------------------------------------------------------------
	$(document).on('click', ".disable_button", function(){
		disable_vhost($(this).attr('id'));
	});

//--------------------------------------------------------------------------
	$(document).on('click', ".cache_button", function(){
		purge_moodlecache($(this).attr('id'));
	});

//--------------------------------------------------------------------------
	$(document).on('click', ".server_button", function(){
		window.open($(this).closest( ".button" ).val());
		return false;
	});

//--------------------------------------------------------------------------
	$(document).on('click', "#loginbtn", function(){
		if($(".admin:visible").length > 0) {
			logout();
		} else {
			$('#login_msg').html('');
			$('#login_box').show();
			$("input[name=username]").focus();
		}
	});

//--------------------------------------------------------------------------
	$(document).on('click', "#login_submit_btn", function(){
		login($("input[name=username]").val(), $("input[name=password]").val());
	});

//--------------------------------------------------------------------------
	$(document).on('click',"#editusersbtn", function(){
		$.ajax({
			url: 'lib/ajax_user.php',
			data: {action: 'get_user_list'},
			type: 'get',
			success: function(result) {
				$('#users_box').html('').append(result);
				$('#users_box').show();
			},
			error: function(error){
				console.log('Error:' + error);
				$('#login_msg').html('Login failed!');
			}
		});
	});

//--------------------------------------------------------------------------
	$(document).on('click',"#add_user_btn",function(){
		$.ajax({
			url: 'lib/ajax_user.php',
			data: {action: 'add_user_form'},
			type: 'get',
			success: function(result) {
				console.log(result);
				$('#user_add_box').html('').append(result);
				$('#user_add_box').show();
				$("input[name=new_username]").focus();
				$('#users_box').hide();
			},
			error: function(error){
				console.log('Error:' + error);
			}
		});
	});

//--------------------------------------------------------------------------
	$(document).on('click','#save_add_user_btn', function(){
		new_username = $('input[name="new_username"]').val();
		new_password = $('input[name="new_password"]').val();
		confirm_password = $('input[name="confirm_password"]').val();
		$.ajax({
			url: 'lib/ajax_user.php',
			data: {action: 'validate_user', username: new_username, password: new_password, confirm_password: confirm_password},
			type: 'get',
			success: function(result) {
				if(result === 'ok') {
					$.ajax({
						url: 'lib/ajax_user.php',
						data: {action: 'add_user', username: new_username, password: new_password},
						type: 'get',
						success: function(result) {
							console.log('--> added user ' + result);
						},
						error: function(error){
							console.log('Error:' + error);
						}
					});
					$('#user_add_msg').html('');
					$('#user_add_box').hide();
				} else {
					console.log('==> ' + result);
					$('#user_add_msg').html(result);
				}
			},
			error: function(error){
				console.log('Error:' + error);
			}
		});
	});

//--------------------------------------------------------------------------
	$(document).on('click',".edit_user_btn", function(){
		user_id = $(this).attr('id');
		username = $('#user_'+user_id).html();
		$.ajax({
			url: 'lib/ajax_user.php',
			data: {action: 'edit_user_form', username: username},
			type: 'get',
			success: function(result) {
				$('#user_edit_box').html('').append(result);
				$('#user_edit_box').show();
				$("input[name=new_password]").focus();
				$('#users_box').hide();
			},
			error: function(error){
				console.log('Error:' + error);
				$('#user_edit_msg').html('Saving user data failed!<br>ERROR: ' + error);
			}
		});
	});

//--------------------------------------------------------------------------
	$(document).on('click','#save_edit_user_btn', function(){
		username = $('#username').html();
		new_password = $('input[name="new_password"]').val();
		confirm_password = $('input[name="confirm_password"]').val();
		$.ajax({
			url: 'lib/ajax_user.php',
			data: {action: 'validate_password', password: new_password, confirm_password: confirm_password},
			type: 'get',
			success: function(result) {
				if(result === 'ok') {
					$.ajax({
						url: 'lib/ajax_user.php',
						data: {action: 'save_user', username: username, password: new_password},
						type: 'get',
						success: function(result) {
							console.log('success: ' + result);
						},
						error: function(error){
							console.log('Error:' + error);
						}
					});
					$('#user_edit_msg').html('');
					$('#user_edit_box').hide();
				} else {
					console.log('==> ' + result);
					$('#user_edit_msg').html(result);
				}

			},
			error: function(error){
				console.log('Error:' + error);
			}
		});
	});

//--------------------------------------------------------------------------
	$(document).on('click',".delete_user_btn",function(){
		user_id = ($(this).attr('id')-1000);
		username = $('#user_'+user_id).html();
		if(username === 'admin')
			alert("User 'admin' cannot be deleted!");
		else	
			if(confirm('Do you really want to delete user ' + username + '?')) {
				$.ajax({
					url: 'lib/ajax_user.php',
					data: {action: 'delete_user', username: username},
					type: 'get',
					success: function(result) {
				$('#users_box').hide();
						console.log(result);
						console.log('User ' + username + ' has been deleted');
					},
					error: function(error){
						console.log('Error:' + error);
					}
				});
			}
	});

//--------------------------------------------------------------------------
	$(document).on('click', "#cancel_login_btn", function(){
		$('#login_msg').html('');
		$('#login_box').hide();
	});

//--------------------------------------------------------------------------
	$(document).on('click','#cancel_user_btn', function(){
		$('#users_box').hide();
	});

//--------------------------------------------------------------------------
	$(document).on('click','#cancel_add_user_btn', function(){
		$('#user_add_msg').html('');
		$('#user_add_box').hide();
	});

//--------------------------------------------------------------------------
	$(document).on('click','#cancel_edit_user_btn', function(){
		$('#user_edit_msg').html('');
		$('#user_edit_box').hide();
	});


});
