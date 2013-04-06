<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PmcSkedjoolAppController
 *
 * @author pmcjury
 */
class PmcSkedjoolFrontController {

	private static $instance = null;

    public static function create(){
        if( self::$instance == null ){
            self::$instance = new PmcSkedjoolFrontController();
        }
        return self::$instance;
    }

    public static function getInstance(){
        return self::$instance;
    }
    //put your code here
    private function __construct(){
        //add_action();
        $this->load_css();
    }

    public function load_css(){
        wp_register_style( 'pmc_skejool', '/' . PLUGINDIR . '/skejool/views/pmc_skejool.css' );
        wp_enqueue_style( 'pmc_skejool' );
    }
}