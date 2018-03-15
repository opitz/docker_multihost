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
		url: 'login.php',
		data: {username: username, password: password},
		datatype: 'json',
		type: 'post',
		success: function(result) {
//				alert('this worked..?! : ' + data);
			if(result === ''){
				$('#login_msg').html('Login failed!');
			} else {
				admin_mode(1);
				$('#loginbtn').html("Logout " + result);
				$('#login_box').hide();
				console.log('--> logged in.');
				console.log(result);
			}
		},
		error: function(error){
			console.log('Error:');
			console.log(error);
			$('#login_msg').html('Login failed!');
		}
	});
}

//--------------------------------------------------------------------------
function logout (){
	$.ajax({
		url: 'logout.php',
		success: function(result) {
			if(result === ''){
				console.log('--> logout failed?!');
			} else {
				$('.user_box').hide();
				admin_mode(0);
				console.log('--> logging out...');
			}
		}
	});
}

//=========================================================================
$(document).ready(function(){

	if($('#logged_in').length) {
		admin_mode(2);
	}

//--------------------------------------------------------------------------
	$('#login_box').keypress(function(e){
      if(e.keyCode==13)
      $('#login_submit_btn').click();
    });

//--------------------------------------------------------------------------
	$(document).on('click', ".enable_button", function(){
		$("#" + $(this).closest( ".button" ).attr("id")).submit();
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
		}
	});

//--------------------------------------------------------------------------
	$(document).on('click', "#login_submit_btn", function(){
		login($("input[name=username]").val(), $("input[name=password]").val());
	});

//--------------------------------------------------------------------------
	$(document).on('click', "#login_cancel_btn", function(){
		$('#login_msg').html('');
		$('#login_box').hide();
	});

//--------------------------------------------------------------------------
	$(document).on('click',"#editusersbtn", function(){
		$.ajax({
			url: 'get_users.php',
			type: 'get',
			success: function(result) {
				$('#users_box').html('').append(result);
				$('#users_box').show();
			},
			error: function(error){
				console.log('Error:');
				console.log(error);
				$('#login_msg').html('Login failed!');
			}
		});
	});

//--------------------------------------------------------------------------
	$(document).on('click','#cancel_user_btn', function(){
		$('#users_box').hide();
	});


});
