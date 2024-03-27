<?php
//convert the seconds to Days, Hours, Minutes and Seconds
function seconds_to_time($seconds) {
	$s = str_pad($seconds%60, 2, '0', STR_PAD_LEFT);
	$m = str_pad(floor(($seconds%3600)/60), 2, '0', STR_PAD_LEFT);
	$h = str_pad(floor(($seconds%86400)/3600), 2, '0', STR_PAD_LEFT);
	$d = str_pad(floor(($seconds%2592000)/86400), 2, '0', STR_PAD_LEFT);

	return $d."日".$h."時間".$m."分".$s."秒";
}

//Function to check if the articles along with selected post type is empty
function alert_validation ($post_type) {
	$post_type_alert = array();
	$countdown_repeater = get_field('countdwn_timer', 'option');
	if(is_array($countdown_repeater)) :
	  foreach($countdown_repeater as $countrow) :
	    //fetch all ac fields
	    $relation = $countrow['rltship'];
	    $expired_date = $countrow['tmr_grp']['exp_dte'];
		$display_date = $countrow['tmr_grp']['dsply_strtime'];
	    $start_time	= ($display_date == 1) ? $countrow['tmr_grp']['strt_dte'] : '<b>&#8212;</b>';
	    $currents_time = current_time( 'timestamp' );
	    $started_date	= (!empty($start_time)) ? strtotime($start_time) : '';
	    $expired_dates	= strtotime($expired_date);
	    $expired_date	= date_i18n( 'Y-m-d H:i:s', $expired_dates );
	    $current_date	= date_i18n( 'Y-m-d H:i:s', $currents_time );
	    $interval = $countrow['tmr_grp']['rmndr_slct'];
			$side_banner = $countrow['sdbnner_grp']['select_sideBanner'];
	    //Removal of text on string
	    $timestamp = ($interval['value'] > 0 && $interval['value'] < 6) ? trim($interval['label'], "Before") : '0 day';
	    //created value for +interval on current minute/hour/day
	    $plus_time = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',current_time("timestamp")).'+'.$timestamp));
	    //Created condition for displaying the status of countdown
	    if ($plus_time >= $expired_date && $current_date <= $expired_date && $interval['value'] != 0) {
	      //Created array for post type
	      $article_types = array(
	        'article' => "Article List",
	        'slider' => "Slider Banner",
	        'attachment' => "Side Banner",
	      );
	      $alert_time = ($interval['value'] == 6 ) ? $interval['label'] : trim($interval['label'], "Before") ;
        $alert_layout = '<h5>Due  in '.$alert_time.'</h5>';
					if (is_array($article_types)) {
            foreach ($article_types as $key => $value) {
							if (is_array($relation)) {
                foreach ($relation as $post) {
                  if ($key == $post_type &&  $post->post_type == $post_type) {
                    $post_type_alert[] = $post->post_title;
                  }
                }
	            }
						$post_type_alert[] = ($key == 'arrachment' && !empty($side_banner)) ? array('0','1') : '' ;
	        }
				}
	    }
	    endforeach;
	  endif;

	return count($post_type_alert);
}

//Fetch the sidebanner image
function sidebanner_image($unique_id, $data) {
	//Banner Repeater Field
	$top_banners = get_field('top_banner_rptr', 'option');
	//Above Category list
	if (is_array($top_banners)) {
	  foreach ($top_banners as $banners) {
	    //Banner Image
	    $banner_group = $banners['banner_grp'];
	    $banner_image = (!empty($banner_group['banner_image_mg'])) ? $banner_group['banner_image_mg'] : '';
			if ($banner_image['ID'] == $unique_id && $data == 'url') {
				return $banner_image['url'];
			} else if ($banner_image['ID'] == $unique_id && $data == 'title') {
				return $banner_image['title'];
			}
		}
	}
}


//Fetch the output for the notification
function email_layout() {
	$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	// Test if string contains the word wp-admin
	$admin_site = (strpos($url, 'wp-admin') !== false) ? 'admin_footer' : 'wp_footer';
	$countdown_repeater = get_field('countdwn_timer', 'option');
	$alert_layout = '';
	if(is_array($countdown_repeater)) :
		foreach($countdown_repeater as $countrow) :
			//fetch all ac fields
			$relation = $countrow['rltship'];
			$expired_date = $countrow['tmr_grp']['exp_dte'];
			$display_date = $countrow['tmr_grp']['dsply_strtime'];
			$start_time	= ($display_date == 1) ? $countrow['tmr_grp']['strt_dte'] : '<b>&#8212;</b>';
			$currents_time = current_time( 'timestamp' );
			$started_date	= (!empty($start_time)) ? strtotime($start_time) : '';
			$expired_dates	= strtotime($expired_date);
			$expired_date	= date_i18n( 'Y-m-d H:i:s', $expired_dates );
			$current_date	= date_i18n( 'Y-m-d H:i:s', $currents_time );
			$interval = $countrow['tmr_grp']['rmndr_slct'];
			$side_banner = $countrow['sdbnner_grp']['select_sideBanner'];

			//Removal of text on string
			$timestamp = ($interval['value'] > 0 && $interval['value'] < 6) ? trim($interval['label'], "Before") : '0 day';

			//created value for +interval on current minute/hour/day
			$plus_time = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',current_time("timestamp")).'+'.$timestamp));

			//Created condition for displaying the status of countdown
			if ($plus_time >= $expired_date && $current_date <= $expired_date && $interval['value'] != 0) {
				//Created array for post type
				$article_types = array(
					'article' => "Article List",
					'slider' => "Slider Banner",
					'attachment' => "Side Banner",
				);
				$alert_time = '';
				if ($interval['value'] == 1 ) {
					$alert_time = 'Due  in 9 Minutes.';
				} else if ($interval['value'] == 2 ) {
					$alert_time = 'Due  in 29 Minutes.';
				} else if ($interval['value'] == 3 ) {
					$alert_time = 'Due  in 59 Minutes.';
				} else if ($interval['value'] == 4 ) {
					$alert_time = 'Due  in 1 Hour 59 Minutes.';
				} else if ($interval['value'] == 5 ) {
					$alert_time = 'Due  in 23 Hours 59 Minutes.';
				} else {
					$alert_time = 'The countdown has expired!';
				}

				$server_host = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['SERVER_NAME'] : $_SERVER['SERVER_NAME'].'/tmj-times-tsr';
				$alert_layout = '<!DOCTYPE html><html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="x-apple-disable-message-reformatting"><title></title><!--[if mso]><noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript><![endif]--><style>
					.content-container .content-logo {margin-top: 20px;text-align: center;}.alert-message {width: 70%;margin: auto}.alert-message li, .image-banner {padding: 10px;list-style: none;} .alert-message li a, .image-banner a {text-decoration: none;} .alert-message ul {margin: 0; padding: 0; background-color: #f4f5f7;}.alert-message h5 {margin: 0;padding: 20px 20px;font-size: 15px;background-color: #f4f5f7;}.post-type li, .image-banner {margin: 10px 0;font-size: 13px;background-color: #fff;}.list-title {margin-top: 0;font-weight: 600;font-size: 14px;}
				</style></head><body><div class="content-container"><div class="content-logo"> <img src="https://surveymonkey-assets.s3.amazonaws.com/survey/186418666/ef386416-eb7e-40b6-af3a-2122708b0dc0.png" width="150"></div><div class="alert-message"><p> The list below shows that these article/side banner/slider image countdown timers are due to set reminders and to know more information about a particular article/image. Thank you very much. </p>';
					$alert_layout.= '<h5>'.$alert_time.'</h5>';
					foreach ($article_types as $key => $value) {
						if (is_array($relation) && alert_validation($key)) {
							$alert_layout.='<ul>';
								$alert_layout.='<li class="post-type"><p class="list-title '.$key.'-list">'.$value.'</p>';
									$alert_layout.= ($key == 'slider') ? "<p>We can't display the image itself and to check the more information about the slider image, click the name to redirect on the admin dashboard.</p>" : '';
									$alert_layout.= ($key == 'attachment') ? "<p>We can't display the image itself and to check the more information about the sidebar banner, click the name to redirect on the admin dashboard.</p>" : '';
										foreach ($relation as $post) {
											$alert_layout.='<ul>';
											if ($key == 'article' && $post->post_type == 'article') {
												$alert_layout.='<li class="post-title"><a href="'.$server_host.'/wp-admin/admin.php?page=wmd-countdown-menu-main&ct=1&due_reminder='.$post->ID.'">'.$post->post_title.'</a></li>';
											}
											if ($key == 'slider' && $post->post_type == 'slider') {
												$alert_layout.='<li class="post-title"><a href="'.$server_host.'/wp-admin/admin.php?page=wmd-countdown-menu-main&ct=1&due_reminder='.$post->ID.'">'.$post->post_title.'</a></li>';
											}
											$alert_layout.='</ul>';
										}
										$alert_layout.= ($key == 'attachment') ? '<p class="image-banner"> <a href="'.$server_host.'/wp-admin/admin.php?page=wmd-countdown-menu-main&ct=1&due_reminder='.$side_banner.'">'.sidebanner_image($side_banner, 'title').'</a> </p>' : '';
									}
									$alert_layout.='</li>';
								}
								$alert_layout.='</ul></div></div></body></html>';
							}
							endforeach;
							endif;
							return $alert_layout;
					}

 ?>
