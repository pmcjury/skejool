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

    public function __construct(){
        $this->game_input_fields = ScheduleGame::$game_input_fields;
        $this->load_css();
        $options_controller = new PmcSkejoolAdminOptionsController();
        add_action( 'admin_init', array ( &$options_controller, 'options_init') );
        add_action( 'admin_init', array( &$this, 'load_js' ) );
        add_action( 'admin_init', array( &$this, 'load_css' ) );
        add_action( 'admin_menu', array( &$this, 'create_games_meta_box' ) );
        add_action( 'admin_menu', array ( &$this, 'add_admin_menu') );
        add_action( 'save_post', array( &$this, 'save_games_meta_box' ) );
    }

    public function add_admin_menu(){
        add_options_page( 'Schedule Settings', "Schedule", 8, 'pmc_skejool_options_settings', array ( 'PmcSkejoolAdminOptionsController', 'show_options_page' ) );
    }

    public function load_css(){
        wp_register_style( 'jquery-ui-datepicker-smoothness', '/' . PLUGINDIR . '/skejool/views/admin/jquery-ui-1.7.3.custom/css/smoothness/jquery-ui-1.7.3.custom.css' );
        wp_enqueue_style( 'jquery-ui-datepicker-smoothness' );
    }

    public function load_js(){
        wp_deregister_script( 'autosave' );
        wp_enqueue_script( 'jquery-ui-date-picker', '/' . PLUGINDIR . '/skejool/views/admin/jquery-ui-1.7.3.custom/js/jquery-ui-1.7.3.custom.min.js', array( ), false, true ); // in the footer last param == true
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