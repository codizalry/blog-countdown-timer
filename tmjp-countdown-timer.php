<?php
/*
*  Plugin Name: TMJP Countdown Timer
*  Description: Easy to add and display responsive Countdown timer on your website. Also work with Gutenberg shortcode block.
*  Author: Jeffrey Cardino and Ryan Codizal
*  Version: 1.0.0
*/

// for security - need to make sure that only wordpress to activate of our plugin
// checking if the plugin is in the worpress.
defined( 'ABSPATH' ) or die('You are not in the Wordpress directory.');


//Function for fetching css and js
function countdown_admin_css_and_scripts(){
  if (isset($_GET["page"])) {
    $_GET['pages'] = $_GET["page"];
  } else {
    $_GET['pages'] = '';
  }
  if ($_GET['pages'] == "wmd-countdown-menu-main" || $_GET['pages'] == "option-countdown-page") {
    wp_enqueue_style('countdown-timer-admin-styles',  plugins_url( 'assets/css/admin-custom.css' , __FILE__ ));
    wp_enqueue_script('countdown-timer-admin-script', plugins_url( 'assets/js/admin-custom.js' , __FILE__ ), array('jquery'));
  }
  //Global CSS AND JS
  wp_enqueue_style('countdown-timer-alert-styles',  plugins_url( 'assets/css/global-custom.css' , __FILE__ ));
  wp_enqueue_script('countdown-timer-alert-script', plugins_url( 'assets/js/global-custom.js' , __FILE__ ), array('jquery'));
}
add_action('admin_enqueue_scripts','countdown_admin_css_and_scripts');

//Public CSS and JS
function public_css_and_script() {
  /* Register & Enqueue Styles. */
  wp_enqueue_style('countdown-site-styles',  plugins_url( 'assets/css/site-custom.css' , __FILE__ ));
  wp_enqueue_style('countdown-sidebar-styles',  plugins_url( 'assets/css/sidebar-banner.css' , __FILE__ ));
  wp_enqueue_style('countdown-timer-alert-styles',  plugins_url( 'assets/css/global-custom.css' , __FILE__ ));

  /* Register & Enqueue scripts. */
  wp_enqueue_script('countdown-timer-admin-script', plugins_url( 'assets/js/site-custom.js' , __FILE__ ), array('jquery'));
  /* Sidebar banner Register & Enqueue Styles. */
  wp_enqueue_style('sidebar-styles',  plugins_url( 'assets/css/sidebar-banner.css' , __FILE__ ));
  /* Sidebar banner Register & Enqueue scripts. */
  wp_enqueue_script('sidebar-timer-admin-script', plugins_url( 'assets/js/sidebar-banner.js' , __FILE__ ), array('jquery'));
}
add_action( 'wp_enqueue_scripts', 'public_css_and_script', 10 );


//Public CSS and JS
function countdown_timer_alert_script() {
  wp_enqueue_style('countdown-timer-alert-styles',  plugins_url( 'assets/css/global-custom.css' , __FILE__ ));
  wp_enqueue_script('countdown-timer-alert-script', plugins_url( 'assets/js/global-custom.js' , __FILE__ ), array('jquery'));
}
add_action( 'wp_enqueue_scripts', 'public_css_and_script', 10 );

// Creation of menu page on admin dashboard
function wmd_countdown_timer_menu() {
   //notification for the expired timer
	add_menu_page(
    'Countdown Timer',
    'Countdown',
    'manage_options',
    'wmd-countdown-menu',
    false,
    'dashicons-clock',
    27
  );
	add_submenu_page(
    'wmd-countdown-menu',
    'Registered CDT',
    'Registered CDT',
    'manage_options',
    'wmd-countdown-menu-main',
    'wmd_countdown_menu_main'
  );

	// Remove the default one so we can add our customized version.
	remove_submenu_page( 'wmd-countdown-menu', 'wmd-countdown-menu' );
}
add_action( 'admin_menu', 'wmd_countdown_timer_menu' );

//Create option page for the countdown timer configuration
if(function_exists('acf_add_options_page')) {
  acf_add_options_page(array(
    'page_title' 	=> 'Configuration',
    'menu_title' 	=> 'Countdown configuration',
    'menu_slug' 	=> 'option-countdown-page',
    'parent_slug'	=> 'wmd-countdown-menu',
    'position'    => false,
    'redirect'	  => false,
  ));
}

//Define function
if( ! defined( 'WMD_VERSION' ) ) {
  define( 'WMD_VERSION', '1.0.0' ); // Version of plugin
}
if( ! defined( 'WMD_DIR' ) ) {
  define( 'WMD_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( ! defined( 'WMD_URL' ) ) {
  define( 'WMD_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

//Callback admin dashboard
function wmd_countdown_menu_main(){
  ?>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
  <?php
  require_once( WMD_DIR . '/templates/admin-dashboard.php' );
}

function countdown_functions() {
  //Fetch the ACF Fields and Run the function in core
  include plugin_dir_path( __FILE__ ) . 'includes/acf-register.php';

  //alert notification File
  require_once( WMD_DIR . '/includes/alert-notification.php' );

  // if (isset($_GET["page"])) {
  //   $_GET['pages'] = $_GET["page"];
  // } else {
  //   $_GET['pages'] = '';
  // }
  // if ($_GET['pages'] == "wmd-countdown-menu-main" || $_GET['pages'] == "option-countdown-page") {
    
    require_once( WMD_DIR . '/includes/validation.php' );
    
  // }

  //Widget dashboard File
  require_once( WMD_DIR . '/templates/widget-dashboard.php' );
}

add_action( 'init', 'countdown_functions' );



//Admin countdown function
require_once( WMD_DIR . '/includes/countdown-function.php' );

//Shortcode File
require_once( WMD_DIR . '/includes/shortcode.php' );

//Shortcode File
require_once( WMD_DIR . '/includes/sidebar-shortcode.php' );

//User countdown function
require_once( WMD_DIR . '/includes/user-countdown-function.php' );

//Class for fetching the CSS and JS of countdown ( User side )
class WMD_Script {
  function __construct() {
    // Action to add style & script at front side
    add_action( 'wp_enqueue_scripts', array($this, 'front_style_script') );
  }

  /** Function to add styles & scripts at front side */
  function front_style_script() {

    // Global Variable
    global $post;

    // Registring public css
    wp_register_style( 'countdown-public-css', WMD_URL.'assets/css/global-custom.css', array(), WMD_VERSION );

    // Registring timer script
    wp_register_script( 'countdown-timecircle-js', WMD_URL.'assets/js/countdown-time-circles.js', array('jquery'), WMD_VERSION, true );

    // Registring public script
    wp_register_script( 'countdown-public-js', WMD_URL.'assets/js/global-custom-circles.js', array('jquery'), WMD_VERSION, true );

    // Enqueue Public style
    wp_enqueue_style( 'countdown-public-css' );

  }
}

$wmd_script = new WMD_Script();
?>
