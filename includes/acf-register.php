<?php
    $top_banners = get_field('top_banner_rptr', 'option');
    if (is_array($top_banners)) {
      $count = 1;
        foreach ($top_banners as $banners) {
            //Banner Image
            $banner_group = $banners['banner_grp'];
            $banner_image = (!empty($banner_group['banner_image_mg'])) ? $banner_group['banner_image_mg'] : '';
            if ($banner_image['status'] != 'trash') {
                $side_banner_id[$banner_image['ID']] = $banner_image['url'];
            }
        }
    }


    if( function_exists('acf_add_local_field_group') ):
        acf_add_local_field_group(array(
            'key' => 'group_6306d0c92202e',
            'title' => 'Countdown Timer_Latest1',
            'fields' => array(
                array(
                    'key' => 'field_6306e00bc2361',
                    'label' => 'Select Display',
                    'name' => 'select_display',
                    'type' => 'group',
                    'instructions' => 'You can filter the countdown timer in this section based on what you want to see on the screen and you can also change the background and text colors.
                     <p style="color:red;">Note : Once chosen, it will be applied to all posts, including the background and text colors. </p>',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_6306e033c2362',
                            'label' => 'Filter Display',
                            'name' => 'filter_display',
                            'type' => 'radio',
                            'instructions' => 'Choose the radio button to specify the countdown timer that will be shown on the screen.',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'choices' => array(
                                'All' => 'Days , Hours, Minutes, Seconds',
                                'DaysHoursMins' => 'Days , Hours, Minutes',
                                'DaysHours' => 'Days , Hours',
                            ),
                            'allow_null' => 0,
                            'other_choice' => 0,
                            'default_value' => '',
                            'layout' => 'vertical',
                            'return_format' => 'value',
                            'save_other_choice' => 0,
                        ),
                        array(
                            'key' => 'field_63214aee466fa',
                            'label' => 'Preview Timer',
                            'name' => '',
                            'type' => 'message',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_6306e033c2362',
                                        'operator' => '==',
                                        'value' => 'All',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '<p style="text-align: center; margin: 0;" >
                            <img src="/tmj-times-tsr/wp-content/plugins/tmjp-countdown-timer/assets/img/DaysHoursMinsSecs.gif" width ="310" height="86">
                            </p>',
                            'new_lines' => 'wpautop',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_632177ff9983c',
                            'label' => 'Preview Timer',
                            'name' => '',
                            'type' => 'message',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_6306e033c2362',
                                        'operator' => '==',
                                        'value' => 'DaysHoursMins',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '<p style="text-align: center; margin: 0;" >
                            <img src="/tmj-times-tsr/wp-content/plugins/tmjp-countdown-timer/assets/img/DaysHoursMins.gif" width ="255" height="86">
                            </p>',
                            'new_lines' => 'wpautop',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_6321511a2a88c',
                            'label' => 'Preview Timer',
                            'name' => '',
                            'type' => 'message',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_6306e033c2362',
                                        'operator' => '==',
                                        'value' => 'DaysHours',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '<p style="text-align: center; margin: 0;" >
                            <img src="/tmj-times-tsr/wp-content/plugins/tmjp-countdown-timer/assets/img/DaysHours.gif" width ="171" height="86">
                            </p>',
                            'new_lines' => 'wpautop',
                            'esc_html' => 0,
                        ),
                        array(
                            'key' => 'field_6306d1d058e47',
                            'label' => 'Color text',
                            'name' => 'color_text',
                            'type' => 'color_picker',
                            'instructions' => 'Please select the desired color.',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'enable_opacity' => 0,
                            'return_format' => 'string',
                        ),
                        array(
                            'key' => 'field_6306d1b758e46',
                            'label' => 'Background color',
                            'name' => 'background_color',
                            'type' => 'color_picker',
                            'instructions' => 'Select the desired background color of countdown timer.',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'enable_opacity' => 0,
                            'return_format' => 'string',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_6306d12ea4acc',
                    'label' => '',
                    'name' => 'countdwn_timer',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 1,
                    'max' => 0,
                    'layout' => 'block',
                    'button_label' => '',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_6306daae16f2f',
                            'label' => '',
                            'name' => 'tmr_grp',
                            'type' => 'group',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'layout' => 'table',
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_6306e797270ff',
                                    'label' => 'Show Start Timer',
                                    'name' => 'dsply_strtime',
                                    'type' => 'true_false',
                                    'instructions' => 'Please choose the display start date if you want to add a start date countdown to the post.',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'message' => '',
                                    'default_value' => 0,
                                    'ui' => 1,
                                    'ui_on_text' => 'Display the Start Date',
                                    'ui_off_text' => 'Hide the Start Date',
                                ),
                                array(
                                    'key' => 'field_6306d15058e44',
                                    'label' => 'Start Date',
                                    'name' => 'strt_dte',
                                    'type' => 'date_time_picker',
                                    'instructions' => 'Start date refers to the time that you want to apply the post\'s countdown timer.',
                                    'required' => 0,
                                    'conditional_logic' => array(
                                        array(
                                            array(
                                                'field' => 'field_6306e797270ff',
                                                'operator' => '==',
                                                'value' => '1',
                                            ),
                                            array(
                                                'field' => 'field_6306db7216f32',
                                                'operator' => '!=',
                                                'value' => '1',
                                            ),
                                        ),
                                    ),
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'display_format' => 'Y-m-d H:i:s',
                                    'return_format' => 'Y-m-d H:i:s',
                                    'first_day' => 1,
                                ),
                                array(
                                    'key' => 'field_6306d18b58e45',
                                    'label' => 'Expired Date',
                                    'name' => 'exp_dte',
                                    'type' => 'date_time_picker',
                                    'instructions' => 'Expiry date is a time frame after which something should no longer be used.',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'display_format' => 'Y-m-d H:i:s',
                                    'return_format' => 'Y-m-d H:i:s',
                                    'first_day' => 1,
                                ),
                                array(
                    							'key' => 'field_632bfa345b0a9',
                    							'label' => 'Due Date Reminder',
                    							'name' => 'rmndr_slct',
                    							'type' => 'select',
                    							'instructions' => 'Select time for the interval of due date reminder.',
                    							'required' => 0,
                    							'conditional_logic' => 0,
                    							'wrapper' => array(
                    								'width' => '',
                    								'class' => '',
                    								'id' => '',
                    							),
                    							'choices' => array(
                    								0 => 'None',
                    								1 => '10 Minutes Before',
                    								2 => '30 Minutes Before',
                    								3 => '1 Hour Before',
                    								4 => '2 Hours Before',
                    								5 => '1 Day Before',
                    								6 => 'At Time of Due Date',
                    							),
                    							'default_value' => false,
                    							'allow_null' => 0,
                    							'multiple' => 0,
                    							'ui' => 0,
                    							'return_format' => 'array',
                    							'ajax' => 0,
                    							'placeholder' => '',
                    						),
                            ),
                        ),
                        array(
                            'key' => 'field_6306daf216f31',
                            'label' => 'Attach the timer to the post or slider image',
                            'name' => 'rltship',
                            'type' => 'relationship',
                            'instructions' => 'In this section, you can apply several slider images or posts to a single countdown timer. <br>
                            <p style="color:red;">Note ：If the post or slider image already exists in another countdown timer, it will be applied to the new countdown timer as well. </p>',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => array(
                                0 => 'article',
                                1 => 'slider',
                            ),
                            'taxonomy' => '',
                            'filters' => array(
                                0 => 'search',
                                1 => 'post_type',
                            ),
                            'elements' => '',
                            'min' => '',
                            'max' => '',
                            'return_format' => 'object',
                        ),
                        array(
                            'key' => 'field_6323d5af59102',
                            'label' => '',
                            'name' => 'sdbnner_grp',
                            'type' => 'group',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => 'sideBanner',
                                'id' => 'sideBanner',
                            ),
                            'layout' => 'table',
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_6322e0193096b',
                                    'label' => 'Select Side banner',
                                    'name' => 'select_sideBanner',
                                    'type' => 'select',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'choices' => $side_banner_id,
                                    'default_value' => 'Select Banner',
                                    'allow_null' => 1,
                                    'multiple' => 0,
                                    'ui' => 0,
                                    'ajax' => 0,
                                    'return_format' => 'value',
                                    'placeholder' => '',
                                ),
                                array(
                                    'key' => 'field_6323d6f659103',
                                    'label' => 'Side banner Image',
                                    'name' => '',
                                    'type' => 'message',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'message' => '<div class="sidebanner"></div>',
                                    'new_lines' => 'wpautop',
                                    'esc_html' => 0,
                                ),
                                array(
                                    'key' => 'field_633d2bb90ce31',
                                    'label' => '',
                                    'name' => 'cdt_position_grp',
                                    'type' => 'group',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'layout' => 'block',
                                    'sub_fields' => array(
                                        array(
                                            'key' => 'field_633d29873e752',
                                            'label' => 'Digital Timer Position',
                                            'name' => 'digital_cdt_pos_full_width',
                                            'type' => 'button_group',
                                            'instructions' => 'Choose desire placement of timer',
                                            'required' => 0,
                                            'conditional_logic' => array(
                                                array(
                                                    array(
                                                        'field' => 'field_6306e033c2362',
                                                        'operator' => '==',
                                                        'value' => 'All',
                                                    ),
                                                ),
                                            ),
                                            'wrapper' => array(
                                                'width' => '',
                                                'class' => '',
                                                'id' => '',
                                            ),
                                            'choices' => array(
                                                'top' => 'Top of the Image',
                                                'bot' => 'Bottom of the Image',
                                            ),
                                            'allow_null' => 0,
                                            'default_value' => '',
                                            'layout' => 'horizontal',
                                            'return_format' => 'value',
                                        ),
                                        array(
                                            'key' => 'field_633d2ad53f358',
                                            'label' => 'Digital Timer Position',
                                            'name' => 'digital_cdt_pos_half_width',
                                            'type' => 'button_group',
                                            'instructions' => 'Choose desire placement of timer',
                                            'required' => 0,
                                            'conditional_logic' => array(
                                                array(
                                                    array(
                                                        'field' => 'field_6306e033c2362',
                                                        'operator' => '!=',
                                                        'value' => 'All',
                                                    ),
                                                ),
                                            ),
                                            'wrapper' => array(
                                                'width' => '',
                                                'class' => '',
                                                'id' => '',
                                            ),
                                            'choices' => array(
                                                'top' => 'Above the Image',
                                                'inside' => 'Overlap or Inside the Image',
                                                'bot' => 'Below the Image',
                                            ),
                                            'allow_null' => 0,
                                            'default_value' => '',
                                            'layout' => 'vertical',
                                            'return_format' => 'value',
                                        ),
                                        array(
                                            'key' => 'field_633d2d06f172a',
                                            'label' => 'Placement of the Timer Above the Image',
                                            'name' => 'cdt_above_image',
                                            'type' => 'radio',
                                            'instructions' => 'Choose desire timer position above the image',
                                            'required' => 0,
                                            'conditional_logic' => array(
                                                array(
                                                    array(
                                                        'field' => 'field_633d2ad53f358',
                                                        'operator' => '==',
                                                        'value' => 'top',
                                                    ),
                                                ),
                                            ),
                                            'wrapper' => array(
                                                'width' => '',
                                                'class' => '',
                                                'id' => '',
                                            ),
                                            'choices' => array(
                                                'left' => 'Left',
                                                'center' => 'Center',
                                                'right' => 'Right',
                                            ),
                                            'allow_null' => 0,
                                            'other_choice' => 0,
                                            'default_value' => '',
                                            'layout' => 'horizontal',
                                            'return_format' => 'value',
                                            'save_other_choice' => 0,
                                        ),
                                        array(
                                            'key' => 'field_633d2e2c791aa',
                                            'label' => 'Placement of the Timer Inside the Image',
                                            'name' => 'cdt_inside_image',
                                            'type' => 'radio',
                                            'instructions' => 'Choose desire timer position inside the image',
                                            'required' => 0,
                                            'conditional_logic' => array(
                                                array(
                                                    array(
                                                        'field' => 'field_633d2ad53f358',
                                                        'operator' => '==',
                                                        'value' => 'inside',
                                                    ),
                                                ),
                                            ),
                                            'wrapper' => array(
                                                'width' => '',
                                                'class' => '',
                                                'id' => '',
                                            ),
                                            'choices' => array(
                                                'up_left' => 'Upper Left',
                                                'up_right' => 'Upper Right',
                                                'low_left' => 'Lower Left',
                                                'low_right' => 'Lower Right',
                                            ),
                                            'allow_null' => 0,
                                            'other_choice' => 0,
                                            'default_value' => '',
                                            'layout' => 'horizontal',
                                            'return_format' => 'value',
                                            'save_other_choice' => 0,
                                        ),
                                        array(
                                            'key' => 'field_633d2f754ddeb',
                                            'label' => 'Placement of the Timer below the Image',
                                            'name' => 'cdt_below_image',
                                            'type' => 'radio',
                                            'instructions' => 'Choose desire timer position below the image',
                                            'required' => 0,
                                            'conditional_logic' => array(
                                                array(
                                                    array(
                                                        'field' => 'field_633d2ad53f358',
                                                        'operator' => '==',
                                                        'value' => 'bot',
                                                    ),
                                                ),
                                            ),
                                            'wrapper' => array(
                                                'width' => '',
                                                'class' => '',
                                                'id' => '',
                                            ),
                                            'choices' => array(
                                                'left' => 'Left',
                                                'center' => 'Center',
                                                'right' => 'Right',
                                            ),
                                            'allow_null' => 0,
                                            'other_choice' => 0,
                                            'default_value' => '',
                                            'layout' => 'horizontal',
                                            'return_format' => 'value',
                                            'save_other_choice' => 0,
                                        ),
                                    ),
                                ),

                            
                            ),
                        ),
                        array(
                            'key' => 'field_6306db7216f32',
                            'label' => 'Select function',
                            'name' => 'fnction',
                            'type' => 'true_false',
                            'instructions' => 'In this section, you may select whether the article will be drafted or whether the countdown timer will just be disabled.',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '',
                            'default_value' => 0,
                            'ui' => 1,
                            'ui_on_text' => 'Draft the article',
                            'ui_off_text' => 'Disable only the timer',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'option-countdown-page',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
    endif;
?>
