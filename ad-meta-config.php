<div id="kc_ad_scope_selector" class="kc_ad_settings_panel">
	<h4>Ad Scope</h4>
	<ul>
		<li><input type="radio" name="kc_ad_scope" value="global" id="kc_ad_scope_global"<?php if ($kc_ad_scope == 'global' || empty($kc_ad_scope)){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_scope_global">Global (ad displays everywhere on the site)</label></li>
		<li><input type="radio" name="kc_ad_scope" value="front_page" id="kc_ad_scope_front_page"<?php if ($kc_ad_scope == 'front_page'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_scope_front_page">Front Page (ad shows only on your Front Page; see Settings > Reading)</label></li>
		<li><input type="radio" name="kc_ad_scope" value="home_page" id="kc_ad_scope_home_page"<?php if ($kc_ad_scope == 'home_page'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_scope_home_page">Posts Page (ad shows only on your Posts Page; see Settings > Reading)</label></li>
		<li><input type="radio" name="kc_ad_scope" value="all_pages" id="kc_ad_scope_all_pages"<?php if ($kc_ad_scope == 'all_pages'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_scope_all_pages">All Pages (ad shows only on Pages)</label></li>
		<li><input type="radio" name="kc_ad_scope" value="all_posts" id="kc_ad_scope_all_posts"<?php if ($kc_ad_scope == 'all_posts'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_scope_all_posts">All Posts (ad shows only on Posts)</label></li>
		<li><input type="radio" name="kc_ad_scope" value="specific" id="kc_ad_scope_specific"<?php if ($kc_ad_scope == 'specific'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_scope_specific">Select specific Pages and Posts</label></li>
	</ul>
	
	<div id="kc_page_post_selection">
		<div id="kc_page_post_tabs">
			<p class="message">Drag items to the box on the right <span>&raquo;</span></p>
			
			<ul class="tabs">
				<li><a href="#kc_list_pages">Pages</a></li>
				<li><a href="#kc_list_posts">Posts</a></li>
			</ul>
			
			<ul id="kc_list_pages" class="kc_draggable_items">
				<?php
				global $wpdb;
				
				$query = "select * from $wpdb->posts where post_type = 'page'";
				$pages = $wpdb->get_results($query, OBJECT);
				if ($pages){
					global $post;
					
					foreach ($pages as $post){
						setup_postdata($post);
						
						// check if ID is in the list of those already selected, don't display if it is.
						//
						//
						echo '<li class="kc_list_page kc_list_item" id="'.get_the_ID().'">'.get_the_title().'<span title="Add to Selected Set" class="ui-icon ui-icon-select-item">+</span></li>';
					}
				}
				wp_reset_query();
				?>
			</ul>
			
			<ul id="kc_list_posts" class="kc_draggable_items">
				<?php
				global $wpdb;
				
				$query = "select * from $wpdb->posts where post_type = 'post'";
				$posts = $wpdb->get_results($query, OBJECT);
				if ($posts){
					global $post;
					
					foreach ($posts as $post){
						setup_postdata($post);
						
						// check if ID is in the list of those already selected, don't display if it is.
						//
						//
						echo '<li class="kc_list_post kc_list_item" id="'.get_the_ID().'">'.get_the_title().'<span title="Add to Selected Set" class="ui-icon ui-icon-select-item">+</span></li>';
					}
				}
				wp_reset_query();
				?>
			</ul>
		</div>
		
		<div id="kc_selected_container">
			<h4>Show Ad On:</h4>
			<div id="kc_selected">
				
			</div>
			
			<input type="hidden" name="kc_ad_selected_list" id="kc_ad_selected_list" value="<?php if (!empty($kc_ad_selected_list)){ echo $kc_ad_selected_list; } ?>" />
		</div>
	</div>
	
	<script type="text/javascript">
		// set up tabs and drag/drop functionality
		(function($){
			// initialize tabs
			$(function(){
				$('#kc_page_post_tabs').tabs();
			});
			
			// initialize drag/drop
			$(function(){
				var $kc_list_pages = $('#kc_list_pages');
				var $kc_list_posts = $('#kc_list_posts');
				var $kc_selected = $('#kc_selected');
		
				// let the pages items be draggable
				$( "li", $kc_list_pages ).draggable({
					cancel: "a.ui-icon", // clicking an icon won't initiate dragging
					revert: "invalid", // when not dropped, the item will revert back to its initial position
					containment: $( "#kc_page_post_selection" ).length ? "#kc_page_post_selection" : "document", // stick to kc_page_post_selection if present
					helper: "clone",
					cursor: "move"
				});
				
				// let the posts items be draggable
				$( "li", $kc_list_posts ).draggable({
					cancel: "a.ui-icon", // clicking an icon won't initiate dragging
					revert: "invalid", // when not dropped, the item will revert back to its initial position
					containment: $( "#kc_page_post_selection" ).length ? "#kc_page_post_selection" : "document", // stick to kc_page_post_selection if present
					helper: "clone",
					cursor: "move"
				});
		
				// let the selected area be droppable, accepting the page or post items
				$kc_selected.droppable({
					accept: ".kc_draggable_items > li",
					activeClass: "ui-state-highlight",
					drop: function(event, ui){
						selectItem(ui.draggable, true);
					}
				});
		
				// let the pages list be droppable as well, accepting items from selected ones
				$kc_list_pages.droppable({
					accept: "#kc_selected li",
					activeClass: "custom-state-active",
					drop: function(event, ui){
						returnItem(ui.draggable);
					}
				});
				
				// let the posts list be droppable as well, accepting items from selected ones
				$kc_list_posts.droppable({
					accept: "#kc_selected li",
					activeClass: "custom-state-active",
					drop: function(event, ui){
						returnItem(ui.draggable);
					}
				});
				
				// item selection function
				var return_icon = '<span title="Remove from Selected Set" class="ui-icon ui-icon-return-item">X</span>'; // define removal icon
				function selectItem($item, $add_to_list){
					$item.fadeOut(function(){
						var $list = $("ul", $kc_selected).length ?
							$("ul", $kc_selected) :
							$("<ul class='kc_list_selected'/>").appendTo($kc_selected);
						
						$item.find("span.ui-icon-select-item").remove();
						$item.append(return_icon).appendTo($list).fadeIn();
						
						if ($add_to_list === true){
							// add item to list
							$('#kc_ad_selected_list').val($('#kc_ad_selected_list').val() + ',' + $item.attr('id'));
							
							// eliminate initial comma, if present
							if ($('#kc_ad_selected_list').val().indexOf(",") == 0){
								$('#kc_ad_selected_list').val($('#kc_ad_selected_list').val().replace(",", ""));
							}
						}
					});
				}
		
				// item return function
				var select_icon = '<span title="Add to Selected Set" class="ui-icon ui-icon-select-item">+</span>'; // define addition icon
				function returnItem($item){
					$item.fadeOut(function(){
						$item.find("span.ui-icon-return-item").remove();
						$item.append(select_icon);
						
						if ($item.hasClass('kc_list_page')){
							$item.appendTo($kc_list_pages).fadeIn();
						} else if ($item.hasClass('kc_list_post')){
							$item.appendTo($kc_list_posts).fadeIn();
						}
						
						// remove item from list
						if ($('#kc_ad_selected_list').val().indexOf($item.attr('id')) == 0){
							$('#kc_ad_selected_list').val($('#kc_ad_selected_list').val().replace($item.attr('id'), ""));
						}else{
							$('#kc_ad_selected_list').val($('#kc_ad_selected_list').val().replace("," + $item.attr('id'), ""));
						}
						
						// eliminate initial comma, if present
						if ($('#kc_ad_selected_list').val().indexOf(",") == 0){
							$('#kc_ad_selected_list').val($('#kc_ad_selected_list').val().replace(",", ""));
						}
					});
				}
				
				// resolve the icons behavior with event delegation
				$('.kc_list_item').click(function(event){
					var $item = $(this);
					var $target = $(event.target);
		
					if ($target.is("span.ui-icon-select-item")){
						selectItem($item, true);
					} else if ($target.is("span.ui-icon-return-item")){
						returnItem($item);
					}
		
					return false;
				});
				
				// select any that were in the saved list
				if ($('#kc_ad_selected_list').val() != ''){
					var saved_post_ids = $('#kc_ad_selected_list').val().split(',');
					
					for (var id in saved_post_ids){
						var item_to_select = $('.kc_list_item[id="' + saved_post_ids[id] + '"]');
						selectItem(item_to_select, false);
					}
				}
			});
			
			// on page load: deactivate page/post selection unless that option is selected
			<?php
			if ($kc_ad_scope == 'specific'){
				echo '$(\'#kc_page_post_selection\').show();';
			}else{
				echo '$(\'#kc_page_post_selection\').hide();';
			}
			?>
			
			// show or hide the page/post selection when the option is changed
			$('input[name="kc_ad_scope"]').change(function(){
				if ($(this).val() == 'specific'){
					$('#kc_page_post_selection').slideDown();
				}else{
					$('#kc_page_post_selection').slideUp();
				}
			});
		})(jQuery);
	</script>
</div>

<div id="kc_ad_position_settings" class="kc_ad_settings_panel">
	<h4>Screen Position</h4>
	
	<div id="kc_ad_position_options_box">
		<ul id="kc_ad_screen_location">
			<li id="kc_ad_screen_location_top_button"><input type="radio" name="kc_ad_screen_location" value="top" id="kc_ad_screen_location_top"<?php if ($kc_ad_screen_location == 'top'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_screen_location_top">Top</label></li>
			<li id="kc_ad_screen_location_right_button"><input type="radio" name="kc_ad_screen_location" value="right" id="kc_ad_screen_location_right"<?php if ($kc_ad_screen_location == 'right'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_screen_location_right">Right</label></li>
			<li id="kc_ad_screen_location_bottom_button"><input type="radio" name="kc_ad_screen_location" value="bottom" id="kc_ad_screen_location_bottom"<?php if ($kc_ad_screen_location == 'bottom' || empty($kc_ad_screen_location)){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_screen_location_bottom">Bottom</label></li>
			<li id="kc_ad_screen_location_left_button"><input type="radio" name="kc_ad_screen_location" value="left" id="kc_ad_screen_location_left"<?php if ($kc_ad_screen_location == 'left'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_screen_location_left">Left</label></li>
		</ul>
		
		<div id="kc_ad_horiz_position">
			<h5>Top or Bottom Horizontal Position</h5>
			<input type="text" name="kc_ad_tbhorizpos_val" value="<?php if ($kc_ad_tbhorizpos_val != ''){ echo $kc_ad_tbhorizpos_val; }else{ echo '50'; } ?>" size="10" id="kc_ad_tbhorizpos_val" />
			<select name="kc_ad_tbhorizpos_type" id="kc_ad_tbhorizpos_type">
				<option value="percent"<?php if($kc_ad_tbhorizpos_type == 'percent'){ echo ' selected="selected"'; } ?>>Percent</option>
				<option value="pixels"<?php if($kc_ad_tbhorizpos_type == 'pixels'){ echo ' selected="selected"'; } ?>>Pixels</option>
			</select>
			
			<br />From
			<select name="kc_ad_tbhorizpos_ref" id="kc_ad_tbhorizpos_ref">
				<option value="left"<?php if($kc_ad_tbhorizpos_ref == 'left'){ echo ' selected="selected"'; } ?>>Left</option>
				<option value="right"<?php if($kc_ad_tbhorizpos_ref == 'right'){ echo ' selected="selected"'; } ?>>Right</option>
			</select>
			
				<p id="kc_ad_spacer">
					<input type="checkbox" name="kc_ad_include_spacer" value="true" id="kc_ad_include_spacer"<?php if ($kc_ad_include_spacer == 'true'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_include_spacer">Include Spacer</label><br />
					<em>Adds a spacer to the page to compensate<br />for the height of the ad.</em>
				</p>
		</div>
		
		<div id="kc_ad_vert_position">
			<h5>Left or Right Vertical Position</h5>
			<input type="text" name="kc_ad_lrvertpos_val" value="<?php if ($kc_ad_lrvertpos_val != ''){ echo $kc_ad_lrvertpos_val; }else{ echo '50'; } ?>" size="10" id="kc_ad_lrvertpos_val" />
			<select name="kc_ad_lrvertpos_type" id="kc_ad_lrvertpos_type">
				<option value="percent"<?php if($kc_ad_lrvertpos_type == 'percent'){ echo ' selected="selected"'; } ?>>Percent</option>
				<option value="pixels"<?php if($kc_ad_lrvertpos_type == 'pixels'){ echo ' selected="selected"'; } ?>>Pixels</option>
			</select>
			
			<br />From
			<select name="kc_ad_lrvertpos_ref" id="kc_ad_lrvertpos_ref">
				<option value="top"<?php if($kc_ad_lrvertpos_ref == 'top'){ echo ' selected="selected"'; } ?>>Top</option>
				<option value="bottom"<?php if($kc_ad_lrvertpos_ref == 'bottom'){ echo ' selected="selected"'; } ?>>Bottom</option>
			</select>
		</div>
		
		<script type="text/javascript">
			// grey out positioning settings when not needed
			(function($){
				// on page load: grey out vertical positioning
				<?php
				if ($kc_ad_screen_location == 'right' || $kc_ad_screen_location == 'left'){
					echo '$(\'#kc_ad_vert_position\').css(\'opacity\', 1);'."\n".'$(\'#kc_ad_vert_position\').children().removeAttr(\'disabled\');';
				}else{
					echo '$(\'#kc_ad_vert_position\').css(\'opacity\', 0.5);'."\n".'$(\'#kc_ad_vert_position\').children().attr(\'disabled\', true);';
				}
				?>
				
				// show or hide vertical positioning when the option is changed
				$('input[name="kc_ad_screen_location"]').change(function(){
					if ($(this).val() == 'right' || $(this).val() == 'left'){
						$('#kc_ad_vert_position').css('opacity', 1);
						$('#kc_ad_vert_position').children().removeAttr('disabled');
					}else{
						$('#kc_ad_vert_position').css('opacity', 0.5);
						$('#kc_ad_vert_position').children().attr('disabled', true);
					}
				});
				
				// on page load: grey out horizontal positioning
				<?php
				if ($kc_ad_screen_location == 'top' || $kc_ad_screen_location == 'bottom' || empty($kc_ad_screen_location)){
					echo '$(\'#kc_ad_horiz_position\').css(\'opacity\', 1);'."\n".'$(\'#kc_ad_horiz_position\').children().removeAttr(\'disabled\');';
				}else{
					echo '$(\'#kc_ad_horiz_position\').css(\'opacity\', 0.5);'."\n".'$(\'#kc_ad_horiz_position\').children().attr(\'disabled\', true);';
				}
				?>
				
				// show or hide horizontal positioning when the option is changed
				$('input[name="kc_ad_screen_location"]').change(function(){
					if ($(this).val() == 'top' || $(this).val() == 'bottom'){
						$('#kc_ad_horiz_position').css('opacity', 1);
						$('#kc_ad_horiz_position').children().removeAttr('disabled');
					}else{
						$('#kc_ad_horiz_position').css('opacity', 0.5);
						$('#kc_ad_horiz_position').children().attr('disabled', true);
					}
				});
			})(jQuery);
		</script>
		
	</div>
</div>

<div id="kc_ad_display_settings" class="kc_ad_settings_panel">
	<h4>Ad Display Options</h4>
	
	<p>
		<input type="radio" name="kc_ad_appear_type" value="timed" id="kc_ad_appear_type_timed"<?php if ($kc_ad_appear_type == 'timed' || empty($kc_ad_appear_type)){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_appear_type_timed">Appear after</label>
		<input type="text" name="kc_ad_appear_delay" value="<?php if (!empty($kc_ad_appear_delay)){ echo $kc_ad_appear_delay; }else{ echo '0'; } ?>" size="10" id="kc_ad_appear_delay" /> seconds
	</p>
	
	<p>
		<input type="radio" name="kc_ad_appear_type" value="scroll" id="kc_ad_appear_type_scroll"<?php if ($kc_ad_appear_type == 'scroll'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_appear_type_scroll">Appear after scrolling</label>
		<input type="text" name="kc_ad_appear_position" value="<?php if (!empty($kc_ad_appear_position)){ echo $kc_ad_appear_position; }else{ echo '0'; } ?>" size="10" id="kc_ad_appear_position" /> pixels
	</p>
	
	<p>
		<input type="checkbox" name="kc_ad_user_close" value="true" id="kc_ad_user_close"<?php if ($kc_ad_user_close == 'true'){ echo ' checked="checked"'; } ?> /> <label for="kc_ad_user_close">Don't display again after user closes</label>
	</p>
		Show ad <input type="text" name="kc_ad_display_count" value="<?php if (!empty($kc_ad_display_count)){ echo $kc_ad_display_count; }else{ echo '0'; } ?>" size="10" id="kc_ad_display_count" /> times<br />
		<em>Set to 0 (zero) to display unlimited times.</em>
	<p>
		
	</p>
</div>

<div id="kittycatfish_thanks">
<a href="http://www.missilesilo.com/kittycatfish" target="_blank"><img src="<?php echo plugins_url('/images/thanks.png', __FILE__); ?>" alt="Thanks for using KittyCatfish by Missilesilo" /></a>
</div>