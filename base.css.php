<?php
// build and output KittyCatfish CSS based on ad settings

header('Content-type: text/css');

include('../../../wp-config.php');

$kc_ad = $_GET['kc_ad'];
$kc_ad_meta = kittycatfish_ad_get_meta($kc_ad);
?>

/* KittyCatfish Base Styles */

#kittycatfish {
	position: fixed;
	display: none;
	margin: 0;
	padding: 0;
	z-index: 999;
	border: none;
	<?php
	// determine positioning
	switch ($kc_ad_meta['kc_ad_screen_location']){
		case 'top':
			echo "top: 0px;\n";
			echo "\t".$kc_ad_meta['kc_ad_tbhorizpos_ref'].": ".$kc_ad_meta['kc_ad_tbhorizpos_val'];
			
			// check unit type
			if ($kc_ad_meta['kc_ad_tbhorizpos_type'] == 'percent'){
				echo "%;\n";
			}else{
				echo "px;\n";
			}
			break;
			
		case 'right':
			echo "right: 0px;\n";
			echo "\t".$kc_ad_meta['kc_ad_lrvertpos_ref'].": ".$kc_ad_meta['kc_ad_lrvertpos_val'];
			
			// check unit type
			if ($kc_ad_meta['kc_ad_lrvertpos_type'] == 'percent'){
				echo "%;\n";
			}else{
				echo "px;\n";
			}
			break;
			
		case 'left':
			echo "left: 0px;\n";
			echo "\t".$kc_ad_meta['kc_ad_lrvertpos_ref'].": ".$kc_ad_meta['kc_ad_lrvertpos_val'];
			
			// check unit type
			if ($kc_ad_meta['kc_ad_lrvertpos_type'] == 'percent'){
				echo "%;\n";
			}else{
				echo "px;\n";
			}
			break;
		
		case 'bottom':
		default:
			echo "bottom: 0px;\n";
			echo "\t".$kc_ad_meta['kc_ad_tbhorizpos_ref'].": ".$kc_ad_meta['kc_ad_tbhorizpos_val'];
			
			// check unit type
			if ($kc_ad_meta['kc_ad_tbhorizpos_type'] == 'percent'){
				echo "%;\n";
			}else{
				echo "px;\n";
			}
			break;
	}
	?>
	width: auto;
}

#kittycatfish_spacer {
	margin: 0;
	padding: 0;
	height: 0px;
}

#kittycatfish_ad_content {
	position: relative;
}

#kittycatfish_ad_content #close {
	position: absolute;
	margin: 0;
	padding: 0;
	line-height: normal;
	top: 5px;
	right: 7px;
	z-index: 9999;
	cursor: pointer;
}

<?php
// include custom ad css
if ($kc_ad_meta['kc_ad_css'] != ''){
?>
/* Custom Ad CSS */

<?php
	echo $kc_ad_meta['kc_ad_css'];
}
?>