<?php

/**
 * Contains methods for installation, activation, deactiviation, init option values, and updating.
 *
 * @author pmcjury
 */

include_once PMC_SKEJOOL_CONTROLLERS_DIR . '/admin/PmcSkejoolAdminOptionsController.php';

class PmcSkedjoolPluginController{

    public function __construct(){
   
    }

    public static function activate(){
        $options_controller = PmcSkejoolAdminOptionsController::create();
        $options_controller->activate_options();
    }

    public static function deactivate(){
        global $pmc_skejool_options;
        $pmc_skejool_options['active'] = false;
        update_option( 'pmc_skejool_options', $pmc_skejool_options );
    }

    public static function uninstall(){
        pmc_log( "Uninstalling..." );
        $options_controller = PmcSkejoolAdminOptionsController::create();
        $options_controller->delete_options();
    }

    public static function register_skejool_post_types(){
        pmc_log( "Adding skedjool post type..." );
        $labels = array(
            'name' => __( 'Schedules' ),
            'singular_name' => __( 'Schedule' ),
            'add_new' => __( 'Add Schedule' ),
            'add_new_item' => __( 'Add New Schedule' ),
            'new_item' => __( 'New Schedule' ),
            'view_item' => __( 'View Schedule' ),
            'search_item' => __( 'Search Schedule' ),
            'not_found' => __( 'No Schedule found' )
        );

        register_post_type( 'schedules',
                array(
                    'labels' => $labels,
                    'hierarchical' => true,
                    'description' => __( 'Schedules' ),
                    'public' => true,
                    'show_ui' => true,
                    'show_in_nav_menus' => true,
                    'rewrite' => array( 'slug' => 'schedule' ),
                    'supports' => array(
                        'title', 'author', 'excerpt',  'custom-fields'
                    ),
                )
        );
        $labels = array(
          'name' => _x( 'Type', 'schedule_type' ),
          'singular_name' => _x( 'Type', 'schedule_type' ),
          'search_items' => __( 'Search Type' ),
          'all_items' => __( 'All Types' ),
          'parent_item' => __( 'Parent Type' ),
          'parent_item_colon' => __( 'Parent Type:' ),
          'edit_item' => __( 'Edit Type' ),
          'update_item' => __( 'Update Type' ),
          'add_new_item' => __( 'Add New Type' ),
          'new_item_name' => __( 'New Type' ),
          );

          register_taxonomy( 'schedule_type', array( 'schedules' ), array(
           'public' => true,
          'hierarchical' => true,
          'labels' => $labels,
          'show_ui' => true,
          'query_var' => true,
          'rewrite' => array( 'slug' => 'schedules' ),
          ) );
    }

}