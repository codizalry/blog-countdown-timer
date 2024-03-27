<?php
  function my_acf_save_post($input) {
      ?>
        <style>
          #error-page .wp-die-message h2{
            visibility: hidden !important;
            position: relative !important;
          }

          #error-page .wp-die-message h2::after{
            visibility: visible !important;
            position: absolute !important;
            top: 0;
            left: 0;
            content: "Opps Something Wrong! You have an Error." !important;
          }
        </style>
      <?php
      $getNextArray = array_keys($_POST['acf']['field_6306d12ea4acc']);
      foreach ($getNextArray as $row) {
          $data_sidebar[] = $_POST['acf']['field_6306d12ea4acc'][''.$row.'']['field_6323d5af59102']['field_6322e0193096b'];
          $data_expiry[] = $_POST['acf']['field_6306d12ea4acc'][''.$row.'']['field_6306daae16f2f']['field_6306d18b58e45'];
          $data_relations[] = $_POST['acf']['field_6306d12ea4acc'][''.$row.'']['field_6306daf216f31'];
          $expiry = $_POST['acf']['field_6306d12ea4acc'][''.$row.'']['field_6306daae16f2f']['field_6306d18b58e45'];
          $condition = $_POST['acf']['field_6306d12ea4acc'][''.$row.'']['field_6306daae16f2f']['field_6306e797270ff'];
          $start = (!empty($_POST['field_6306d15058e44'])) ? $_POST['acf']['field_6306d12ea4acc'][''.$row.'']['field_6306daae16f2f']['field_6306d15058e44'] : '';
          $startvalidation[$start] = $expiry;
          $banner_side = $_POST['acf']['field_6306d12ea4acc'][''.$row.'']['field_6323d5af59102']['field_6322e0193096b'];
          $relations = $_POST['acf']['field_6306d12ea4acc'][''.$row.'']['field_6306daf216f31'];
          $data[] = array(
            'side_banner' => $banner_side,
            'relation' => $relations,
          );
      }

       // if the expiration date has empty
       in_array("",$data_expiry) ? acf_add_validation_error( $input,'Expiry Date has EMPTY VALUES') : '';
      if(!empty($startvalidation)){
        foreach ($startvalidation as $key => $value) {
          $key > $value ? acf_add_validation_error( $input, 'Start date is greater than expiry') : '';
        }
      }
    
      foreach($data as $key => $datarow){
        $banner = $datarow['side_banner'];
        $rel = $datarow['relation'];
        if($rel == '' && $banner == ''){
          acf_add_validation_error($input,' Side Banner and Article/Slider are EMPTY VALUES');
        }
      }

      $variable = array('sidebar', 'expiry');
      // make dynamic variable and message for validation
      foreach ($variable as $key => $value) {
        if((count(array_unique(array_filter(${'data_'.$value}))))<(count(array_filter(${'data_'.$value})))) {
        $variable = ($key == 0) ? 'Side Banner Has' : 'Expiry Date has';
            acf_add_validation_error( $input, $variable.' DUPLICATES VALUES');
        }
      }
     
     function array_flatten($array) {
      if (!is_array($array)) {
        return FALSE;
      }
      $result = array();
      foreach ($array as $key => $value) {
        if (is_array($value)) {
          $result = array_merge($result, array_flatten($value));
        }else{
          $result[$key] = $value;
        }
      }
      return $result;
    }
    // array_filter is to delete empty array
      if(count(array_unique(array_flatten(array_filter($data_relations))))<count(array_flatten(array_filter($data_relations)))) {
        $GetTitle ='';
        // display all even duplicate array
        $All = array_flatten(array_filter($data_relations));
        // display all unique array
        $unique = array_unique(array_flatten(array_filter($data_relations)));
        // display duplicates only and remove unique array.
        $duplicates = array_unique(array_diff_assoc($All,$unique));
        foreach ($duplicates as $value) {
          $GetTitle.= '<br><span> - '.get_the_title($value).'<span>';
        }
        acf_add_validation_error( $input, 'Article/Slider  DUPLICATES VALUES :'.$GetTitle);
      }
  }


  if (isset($_GET["page"])) {
    $_GET['pages'] = $_GET["page"];
  } else {
    $_GET['pages'] = '';
  }

  if ($_GET['pages'] == "wmd-countdown-menu-main" || $_GET['pages'] == "option-countdown-page") {
    add_action('acf/validate_save_post', 'my_acf_save_post');
  }

?>