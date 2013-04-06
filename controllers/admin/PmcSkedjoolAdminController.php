<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PmcSkedjoolAdminController
 *
 * @author pmcjury
 */
include_once PMC_SKEJOOL_CONTROLLERS_DIR . '/admin/PmcSkejoolAdminOptionsController.php';
include_once PMC_SKEJOOL_MODELS_DIR . '/ScheduleGameData.php';

class PmcSkedjoolAdminController{

    public $key = 'schedule_games';
    public $title = 'Games';
    public $post_type = 'schedules';
    public $game_input_fields = array( );
    
    private static $instance = null;

    public static function create(){
        if( self::$instance == null ){
            self::$instance = new PmcSkedjoolAdminController();
        }
        return self::$instance;
    }

    public static function getInstance(){
        return self::$instance;
    }

    private function __construct(){
        $this->game_input_fields = ScheduleGame::$game_input_fields;
        $this->load_css();
        $options_controller = PmcSkejoolAdminOptionsController::create();
        add_action( 'admin_init', array ( &$options_controller, 'options_init') );
        add_action( 'admin_enqueue_scripts', array( &$this, 'load_js' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'load_css' ) );
        add_action( 'admin_menu', array( &$this, 'create_games_meta_box' ) );
        add_action( 'admin_menu', array ( &$this, 'add_admin_menu') );
        add_action( 'save_post', array( &$this, 'save_games_meta_box' ) );
    }

    public function add_admin_menu(){
        add_options_page( 'Schedule Settings', "Schedule", 8, 'pmc_skejool_options_settings', array ( 'PmcSkejoolAdminOptionsController', 'show_options_page' ) );
    }

    public function load_css(){
        wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css');
        wp_enqueue_style( 'jquery-ui' );        
        wp_register_style( 'jquery-ui-timepicker-addon', '/' . PLUGINDIR . '/skejool/views/admin/css/jquery-ui-timepicker-addon.css' );
        wp_enqueue_style( 'jquery-ui-timepicker-addon' );
    }

    public function load_js(){
        wp_deregister_script( 'autosave' );
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-datepicker');
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'jquery-effects-core');
        wp_enqueue_script( 'jquery-effects-highlight');
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_register_script( 'jquery-ui-timepicker-addon', '/' . PLUGINDIR . '/skejool/views/admin/js/jquery-ui-timepicker-addon.js',
        array('jquery','jquery-ui-core', 'jquery-ui-datepicker' ), '1.2', true);
        wp_enqueue_script(  'jquery-ui-timepicker-addon');
    }

    public function save_games_meta_box( $post_id ){
        global $post;
        if( wp_verify_nonce( $_POST[$this->key . '_wpnonce'], PMC_SKEJOOL_PLUGIN_BASENAME ) && current_user_can( 'edit_post', $post_id ) && $_POST && !empty( $_POST['pmc_schedule_data_date'] ) ){
            $schedule = new ScheduleGameData( $_POST, false );
            update_post_meta( $post_id, $this->key, $schedule->games );
        }
        else{
            return $post_id;
        }
    }

    public function create_games_meta_box(){
        if( function_exists( 'add_meta_box' ) ){
            add_meta_box( 'new-meta-boxes', $this->title, array( &$this, 'display_games_meta_box' ), $this->post_type, 'normal', 'high' );
        }
        remove_meta_box( 'postexcerpt', $this->post_type, 'normal' );
        remove_meta_box( 'postcustom', $this->post_type, 'normal' );
    }

    public function display_games_meta_box(){
        global $post;
        $data = get_post_meta( $post->ID, $this->key, true );
        $schedule = new ScheduleGameData( $data );
        include_once PMC_SKEJOOL_HELPERS_DIR . '/admin/PmcSkedjoolAdminHelper.php';
        $helper = new PmcSkedjoolAdminHelper();
        include_once PMC_SKEJOOL_VIEWS_DIR . '/admin/index.php';
    }

}