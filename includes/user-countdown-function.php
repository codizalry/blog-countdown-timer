<?php
function acf_countdown_timer($atts) {
    $layout = $cdt_placement = $half_cdt_position = '';
    $id = $atts['id'];
    $alignment = (!empty($atts['alignment'])) ? $atts['alignment'] : '' ;

    // Select display field group
    $select_display = get_field('select_display', 'option');
    // Filter display and if statement for selecting layout
    $filter_display = $select_display['filter_display'];
    if ($filter_display == 'All') {
        $layout = 4;
    } else if ($filter_display == 'DaysHoursMins') {
        $layout = 3;
    } else if ($filter_display == 'DaysHours') {
        $layout = 2;
    }
    // Countdown timer field
    $countdwn_timer = get_field('countdwn_timer', 'option');
    if ( !empty($countdwn_timer)) {
        foreach ($countdwn_timer as $cdt_timer) {
            $tmr_grp = $cdt_timer['tmr_grp'];
            $exp_dte = $tmr_grp['exp_dte'];
            $sdbnner_grp = $cdt_timer['sdbnner_grp'];

            // If id is equal to selected banner
            $select_sideBanner = $sdbnner_grp['select_sideBanner'];
            if ($select_sideBanner == $id) {
                $cdt_position_grp = $sdbnner_grp['cdt_position_grp']; // CDT group
                $digital_cdt_pos_full_width = $cdt_position_grp['digital_cdt_pos_full_width']; // Choices for Full Width
                $digital_cdt_pos_half_width = $cdt_position_grp['digital_cdt_pos_half_width']; // Choices for half Width
                $cdt_above_image = $cdt_position_grp['cdt_above_image']; // Choices for half width and above image
                $cdt_inside_image = $cdt_position_grp['cdt_inside_image']; // Choices for half width and inside image
                $cdt_below_image = $cdt_position_grp['cdt_below_image']; //Choices for half width and below image
                if ($layout == 4) {
                    if ($digital_cdt_pos_full_width == 'top') {
                        $cdt_placement = 'flex-direction: column-reverse !important; align-items: center;';
                        $half_cdt_position = 'position: unset; display: flex; justify-content: center;';
                    } else {
                        $cdt_placement = 'flex-direction: column !important; align-items: center;';
                        $half_cdt_position = 'position: unset; display: flex; justify-content: center;';
                    }
                }  
                if ($layout == 3 || $layout == 2) {
                    // If timer is inside the image
                    if ($digital_cdt_pos_half_width == 'top' && $cdt_above_image == 'left') {
                        $cdt_placement = 'flex-direction: column-reverse; align-items: center;';
                        $half_cdt_position = 'position: unset; display: flex; justify-content: flex-start;';
                    }
                    else if ($digital_cdt_pos_half_width == 'top' && $cdt_above_image == 'center') {
                        $cdt_placement = 'flex-direction: column-reverse; align-items: center;';
                        $half_cdt_position = 'position: unset; display: flex; justify-content: center;';
                    }
                    else if ($digital_cdt_pos_half_width == 'top' && $cdt_above_image == 'right') {
                        $cdt_placement = 'flex-direction: column-reverse; align-items: center;';
                        $half_cdt_position = 'position: unset; display: flex; justify-content: flex-end;';
                    }
                    // If timer is inside the image
                    if ($digital_cdt_pos_half_width == 'inside' && $cdt_inside_image == 'up_left') {
                        $cdt_placement = 'flex-direction: column;';
                        $half_cdt_position = 'position: absolute; display: flex; justify-content: flex-start;';
                    }
                    else if ($digital_cdt_pos_half_width == 'inside' && $cdt_inside_image == 'up_right') {
                        $cdt_placement = 'flex-direction: column;';
                        $half_cdt_position = 'position: absolute; display: flex; justify-content: flex-end;';
                    }
                    else if ($digital_cdt_pos_half_width == 'inside' && $cdt_inside_image == 'low_left') {
                        $cdt_placement = 'flex-direction: column-reverse';
                        $half_cdt_position = 'position: absolute; display: flex; justify-content: flex-start;';
                    }
                    else if ($digital_cdt_pos_half_width == 'inside' && $cdt_inside_image == 'low_right') {
                        $cdt_placement = 'flex-direction: column-reverse';
                        $half_cdt_position = 'position: absolute; display: flex; justify-content: flex-end;';
                    }
                    // If timer is outside the image
                    if ($digital_cdt_pos_half_width == 'bot' && $cdt_below_image == 'left') {
                        $cdt_placement = 'flex-direction: column; align-items: center;';
                        $half_cdt_position = 'position: unset; display: flex; justify-content: flex-start;';
                    }
                    else if ($digital_cdt_pos_half_width == 'bot' && $cdt_below_image == 'center') {
                        $cdt_placement = 'flex-direction: column; align-items: center;';
                        $half_cdt_position = 'position: unset; display: flex; justify-content: center;';
                    }
                    else if ($digital_cdt_pos_half_width == 'bot' && $cdt_below_image == 'right') {
                        $cdt_placement = 'flex-direction: column; align-items: center;';
                        $half_cdt_position = 'position: unset; display: flex; justify-content: flex-end;';
                    }
                }
            }
        }
    return ($alignment == "position") ? $half_cdt_position : $cdt_placement ;
    }
}
add_shortcode('acf_shortcode', 'acf_countdown_timer');
