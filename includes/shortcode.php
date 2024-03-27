<?php // Icon timer output
function icon_layout() {
  // Global variable
  $current_date = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',current_time("timestamp")).'-1 Hour'));

  $countdown_repeater = get_field('countdwn_timer', 'option');
  if(is_array($countdown_repeater)) :
    foreach($countdown_repeater as $countrow) :
      $relation = $countrow['rltship'];
      $expired_date = $countrow['tmr_grp']['exp_dte'];
      $countdown_function = $countrow['fnction'];
      if (is_array($relation)) :
        foreach($relation as $rel) :
          $rid = $rel->ID;
          if ($rid == get_the_ID() && $current_date < $expired_date) : ?> <!-- Output -->
            <div class="timer-display" id="svg_<?=$rid?>"><span>&#9201;</span> <p class="countdown" id="countdown_<?=$rid?>"></p></div>
            <div class="icon" id="i_<?=$rid?>"></div>
            <?php
            add_action('wp_footer', function() use($expired_date, $rid) { ?>
              <script type="text/javascript">
              // parameter Expiry Date, SVG ID, Icon ID, Text ID
              iconCdt("<?=$expired_date?>", "svg_<?=$rid?>", "i_<?=$rid?>", "countdown_<?=$rid?>");

              // Timer mousehover
              $("#i_<?=$rid?>")
              .mouseover(function() {
                $("#svg_<?=$rid?>").addClass('hover d-flex');
              })
              .mouseout(function() {
                $("#svg_<?=$rid?>").removeClass('hover d-flex');
              });
              </script>
              <?php
            });
          else:
            //for the draft of article
            $post_id = array( 'ID' => $rid, 'post_status' => 'draft' );
            ( $countdown_function == 1 ) ? wp_update_post($post_id) : '' ;
          endif;
        endforeach;
      endif;
    endforeach;
  endif;
}
add_shortcode( "icon_timer", "icon_layout" ); ?>
