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
        if( PMC_SKEJOOL_ACTIVE === true ){
            pmc_log("Plugin is active...");
            // call init actions needed for both admin and the front end
            $this->add_init_actions();
            // Delegate Control
            if( is_admin( ) ){
                require_once ( PMC_SKEJOOL_CONTROLLERS_DIR . '/admin/PmcSkedjoolAdminController.php' );
                $admin = new PmcSkedjoolAdminController();
            }
            else{
                require_once ( PMC_SKEJOOL_CONTROLLERS_DIR . '/PmcSkedjoolFrontController.php' );
                include_once PMC_SKEJOOL_MODELS_DIR . '/ScheduleGameData.php';
                include_once PMC_SKEJOOL_HELPERS_DIR . '/PmcSkedjoolHelper.php';
                $app = new PmcSkedjoolFrontController();
            }
        }
        else{
            pmc_log("Plugin is not active...");
            $this->register_default_hooks();
        }
    }

    private function register_default_hooks(){
        pmc_log("Registering Activation hooks...");
        register_activation_hook( PMC_SKEJOOL_PLUGIN_BASENAME, array( 'PmcSkedjoolPluginController', 'activate' ) );
        register_deactivation_hook( PMC_SKEJOOL_PLUGIN_BASENAME, array( 'PmcSkedjoolPluginController', 'deactivate' ) );
        //register_uninstall_hook( PMC_SKEJOOL_PLUGIN_BASENAME, array( 'PmcSkedjoolPluginController', 'uninstall' ) );
    }

    private function add_init_actions(){
        pmc_log("Init hooks...");
        add_action( 'init', array( 'PmcSkedjoolPluginController', 'register_skejool_post_types' ) );
    }

}