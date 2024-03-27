<?php
function dynamic_widgets_dashboard(){
  wp_add_dashboard_widget(
    'widget_notification',
    'TMJ CDT Notification',
    'widget_notification'
  );
}
function widget_notification(){
  ?>
  <style>
     .article,
     .slider-banner,
     .side-banner{
         border: 1px solid #0000002b;
         padding: 10px;
         box-shadow: 4px 4px #88888873;
         margin: 10px;
     }
     .article h5,
     .side-banner h5,
     .slider-banner h5{
         margin: 0;
         padding: 1px 15px;
         border-radius: 9px;
         font-weight: 700;
     }
     .article h5{
         background-color: #99979761;
         width: 60px;
     }
     .side-banner h5{
         background-color: #f0f33b61;
         width: 65px;
         margin-bottom: 10px;
     }
     .slider-banner h5{
         background-color: #51dbf187;
         width: 75px;
     }
     .article img,
     .slider-banner img,
     .side-banner img{
         width: 50%;
         height: auto;
     }

     .see-more-btn a{
      text-decoration: none;
     }
  </style>
 <?php
    $countdwn_timer = get_field('countdwn_timer','option');
    if(is_array($countdwn_timer)){
        foreach($countdwn_timer as $row_count){
            $timer_grp = $row_count['tmr_grp'];
            $expired_date = $timer_grp['exp_dte'];
            $rel = $row_count['rltship'];
            $sidebanner_grp = $row_count['sdbnner_grp'];
            $select_sidebanner = $sidebanner_grp['select_sideBanner'];
            $sidebanner_img = sidebanner_image($select_sidebanner,'url');
            $stop_date = date('Y-m-d H:i:s', strtotime($expired_date . ' -1 day'));
            // get today
            date_default_timezone_set('Asia/Tokyo');
            $today = date('Y-m-d H:i:s', time());
            // get the hours and minute
            $hours_and_mins = date('H:i', strtotime($today));
            // minus one hour 
            $minus_one_hour = strtotime($hours_and_mins. ' -1 hour');
            $time = date('H:i', $minus_one_hour);
            // this is new date and time for date today
            $date_today =  date('Y-m-d '.$time.':s', time());
            // empty slider and article title
            $slider_img = $article_title = '';
            // echo $date_today;
            $now = new DateTime($date_today);
            $expires = new DateTime($expired_date);
            // get the day
            $day = $expires->diff($now)->format("%d"); 
            // get the hours
            $hour = $expires->diff($now)->format("%h"); 
            // get the minutes
            $minutes = $expires->diff($now)->format("%i"); 
            // get the total hours of days plus the remaining hours
            $total_hours = $day*24+$hour; 
            //get the total of hours and minutes
            $total = $day*24+$hour+$minutes; 
            // put a condition that if have less than 24 make value as one if not display zero
            if($total_hours < 24){
                if($expired_date >= $date_today && $total !=0 && $sidebanner_img != '' || $rel != '' ){
                    $one_day_before = 'one';
                }
            }else{
                $one_day_before = 'zero';
            }
            // put empty variables to empty array value.
            $slider = $articles =  $data = array('');
            // check if the relationship is empty or not
            if(is_array($rel)){
                $count=0;
                $slider_img = array('');
                $slider = array('');

                // foreach the relationship has many slider and article
                foreach($rel as $post){
                    if( $post->post_type == 'slider' ){
                        $slider_img =  get_field('slider_image', $post->ID)['url'];
                        $slider[] = $slider_img; // pass the array to the variable of slider image
                    }
                    if($post->post_type == 'article' ){
                        $article_title =  $post->post_title;
                        $articles[] = $article_title; // pass the array to the variable of article
                    }
                }
            }
            
            // sort into the multidimensional array to make syncronize data
            $data = array (
                'one_day_before'  => $one_day_before,
                'date'          => $stop_date,
                'article'       => $articles,
                'slider_image' => $slider,
                'side-banner' => $sidebanner_img,
            );
            $datas[] = array_filter($data);
            
        }
        // create a function to make descending order base on date expiry.
        function descending_order($array,$key) {
        //Loop through and get the values of our specified key
            foreach($array as $k=>$v) {
                $b[] = strtolower($v[$key]);
            }
            asort($b);
            foreach($b as $k=>$v) {
                $c[] = $array[$k];
            }
            return $c;
        }
        $sorted = descending_order($datas, 'date');
        $count_data = 1;
        // display  the sorted data into  desecending order
        if(is_array($sorted)){
            foreach($sorted as $key => $value){
                if($value['one_day_before'] == 'one'){
                    // plus one day of expiry to get the different date today and before expiry
                    $date_expired = date('Y-m-d H:i:s', strtotime($value['date'] . ' +1 day'));
                    $now = new DateTime($date_today);
                    $expires = new DateTime($date_expired);
                    // display the diff of hours and mins of today and tomorrow
                    $hours_range = $expires->diff($now)->format("Due in %h hours  %i minutes");
                    // if the data more than 3 it will show the see more..
                    if($count_data++ <= 3){
                        ?>
                        <div class="container_oneBefore">
                            <h4><b><?= $hours_range; ?></b></h4> 
                            <?php
                            if(is_array($value)){
                                //for the article
                                $count_article = 1;
                                if(!empty(array_filter($value['article']))){
                                    echo '<div class="article">
                                            <h5>Article List</h5>
                                            <ul>';
                                    foreach(array_filter($value['article']) as $row){
                                            // if the article more than 3 it will show the see more..  
                                        if($count_article++ <= 3){
                                            echo ' <li>- '.$row.'</li>';
                                        }else{
                                            echo '<p id="wp-version-message" class="see-more-btn"><a href="admin.php?page=wmd-countdown-menu-main&ct=1" class="button" aria-describedby="wp-version">See more..</a></p>';
                                        }
                                    }
                                    echo ' </ul>     
                                        </div>';
                                }
                                // get the value of slider banner
                                $count_slider = 1;
                                if(!empty(array_filter($value['slider_image']))){
                                    echo ' <div class="slider-banner">
                                                <h5>Slider Banner</h5>
                                            <ul>';
                                    foreach(array_filter($value['slider_image']) as $row){
                                        // if the slider more than 3 it will show the see more..  
                                        if($count_slider++ <= 3){
                                            echo '<li><img src="'.$row.'" alt=""></li>';
                                        }else{
                                            echo '<p id="wp-version-message" class="see-more-btn"><a href="admin.php?page=wmd-countdown-menu-main&ct=1" class="button" aria-describedby="wp-version">See more..</a></p>';
                                        }
                                    }
                                    echo    '</ul>     
                                            </div>';
                                }
                                    // get the value of side banner
                                if(!empty($value['side-banner'])){
                                    echo '<div class="side-banner">
                                            <h5>Side Banner</h5>
                                            <img src="'.$value['side-banner'].'" alt="">
                                        </div>';
                                }
                            }
                            ?>
                        </div>
                    <?php
                    }else{
                        echo '<p id="wp-version-message" class="see-more-btn"><a href="admin.php?page=wmd-countdown-menu-main&ct=1" class="button" aria-describedby="wp-version">See more..</a></p>';
                    }
                }
                $one_day_before = $value['one_day_before'];
                $before_one_day[] = $one_day_before;
            }
            // put a condition that if can't see the array one it display no 
            if(!in_array('one',$before_one_day)){
                echo "no data";
            }
        }
    }

}

add_action('wp_dashboard_setup','dynamic_widgets_dashboard');
?>