<?php
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
// Test if string contains the word wp-admin
$admin_site = (strpos($url, 'wp-admin') !== false) ? 'admin_footer' : 'wp_footer';
$countdown_repeater = get_field('countdwn_timer', 'option');
if(is_user_logged_in() && is_array($countdown_repeater)) :
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
    // if ($plus_time == $expired_date && $interval['value'] != 0) {
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
      $alert_layout = '<h5>'.$alert_time.'</h5>';
        foreach ($article_types as $key => $value) {
          if (is_array($relation) && alert_validation($key)) {
            $alert_layout.='<ul>';
              $alert_layout.='<li class="post-type"><p class="list-title '.$key.'-list">'.$value.'</p>';
              $alert_layout.='<ul>';
                foreach ($relation as $post) {
                  if ($key == 'article' && $post->post_type == 'article') {
                    $alert_layout.='<li class="post-title">&#8212; '.$post->post_title.'</li>';
                  }
                  if ($key == 'slider' && $post->post_type == 'slider') {
                    $alert_layout.='<img class="slider-alert" src='.get_field('slider_image', $post->ID)['url'].'> ';
                  }
                }
              $alert_layout.= ($key == 'attachment') ? '<img class="side-alert" src='.sidebanner_image($side_banner, 'url').'>' : '';
              $alert_layout.='</ul></li>';
            $alert_layout.='</ul>';
          }
        }

        //Set the expired date to time stamp
        if ($interval['value'] != 0) :
          $expired_timestamp = DateTime::createFromFormat( 'Y-m-d H:i:s', $expired_date, new DateTimeZone('UTC'));
          $minus_time = ($expired_timestamp === false) ? '' : date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',$expired_timestamp->getTimestamp()).'-'.$timestamp)) ;
          add_action($admin_site, function() use($minus_time, $alert_layout) { ?>
           <script type="text/javascript">
           function alert_data() {
             var current_date = new Date()
             hours = current_date.getHours( );
             hours = hours ? hours : 12;
             hours = hours.toString().length == 1 ? 0+hours.toString() : hours;

             var minutes = current_date.getMinutes().toString()
             minutes = minutes.length == 1 ? 0+minutes : minutes;

             var seconds = current_date.getSeconds().toString()
             seconds = seconds.length == 1 ? 0+seconds : seconds;

             var month = (current_date.getMonth() +1).toString();
             month = month.length == 1 ? 0+month : month;

             var date = current_date.getDate().toString();
             date = date.length == 1 ? 0+date : date;

             var current_time = current_date.getFullYear() + "-" + month + "-" + date;
             current_time = current_time +" "+  hours + ":" +  minutes + ":" +  seconds;
             if (current_time == '<?=$minus_time;?>') {
               setTimeout(function() {
                 countdown_notification('<?=$alert_layout;?>');
               }, 1000);

           	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
           		jQuery.ajax({
           			 type : "post",
           			 dataType : "json",
           			 url : ajaxurl,
           			 data : {action: "email_notifications"},
           			 success: function(response) {
           				 jQuery('body').append(response);
           			 }
           		});
             }
             alert_output();
           }
           alert_output();
           </script>
           <?php
         });?>
        <?php
      endif;
    }
    endforeach;
  endif;


  //Ajax function for the email notifcation
  add_action( 'wp_ajax_email_notifications', 'email_notifications' );
  add_action( 'wp_ajax_nopriv_email_notifications', 'email_notifications' );

  function email_notifications() {
    //List of Administrator Email Address
    $admin_users = get_users('role=Administrator');
    foreach (get_users('role=Administrator') as $user) {
      $admin_emails[] = $user->user_email;
    }

    //test email notif
    $admin_emails = array (
      'elizabeth@tmjpbpo.com',
      'antonio@tmjpbpo.com',
      'jeffrey@tmjpbpo.com',
      'ryancodizal@tmjpbpo.com'
    );
    $admin_emails = 'ryancodizal@tmjpbpo.com';

    //Email subject
    $subject = 'TMJP CDT Snapshot: List of due soon';

    //Email sent to and header
    // $header = " From : ".get_bloginfo()." <danteedwards@tmjpbpo.com> \r\n\r\n";
    // $header = " From : ".get_bloginfo()." <ryancodizal@tmjpbpo.com> \r\n\r\n";
    $header = array(
      'Content-Type: text/html; charset=UTF-8',
      'From : '.get_bloginfo().' <danteedwards@tmjpbpo.com>'
    );
    //Email body content
    $message = ''.email_layout().'';

    // wordpress function for email notification
    return wp_mail( $admin_emails, $subject, $message, $header );
  }
  ?>
