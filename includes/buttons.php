<?php
function cs_get_buttons_markup_arr($permalink,$img,$title){

	$url = esc_url($permalink);
	$img = esc_url($img);
	$title = esc_url($title);

	$options = get_option('coffey_options');
  	$button_size = $options['cs-select-size'];

	if($button_size=="small"){
			$size_class ="cs-small";
			$whatsapp_size_class = "wa_btn_s";
		} elseif($button_size=="medium") {
				$size_class ="cs-medium";
				$whatsapp_size_class = "wa_btn_m";
			} elseif($button_size=="large"){
					$size_class ="cs-large";
					$whatsapp_size_class = "wa_btn_l";
				}

		if(!empty($options['cs-color'])){
			$selected_color = "#".$options['cs-color-select'];
			$color = ($options['cs-color']) ? 'style="color: '.$selected_color.'!important;"' : '';
		} else {
			$color = '';
		}

		$service_markup_arr = array(
									'facebook' => '<div class="s-single-share cs-facebook"><a '.$color.' class="cs-share-link '.$size_class.'" href="http://www.facebook.com/sharer.php?u='.$url.'"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></div>',
									'twitter' => '<div class="s-single-share cs-twitter"><a '.$color.' class="cs-share-link '.$size_class.'" href="https://twitter.com/intent/tweet?url='.$url.'"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></div>',
									'googleplus' => '<div class="s-single-share cs-googleplus"><a '.$color.' class="cs-share-link '.$size_class.'" href="https://plus.google.com/share?url='.$url.'"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a></div>',
									'linkedin' => '<div class="s-single-share cs-linkedin"><a '.$color.' class="cs-share-link '.$size_class.'" href="https://www.linkedin.com/shareArticle?url='.$url.'"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></div>',
									'pinterest' => '<div class="s-single-share cs-pinterest"><a '.$color.' class="cs-share-link '.$size_class.'" href="https://pinterest.com/pin/create/bookmarklet/?media='.$img.'&url='.$url.'"><i class="fa fa-pinterest-square" aria-hidden="true"></i></a></div>',
									'whatsapp' => '<div class="s-single-share cs-whatsapp '.$size_class.'"><a href="whatsapp://send" data-text="Take a look at this awesome website:" data-href="" class="wa_btn '.$whatsapp_size_class.'" style="display:none">Share</a></div>'
								);

	return $service_markup_arr;
}

?>
