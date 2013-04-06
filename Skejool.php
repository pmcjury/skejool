<?php

/*
  Plugin Name: Skejool
  Plugin URI:
  Description: As the name implies, this plugin is a simple schedule maker for WordPress. No muss no fuss. At it's simpliest can simply create a schedule, and display it on the page.
  Version: 0.1
  Author: Patrick H. McJury
  Author URI: http://pmcjury.com
 */
// Init Checks

define( 'PMC_SKEJOOL_DEBUG', true );
if( PMC_SKEJOOL_DEBUG === true ){
  error_reporting( E_ALL & ~E_WARNING & ~E_NOTICE );
  ini_set('display_errors', 1);
}

include_once dirname( __FILE__ ) . '/includes' . '/wp_plugin_helpers.php';
pmc_check_direct_file_call( basename( __FILE__ ) );
if( !pmc_check_wp_version( 'Skejool' ) ){
  return;
}
else{
  // Version Debug and some default contstants
  define( 'PMC_SKEJOOL_VERSION', '0.1' );
  define( 'PMC_SKEJOOL_INCLUDES_DIR', dirname( __FILE__ ) . '/includes' );
  // Directory Constants
  define( 'PMC_SKEJOOL_BASE_DIR', dirname( __FILE__ ) );
  define( 'PMC_SKEJOOL_CONTROLLERS_DIR', dirname( __FILE__ ) . '/controllers' );
  define( 'PMC_SKEJOOL_HELPERS_DIR', dirname( __FILE__ ) . '/helpers' );
  define( 'PMC_SKEJOOL_MODELS_DIR', dirname( __FILE__ ) . '/models' );
  define( 'PMC_SKEJOOL_VIEWS_DIR', dirname( __FILE__ ) . '/views' );
  define( 'PMC_SKEJOOL_PLUGIN_BASENAME', PMC_SKEJOOL_BASE_DIR . '/Skejool.php' );
  $pmc_skejool_options = get_option( 'pmc_skejool_options' );
  // Init the plugin
  include_once PMC_SKEJOOL_BASE_DIR . '/PmcSkedjoolController.php';
  PmcSkedjoolController::create();
}