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
    //put your code here
    public function __construct(){
        //add_action();
        $this->load_css();
    }

    public function load_css(){
        wp_register_style( 'pmc_skejool', '/' . PLUGINDIR . '/skejool/views/pmc_skejool.css' );
        wp_enqueue_style( 'pmc_skejool' );
    }
}
?>
