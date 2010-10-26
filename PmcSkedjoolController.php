<?php

/**
 * Contoller for the overall plugin. Delegates to other controllers depending on admin or not.
 * Also registers default hooks for plugin activiation, deactivation, install, and uninstall.
 *
 * @author pmcjury
 */

require_once PMC_SKEJOOL_CONTROLLERS_DIR . '/PmcSkedjoolPluginController.php';

class PmcSkedjoolController{

    public function __construct(){
        global $pmc_skejool_options;
        $this->register_default_hooks();
        // call init actions needed for both admin and the front end
        $this->add_init_actions();
        // Delegate Control
        if( is_admin( ) ){
            include_once ( PMC_SKEJOOL_CONTROLLERS_DIR . '/admin/PmcSkedjoolAdminController.php' );
            $admin = new PmcSkedjoolAdminController();
        }
        else{
            include_once ( PMC_SKEJOOL_CONTROLLERS_DIR . '/PmcSkedjoolFrontController.php' );
            include_once PMC_SKEJOOL_MODELS_DIR . '/ScheduleGameData.php';
            include_once PMC_SKEJOOL_HELPERS_DIR . '/PmcSkedjoolHelper.php';
            $app = new PmcSkedjoolFrontController();
        }
    }

    private function register_default_hooks(){
        register_activation_hook( PMC_SKEJOOL_PLUGIN_BASENAME, array( 'PmcSkedjoolPluginController', 'activate' ) );
        register_deactivation_hook( PMC_SKEJOOL_PLUGIN_BASENAME, array( 'PmcSkedjoolPluginController', 'deactivate' ) );
        register_uninstall_hook( PMC_SKEJOOL_PLUGIN_BASENAME, array( 'PmcSkedjoolPluginController', 'uninstall' ) );
    }

    private function add_init_actions(){
        add_action( 'init', array( 'PmcSkedjoolPluginController', 'register_skejool_post_types' ) );
    }

}