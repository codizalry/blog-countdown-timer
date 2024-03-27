<?php
// Countdown timer output
function countdown_layouts($atts) {
   
  $current_date = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',current_time("timestamp")).'-1 Hour'));
  // Get select_display group
  $select_display=get_field('select_display', 'option');
  // Get filter_display and if statement for selecting layout
  $filter_display = $select_display['filter_display'];
  $color_text = get_field('select_display', 'option')['color_text'];
  $bgcolor_text = get_field('select_display', 'option')['background_color'];
  $layout='';


  if ($filter_display == 'All') {
    $layout = 4;
  } else if ($filter_display == 'DaysHoursMins') {
    $layout = 3;
  } else if ($filter_display == 'DaysHours') {
    $layout = 2;
  }

  
  // Banner group
  $countdwn_timer=get_field('countdwn_timer', 'option');
  if ( !empty($countdwn_timer)) {
    foreach ($countdwn_timer as $cdt_timer) {
      $tmr_grp = $cdt_timer['tmr_grp'];
      $exp_dte = $tmr_grp['exp_dte'];
      $sdbnner_grp = $cdt_timer['sdbnner_grp'];
      $select_sideBanner = $sdbnner_grp['select_sideBanner'];
      $fnction = $cdt_timer['fnction'];
      $rel = $cdt_timer['rltship'];

      if($atts['post-type'] == 'slider'){
        if(is_array($rel)){
          foreach($rel as $rel_row){
            $rel_id = $rel_row->ID;
            if($rel_id == $atts['id'] && $current_date < $exp_dte){
              $id = $atts['id'];
              ?> 
              <!-- Output -->
              <div id="clock_<?= $id ?>"></div>
              <!-- Dynamic script -->
              <?php add_action('wp_footer', function() use($exp_dte, $id, $layout, $color_text, $bgcolor_text) { ?>
                <script type="text/javascript">
                // parameter Expiry Date, Element ID, Number Color, Background Color, Display format
                digitalcdt("<?=$exp_dte?>", "clock_<?=$id?>", "#ffffff", "#000000", "4");
                setTimeout(() => {
                  jQuery('.cdt-timer p.label').each(function (index) {
                   var getLabel = jQuery(this)[0].textContent;
                   setTimeout(()=>{
                    if(getLabel == 'DAY'){
                      jQuery(this)[0].innerHTML = "DAYS";
                    }else if(getLabel == 'HRS'){
                      jQuery(this)[0].innerHTML = "HOURS";
                    }else if(getLabel == 'MIN'){
                      jQuery(this)[0].innerHTML = "MINUTES";
                    }else if(getLabel == 'SEC'){
                      jQuery(this)[0].innerHTML = "SECONDS";
                    }
                   },500);
                  });
                }, 500);
                </script>
              <?php }
              );
            }else{
                	//condition for function to draft the post
                $slider_id = array( 'ID' => $rel_id, 'post_status' => 'draft' );
                 ($current_date > $exp_dte && $fnction == 1) ? wp_update_post($slider_id) : '' ;
            }
          }
        }
      }else{
        if ($select_sideBanner == $atts['id'] && $current_date < $exp_dte) {
          $id = $atts['id']; // Banner Image id
          $cdt_position_grp = $sdbnner_grp['cdt_position_grp']; // CDT group
          $digital_cdt_pos_half_width = $cdt_position_grp['digital_cdt_pos_half_width']; // Choices for half Width
          $cdt_inside_image = $cdt_position_grp['cdt_inside_image']; // Choices for half width and inside image
          $position = '';
          if ($layout == 3 || $layout == 2) {
            if ($digital_cdt_pos_half_width == 'inside' && $cdt_inside_image == 'up_left' || $digital_cdt_pos_half_width == 'inside' && $cdt_inside_image == 'up_right' || $digital_cdt_pos_half_width == 'inside' && $cdt_inside_image == 'low_left' || $digital_cdt_pos_half_width == 'inside' && $cdt_inside_image == 'low_right') {
              $position = 'inside_numbers';
            } else {
              '';
            }
         }
          // Countdown timer field
          $countdwn_timer = get_field('countdwn_timer', 'option'); ?>
      
          <!-- Output -->
          <div id="clock_<?= $id ?>"></div>
      
          <!-- Dynamic script -->
          <?php add_action('wp_footer', function() use($exp_dte, $id, $color_text, $bgcolor_text, $layout, $position) { ?>
            <script type="text/javascript">
              // parameter Expiry Date, Element ID, Number Color, Background Color, Display format
              digitalcdt("<?=$exp_dte?>", "clock_<?=$id?>", "<?=$color_text?>", "<?=$bgcolor_text?>", "<?=$layout?>", "<?=$position?>");
            </script>
          <?php }
          );
        }
        else {
          ( $fnction == 1 && $current_date > $exp_dte ) ? wp_trash_post($select_sideBanner) : '' ;
        }
      }
    }
  }
}


add_shortcode("countdown_timer", "countdown_layouts");
