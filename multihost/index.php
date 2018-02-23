<?php
$version = '2.0.4';
$date = '2018-02-22';

include('lib/functions.php');

# A simple routing table
if(array_key_exists('enable',$_POST)) enable_vhost($_POST['vhost']);
if(array_key_exists('disable',$_POST)) disable_vhost($_POST['vhost']);
if(array_key_exists('purge_moodlecache',$_POST)) purge_moodlecache($_POST['vhost']);
if(array_key_exists('reload_apache',$_POST)) reload_apache();
#if(array_key_exists('default',$_POST)) make_default($_POST['vhost']);
?>

<!DOCTYPE html>
<html lang="en-GB">
<head>
	<meta charset="utf-8" />
	<title>Docker MultiHost</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.14/semantic.min.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.14/semantic.min.js"></script>

	<link rel="stylesheet" href="lib/main.css">
</head>

<body>
	<div class="ui grid container">
	  <div class="sixteen wide column">
	  	<p><p>
		<span class="header">Docker <span class="multi"><b>multi</b>host</span></span>
		<hr>	  	
	  </div>
	  <div class="ten wide column">
	  	<?php include('lib/intro.php'); ?>
	  </div>
	  <div class="six wide column">
	  	<?php include('lib/details.php'); ?>
	  </div>
	  <div class="ten wide column">
	  	<?php include('lib/enabled.php'); ?>
	  </div>
	  <div class="six wide column">
	  	<?php include('lib/disabled.php'); ?>
	  </div>
	  <div class="sixteen wide column">
	  	<hr>
	  	<?php include('lib/manage.php'); ?>
	  </div>
	  <div class="eight wide column">
	  	<hr>
	  	<?php include('lib/cli.php'); ?>
	  </div>
	  <div class="eight wide column">
	  	<hr>
	  	<?php include('lib/cli_enable.php'); ?>
	  </div>
	  <div class="sixteen wide column">
		<hr>
		<?php
		echo "<span class='footer'>v.$version | $date</span>";
		?>
	  </div>
	</div>

</body>

<script>
	$(document).ready(function(){
		$(".enable_button").click(function(){
			$("#" + $(this).closest( ".button" ).attr("id")).submit();
		});

		$(".server_button").click(function(){
//			alert('test ' + $(this).closest( ".button" ).val());
			window.open($(this).closest( ".button" ).val());
			return false;
		});
	});
</script>
