<div id="kc_side_box">
<a href="http://www.wisetoweb.com/?src=kc_wtw_logo" id="wtwlogo"><img src="<?php echo plugins_url('/images/logo-wisetoweb.png', __FILE__); ?>" alt="WiseToWeb" /></a>

<?php
// store possible messages in array
$text = array(
'<p>Need help designing the perfect ad? Need assistance writing the HTML or CSS to make the ad look just right?</p>
<p>Contact WiseToWeb. You can hire them to design and build any of the ads you need. WiseToWeb can also create custom plugins, applications, and of course entire websites, custom-designed to meet your needs.</p>
<p>See the <a href="http://www.wisetoweb.com/services/?src=kc_top" target="_blank">full list of services</a> or contact them using the form below.</p>',

'<p>If you\'re not a programmer, can\'t design your own ads, or if you just don\'t have the time, contact WiseToWeb. They can design and build the custom ads you need. Their work is guaranteed too.</p>',

'<p>Need help designing, writing, or coding your ads? Contact WiseToWeb. You can hire them to design and build the perfect ads for your site. Need more advanced services like web design or marketing? They do that too.</p>'
);

// pick a message to show
$show = array_rand($text);
echo $text[$show];
?>

<div id="kc_contact_block">
	<div id="kc_contact_form">
		<h4>Contact WiseToWeb</h4>
		
		<p><label for="kc_contact_name">Name:</label><input type="text" id="kc_contact_name" name="kc_contact_name" value="" /></p>
		<p><label for="kc_contact_email">Email:</label><input type="text" id="kc_contact_email" name="kc_contact_email" value="" /></p>
		
		<ul>
			<li><input type="radio" name="kc_contact_need" value="ads" id="kc_contact_need_ads" checked="checked" /> <label for="kc_contact_need_ads">I need to create an ad</label></li>
			<li><input type="radio" name="kc_contact_need" value="other" id="kc_contact_need_other" /> <label for="kc_contact_need_other">I need other custom services</label></li>
		</ul>
		
		<p>
			<label for="kc_contact_message">Message:</label>
			<textarea id="kc_contact_message" name="kc_contact_message"></textarea>
		</p>
		
		<input type="hidden" name="kc_random_message" id="kc_random_message" value="<?php echo $show; ?>" />
		
		<p><input type="button" id="kc_contact_submit" value="Send" /></p>
	</div>
</div>

<script type="text/javascript">
	(function($){
		$('#kc_contact_submit').click(function(){
			// show "processing" message
			$('#kc_contact_form').css("opacity", 0.25);
			$('#kc_contact_form').after('<div id="kc_form_processing">processing...</div>');
			
			// get form values
			var name = $('#kc_contact_name').val();
			var email = $('#kc_contact_email').val();
			var need = $("input[name='kc_contact_need']:checked").val();
			var message = $('#kc_contact_message').val();
			var rand_msg = $('#kc_random_message').val();
			
			$.ajax({
				url: "<?php echo plugins_url('/form-response.php', __FILE__); ?>",
				data: {name: name, email: email, need: need, message: message, rand_msg: rand_msg},
				success: function(response){
					var response_obj = $.parseJSON(response);
					
					// check for errors
					// remove existing errors
					$('.kc_contact_error').remove();
					if (response_obj.status == 'errors'){
						// remove "processing" message
						$('#kc_contact_form').css("opacity", 1);
						$('#kc_form_processing').remove();
					
						// display errors
						for (var x in response_obj.errors){
							var error_message;
							
							if (response_obj.errors[x] == 'empty'){
								error_message = "Please fill in this field.";
							}else{
								error_message = "There was a problem with what you entered."
							}
							$('#' + x).after('<p class="kc_contact_error">' + error_message + '</p>');
						}
					}else if (response_obj.status == 'ok'){
						// remove "processing" message
						$('#kc_form_processing').remove();
						
						// show "thank you" message
						$('#kc_contact_form').css("opacity", 0.25);
						$('#kc_contact_submit').attr('disabled', true);
						$('#kc_contact_form').after('<div id="kc_form_thanks"><h4>Thank You!</h4><p>You\'ll receive a response within one business day.</p></div>');
					}
				}
			});
		});
	})(jQuery);
</script>

<?php
if ($show > 0){
	?>
See the <a href="http://www.wisetoweb.com/services/?src=kc_bottom" target="_blank">full list of WiseToWeb services</a>.
	<?php
}
?>

</div>