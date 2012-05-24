<?php
// build and output KittyCatfish JavaScript based on ad settings

header('Content-type: text/javascript');

include('../../../wp-config.php');

$kc_ad = $_GET['kc_ad'];
$kc_ad_meta = kittycatfish_ad_get_meta($kc_ad);
?>

/*
 * KittyCatfish Actions
 */

(function($){
	$(document).ready(function(){
		// get the dimensions of the entire catfish ad
		var kittycatfish_height = $('#kittycatfish').height();
		var kittycatfish_width = $('#kittycatfish').width();
		<?php
		// insert spacer if position is top or bottom and if the option is selected
		if ($kc_ad_meta['kc_ad_screen_location'] == 'top' && $kc_ad_meta['kc_ad_include_spacer'] == 'true'){
			?>$('body').prepend('<div id="kittycatfish_spacer"></div>');<?php
		}else if ($kc_ad_meta['kc_ad_screen_location'] == 'bottom' && $kc_ad_meta['kc_ad_include_spacer'] == 'true'){
			?>$('body').append('<div id="kittycatfish_spacer"></div>');<?php
		}
		?>
		
		
		// auto-open the ad
		<?php
		switch ($kc_ad_meta['kc_ad_screen_location']){
			case 'top':
				// check display method
				if ($kc_ad_meta['kc_ad_appear_type'] == 'scroll'){
					?>var displayed = false;
					
					// first test if we can scroll enough
					if ( ($(document).height() - $(window).height()) > <?php echo $kc_ad_meta['kc_ad_appear_position']; ?> ){
						$(window).scroll(function(){
							if ($(this).scrollTop() >= <?php echo $kc_ad_meta['kc_ad_appear_position']; ?> && displayed == false){
								$('#kittycatfish').css('top', '-'+kittycatfish_height+'px').show().animate({top: '+='+kittycatfish_height}, 400, 'swing');
								if ($('#kittycatfish_spacer').length > 0){
									$('#kittycatfish_spacer').animate({height: '+='+kittycatfish_height}, 400, 'swing');
								}
								
								displayed = true;
							}
						});
					}else{
						// can't scroll enough, just display it automatically
						$('#kittycatfish').css('top', '-'+kittycatfish_height+'px').show().animate({top: '+='+kittycatfish_height}, 400, 'swing');
						if ($('#kittycatfish_spacer').length > 0){
							$('#kittycatfish_spacer').animate({height: '+='+kittycatfish_height}, 400, 'swing');
						}
						
						displayed = true;
					}<?php
				}else if ($kc_ad_meta['kc_ad_appear_type'] == 'trigger'){
					?>var displayed = false;
					
					// get position of trigger
					if ($('#kittycatfish_trigger').length){
						var offset = $('#kittycatfish_trigger').offset();
						var trigger = offset.top - ($(window).height()/2);
						
						$(window).scroll(function(){
							if ($(this).scrollTop() >= trigger && displayed == false){
								displayed = true;
								
								$('#kittycatfish').css('top', '-'+kittycatfish_height+'px').show().animate({top: '+='+kittycatfish_height}, 400, 'swing');
								if ($('#kittycatfish_spacer').length > 0){
									$('#kittycatfish_spacer').animate({height: '+='+kittycatfish_height}, 400, 'swing');
								}
							}
						});
					}<?php
				}else{
					?>$('#kittycatfish').css('top', '-'+kittycatfish_height+'px').show().delay(<?php echo $kc_ad_meta['kc_ad_appear_delay']*1000; ?>).animate({top: '+='+kittycatfish_height}, 400, 'swing');
					if ($('#kittycatfish_spacer').length > 0){
						$('#kittycatfish_spacer').delay(<?php echo $kc_ad_meta['kc_ad_appear_delay']*1000; ?>).animate({height: '+='+kittycatfish_height}, 400, 'swing');
					}<?php
				}
				break;
				
			case 'right':
				// check display method
				if ($kc_ad_meta['kc_ad_appear_type'] == 'scroll'){
					?>var displayed = false;
					
					// first test if we can scroll enough
					if ( ($(document).height() - $(window).height()) > <?php echo $kc_ad_meta['kc_ad_appear_position']; ?> ){
						$(window).scroll(function(){
							if ($(this).scrollTop() >= <?php echo $kc_ad_meta['kc_ad_appear_position']; ?> && displayed == false){
								$('#kittycatfish').css('right', '-'+kittycatfish_width+'px').show().animate({right: '+='+kittycatfish_width}, 400, 'swing');
								
								displayed = true;
							}
						});
					}else{
						// can't scroll enough, just display it automatically
						$('#kittycatfish').css('right', '-'+kittycatfish_width+'px').show().animate({right: '+='+kittycatfish_width}, 400, 'swing');
						
						displayed = true;
					}<?php
				}else if ($kc_ad_meta['kc_ad_appear_type'] == 'trigger'){
					?>var displayed = false;
					
					// get position of trigger
					if ($('#kittycatfish_trigger').length){
						var offset = $('#kittycatfish_trigger').offset();
						var trigger = offset.top - ($(window).height()/2);
						
						$(window).scroll(function(){
							if ($(this).scrollTop() >= trigger && displayed == false){
								displayed = true;
								
								$('#kittycatfish').css('right', '-'+kittycatfish_width+'px').show().animate({right: '+='+kittycatfish_width}, 400, 'swing');
							}
						});
					}<?php
				}else{
					?>$('#kittycatfish').css('right', '-'+kittycatfish_width+'px').show().delay(<?php echo $kc_ad_meta['kc_ad_appear_delay']*1000; ?>).animate({right: '+='+kittycatfish_width}, 400, 'swing');<?php
				}
				break;
				
			case 'left':
				// check display method
				if ($kc_ad_meta['kc_ad_appear_type'] == 'scroll'){
					?>var displayed = false;
					
					// first test if we can scroll enough
					if ( ($(document).height() - $(window).height()) > <?php echo $kc_ad_meta['kc_ad_appear_position']; ?> ){
						$(window).scroll(function(){
							if ($(this).scrollTop() >= <?php echo $kc_ad_meta['kc_ad_appear_position']; ?> && displayed == false){
								$('#kittycatfish').css('left', '-'+kittycatfish_width+'px').show().animate({left: '+='+kittycatfish_width}, 400, 'swing');
								
								displayed = true;
							}
						});
					}else{
						// can't scroll enough, just display it automatically
						$('#kittycatfish').css('left', '-'+kittycatfish_width+'px').show().animate({left: '+='+kittycatfish_width}, 400, 'swing');
						
						displayed = true;
					}<?php
				}else if ($kc_ad_meta['kc_ad_appear_type'] == 'trigger'){
					?>var displayed = false;
					
					// get position of trigger
					if ($('#kittycatfish_trigger').length){
						var offset = $('#kittycatfish_trigger').offset();
						var trigger = offset.top - ($(window).height()/2);
						
						$(window).scroll(function(){
							if ($(this).scrollTop() >= trigger && displayed == false){
								displayed = true;
								
								$('#kittycatfish').css('left', '-'+kittycatfish_width+'px').show().animate({left: '+='+kittycatfish_width}, 400, 'swing');
							}
						});
					}<?php
				}else{
					?>$('#kittycatfish').css('left', '-'+kittycatfish_width+'px').show().delay(<?php echo $kc_ad_meta['kc_ad_appear_delay']*1000; ?>).animate({left: '+='+kittycatfish_width}, 400, 'swing');<?php
				}
				break;
			
			case 'bottom':
			default:
				// check display method
				if ($kc_ad_meta['kc_ad_appear_type'] == 'scroll'){
					?>var displayed = false;
					
					// first test if we can scroll enough
					if ( ($(document).height() - $(window).height()) > <?php echo $kc_ad_meta['kc_ad_appear_position']; ?> ){
						$(window).scroll(function(){
							if ($(this).scrollTop() >= <?php echo $kc_ad_meta['kc_ad_appear_position']; ?> && displayed == false){
								$('#kittycatfish').css('bottom', '-'+kittycatfish_height+'px').show().animate({bottom: '+='+kittycatfish_height}, 400, 'swing');
								if ($('#kittycatfish_spacer').length > 0){
									$('#kittycatfish_spacer').animate({height: '+='+kittycatfish_height}, 400, 'swing');
								}
								
								displayed = true;
							}
						});
					}else{
						// can't scroll enough, just display it automatically
						$('#kittycatfish').css('bottom', '-'+kittycatfish_height+'px').show().animate({bottom: '+='+kittycatfish_height}, 400, 'swing');
						if ($('#kittycatfish_spacer').length > 0){
							$('#kittycatfish_spacer').animate({height: '+='+kittycatfish_height}, 400, 'swing');
						}
						
						displayed = true;
					}<?php
				}else if ($kc_ad_meta['kc_ad_appear_type'] == 'trigger'){
					?>var displayed = false;
					
					// get position of trigger
					if ($('#kittycatfish_trigger').length){
						var offset = $('#kittycatfish_trigger').offset();
						var trigger = offset.top - ($(window).height()/2);
						
						$(window).scroll(function(){
							if ($(this).scrollTop() >= trigger && displayed == false){
								displayed = true;
								
								$('#kittycatfish').css('bottom', '-'+kittycatfish_height+'px').show().animate({bottom: '+='+kittycatfish_height}, 400, 'swing');
								if ($('#kittycatfish_spacer').length > 0){
									$('#kittycatfish_spacer').animate({height: '+='+kittycatfish_height}, 400, 'swing');
								}
							}
						});
					}<?php
				}else{
					?>$('#kittycatfish').css('bottom', '-'+kittycatfish_height+'px').show().delay(<?php echo $kc_ad_meta['kc_ad_appear_delay']*1000; ?>).animate({bottom: '+='+kittycatfish_height}, 400, 'swing');
					if ($('#kittycatfish_spacer').length > 0){
						$('#kittycatfish_spacer').delay(<?php echo $kc_ad_meta['kc_ad_appear_delay']*1000; ?>).animate({height: '+='+kittycatfish_height}, 400, 'swing');
					}<?php
				}
				break;
		}
		?>
		
		// increment the counter cookie
		if ($.cookie('kittycatfish_count') != null){
			// if cookie already exists, load value
			var kittycatfish_count = $.cookie('kittycatfish_count');
			
			// get the counter for the ad we're showing
			var show_counter;
			
			var ad_counters = kittycatfish_count.split(",");
			var found = false;
			for (var i in ad_counters){
				var counter = ad_counters[i].split(":");
				if (counter[0] == "<?php echo $kc_ad; ?>"){
					show_counter = parseInt(counter[1]);
					
					// increment
					show_counter++;
					
					// save
					ad_counters[i] = "<?php echo $kc_ad; ?>:" + show_counter.toString();
					found = true;
				}
			}
			
			// see if we found the counter or if we still need to add it
			if (!found){
				// add new counter to set
				ad_counters.push("<?php echo $kc_ad; ?>:1");
			}
			
			// convert to string and save cookie
			kittycatfish_count = ad_counters.toString();
			
			$.cookie('kittycatfish_count', kittycatfish_count);
		}else{
			// not set yet; set to first time
			var kittycatfish_count = "<?php echo $kc_ad; ?>:1";
			$.cookie('kittycatfish_count', kittycatfish_count);
		}
		
		// close the ad
		$('#kittycatfish #close').click(function(){
			// hide and remove the ad
			<?php
			switch ($kc_ad_meta['kc_ad_screen_location']){
				case 'top':
					?>$('#kittycatfish').animate({top: '-='+kittycatfish_height}, 400, 'swing', function(){
						// save the fact that the user has closed it
						if ($.cookie('kittycatfish_closed') != null){
							// if cookie already exists, load it
							var kittycatfish_closed = $.cookie('kittycatfish_closed');
							
							// add this ad to the list of ads that were closed
							var closed_ads = kittycatfish_closed.split(",");
							closed_ads.push("<?php echo $kc_ad; ?>");
							kittycatfish_closed = closed_ads.toString();
							
							// set the new cookie value
							$.cookie('kittycatfish_closed', kittycatfish_closed);
						}else{
							// cookie doesn't exist yet, set it
							$.cookie('kittycatfish_closed', '<?php echo $kc_ad; ?>');
						}
						// remove ad from document
						$('#kittycatfish').remove();
					});
					if ($('#kittycatfish_spacer').length > 0){
						// adjust spacer height to zero
						$('#kittycatfish_spacer').animate({height: '-='+kittycatfish_height}, 400, 'swing', function(){
							// remove spacer from document
							$('#kittycatfish_spacer').remove();
						});
					}<?php
					break;
					
				case 'right':
					?>$('#kittycatfish').animate({right: '-='+kittycatfish_width}, 400, 'swing', function(){
						// save the fact that the user has closed it
						if ($.cookie('kittycatfish_closed') != null){
							// if cookie already exists, load it
							var kittycatfish_closed = $.cookie('kittycatfish_closed');
							
							// add this ad to the list of ads that were closed
							var closed_ads = kittycatfish_closed.split(",");
							closed_ads.push("<?php echo $kc_ad; ?>");
							kittycatfish_closed = closed_ads.toString();
							
							// set the new cookie value
							$.cookie('kittycatfish_closed', kittycatfish_closed);
						}else{
							// cookie doesn't exist yet, set it
							$.cookie('kittycatfish_closed', '<?php echo $kc_ad; ?>');
						}
						// remove ad from document
						$('#kittycatfish').remove();
					});<?php
					break;
					
				case 'left':
					?>$('#kittycatfish').animate({left: '-='+kittycatfish_width}, 400, 'swing', function(){
						// save the fact that the user has closed it
						if ($.cookie('kittycatfish_closed') != null){
							// if cookie already exists, load it
							var kittycatfish_closed = $.cookie('kittycatfish_closed');
							
							// add this ad to the list of ads that were closed
							var closed_ads = kittycatfish_closed.split(",");
							closed_ads.push("<?php echo $kc_ad; ?>");
							kittycatfish_closed = closed_ads.toString();
							
							// set the new cookie value
							$.cookie('kittycatfish_closed', kittycatfish_closed);
						}else{
							// cookie doesn't exist yet, set it
							$.cookie('kittycatfish_closed', '<?php echo $kc_ad; ?>');
						}
						// remove ad from document
						$('#kittycatfish').remove();
					});<?php
					break;
				
				case 'bottom':
				default:
					?>$('#kittycatfish').animate({bottom: '-='+kittycatfish_height}, 400, 'swing', function(){
						// save the fact that the user has closed it
						if ($.cookie('kittycatfish_closed') != null){
							// if cookie already exists, load it
							var kittycatfish_closed = $.cookie('kittycatfish_closed');
							
							// add this ad to the list of ads that were closed
							var closed_ads = kittycatfish_closed.split(",");
							closed_ads.push("<?php echo $kc_ad; ?>");
							kittycatfish_closed = closed_ads.toString();
							
							// set the new cookie value
							$.cookie('kittycatfish_closed', kittycatfish_closed);
						}else{
							// cookie doesn't exist yet, set it
							$.cookie('kittycatfish_closed', '<?php echo $kc_ad; ?>');
						}
						// remove ad from document
						$('#kittycatfish').remove();
					});
					// adjust spacer height to zero
					if ($('#kittycatfish_spacer').length > 0){
						$('#kittycatfish_spacer').animate({height: '-='+kittycatfish_height}, 400, 'swing', function(){
							// remove spacer from document
							$('#kittycatfish_spacer').remove();
						});
					}<?php
					break;
			}
			?>
		});
	});
})(jQuery);