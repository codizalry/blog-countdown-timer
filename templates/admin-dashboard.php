<?php
  // Fetch the URL
  $reactions_url = $_SERVER['REQUEST_URI'];
  $result = preg_split('/ct=/',$reactions_url);
  //Fetch the value of CT on URL
  $result_split = ( count($result) > 1 ) ? explode(' ',$result[1]) : '';
  //For empty CT value
  $countdown_url = (isset($result_split[0])) ? $result_split[0] : 0 ;

  //condition for the status of the countdown
  if ($countdown_url == 0) {
    $countdown_url = 'ongoing';
  } elseif ($countdown_url == 1) {
    $countdown_url = 'reminder';
  }else {
    $countdown_url = 'expired';
  }
  //Loop for countdown status
  $countdown_status = array(
    'ongoing' => "オンゴーイング",
    'reminder' => "期限切れ通知",
    'expired' => "期限切れ",
  ); ?>

 <div class="countdown-title">
   <h1>記事・添付ファイル カウントダウンタイマー</h1>
   <p>このリストは、各記事/添付ファイルの現在実行中のアクティブタイマーをカウントダウン状態で割ったデータです。<br/> 注意：コンフィグレーションでカウントダウンタイマーを削除した場合、リスト上のデータも削除されます。</p>
 </div>

 <!-- Creating Dynamic TAB Panel -->
 <div class="countdown-tab">
   <?php
   $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
   // Test if string contains the word wp-admin
   $admin_site = (strpos($url, 'tmj-times-tsr') !== false) ? 'tsr' : 'employee';
   $server_host = ($_SERVER['HTTP_HOST'] == 'localhost') ? '/tmj-times-'.$admin_site.'' : '' ;
   $count = 0;
   foreach ($countdown_status as $key => $value):
     $active_nav = ($key === $countdown_url) ? 'nav-tab-active' : '' ; ?>
     <a href="<?=$server_host;?>/wp-admin/admin.php?page=wmd-countdown-menu-main&ct=<?=$count++;?>" class="<?=$key;?> nav-tab <?=$active_nav;?>" >
       <p><?=$value;?></p>
     </a>
   <?php endforeach; ?>
 </div>

 <!-- Creating Dynamic body content -->
 <?php foreach ($countdown_status as $key => $value):
 $expired_width = ($countdown_url == 'expired') ? 'style="width: 15%;"' : '';
 $table_style = ($key == $countdown_url) ? 'style="display:block; overflow-x : auto;"' : '' ; ?>
 <div id="<?=$key;?>" class="countdown-content" <?=$table_style;?>>
   <table id="countdown_<?=$key;?>" class="table table-filter countdown-table">
     <p class="countdown-notice">
       <?=($countdown_url == 'reminder') ? '<span>参考</span>：期限リマインダーは、現在の時刻が期限から1日以内であれば、自動的に機能します。' : '';?>
       <?=($countdown_url == 'expired') ? '<span>参考</span>：3日後にカウントダウンが切れると、データは自動的に期限切れのカウントダウンリストから削除されます。' : '';?>
     </p>
    <thead>
      <th <?=$expired_width;?>>タイプ</th>
      <th>記事名</th>
      <th <?=$expired_width;?>>開始日</th>
      <th <?=$expired_width;?>>失効日</th>
      <?=($countdown_url != 'expired') ? '<th>存続期間</th>' : '';?>
      <?=($countdown_url != 'expired') ? '<th class="countdown-action">アクション</th>' : '';?>
    </thead>
    <tbody>
      <?php
      $count = get_post_meta(888888, 'Countdown-Timer', true);
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
          $function = $countrow['fnction'];
          $count = get_post_meta(888888, 'Countdown-Timer', true);
          $side_banner = $countrow['sdbnner_grp']['select_sideBanner'];
          //compute the total seconds
          $totalseconds	= ( $expired_dates - $currents_time );
          //created value for +1 on current daye
          $plus_time = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',current_time("timestamp")).'+1 day'));
          //Created condition for displaying the status of countdown
          $server_method = (isset($_GET['due_reminder'])) ? $_GET['due_reminder'] : '';
          if ($plus_time >= $expired_date && $current_date <= $expired_date) {
            $countdown_status = 'reminder';
          } elseif ($current_date > $expired_date) {
            $countdown_status = 'expired';
          } else {
            $countdown_status = 'ongoing';
          }
          $remove_expired = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',strtotime($expired_date)).'+3 day'));
          $relation = ($countdown_status == 'expired' && $current_date > $remove_expired) ? '' : $relation ;
          if (is_array($relation) && $countdown_status == $key) :
            foreach ($relation as $post) :
              if ($post->post_type == 'article') {
                 $article_type = '記事';
              } elseif ($post->post_type == 'slider') {
                $article_type = 'スライダーバナー';
              } else {
                $article_type = 'サイドバナー';
              }

              if ($countdown_status == 'expired' || ($countdown_status != 'expired' && $post->post_status != 'draft')) : ?>
            <tr <?=($server_method == $post->ID) ? 'style="background-color: #f0ad4e;"' : '' ;?>>
              <td> <?=$article_type;?> </td>
              <td> <?=( $post->post_type == 'article' ) ? edit_post_link( get_the_title($post->ID), '', '', $post->ID) : '<div class="slider-title"> <a href="'.get_edit_post_link($post->ID,'').'">  <img src="'.get_field('slider_image', $post->ID)['url'].'"> </a> </div>' ;?> </td>
              <td<?=($display_date == 1) ? '>'.date('Y年n月j日 H:i', strtotime($start_time)) : ' class="empty-data">'.$start_time;?> </td>
              <td> <?=date('Y年n月j日 H:i', strtotime($expired_date));?> </td>
              <?=($countdown_url != 'expired') ? '<td> '.seconds_to_time($totalseconds).' </td>' : '';?>
              <?=($countdown_url != 'expired') ? '<td class="action-button"> <a href="#" class="countdown-function button button-primary" data-toggle="modal" data-target="#staticBackdrop">削除</a> </td>' : '';?>
            </tr>
        <?php endif;
             endforeach;
             if (!empty($side_banner)): ?>
             <tr <?=($server_method == $side_banner) ? 'style="background-color: #f0ad4e;"' : '' ;?>>
               <td> サイドバナー </td>
               <td> <div class="sidebar-title"> <img src="<?=sidebanner_image($side_banner, 'url');?>"> </div> </td>
               <td<?=($display_date == 1) ? '>'.date('Y年n月j日 H:i', strtotime($start_time)) : ' class="empty-data">'.$start_time;?> </td>
               <td> <?=date('Y年n月j日 H:i', strtotime($expired_date));?> </td>
               <?=($countdown_url != 'expired') ? '<td> '.seconds_to_time($totalseconds).' </td>' : '';?>
               <?=($countdown_url != 'expired') ? '<td class="action-button"> <a href="#" class="countdown-function button button-primary" data-toggle="modal" data-target="#staticBackdrop">削除</a> </td>' : '';?>
             </tr>
        <?php
             endif;
            endif;
          endforeach;
        endif; ?>
      </tr>
    </tbody>
  </table>
</div>
<?php endforeach; ?>

<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content py-4 px-5 ">
      <div class="modal-body text-center mb-2">
        <p>カウントダウンを削除する</p>
        <span>本当にデータを削除してよいのですか？</span>
      </div>
      <div class="modal-footer">
        <button class="delete btn">
            <span class="btn-text"><a href="#" class="">パーマネント・デリート</a></span>
        </button>
        <button class="delete btn">
            <span class="btn-text"><a href="#" class="">一覧から削除する</a></span>
        </button>
        <button class="cancel btn" data-dismiss="modal">
            <span class="btn-text"><a href="#" class="">キャンセル</a></span>
        </button>
      </div>
    </div>
  </div>
</div>
