<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function pmc_check_wp_version( $plugin_name = 'this plugin', $version = '3.0' ){
    global $wp_version;
    if( ( version_compare( $wp_version, $version, '>=' ) ) == false ){
        add_action( 'admin_notices', create_function( '', 'echo \'<div id="message" class="error fade"><p><strong>Sorry, ' . $plugin_name . ' works only under WordPress ' . $version . ' or higher</strong></p></div>\';' ) );
        return false;
    }
    else{
        return true;
    }
}

function pmc_check_direct_file_call( $file ){
    if( preg_match( '#' . $file . '#', $_SERVER['PHP_SELF'] ) ){
        wp_die( 'You are not allowed to call this page directly.' );
    }
}

function pmc_log( $msg ){
    //error_reporting( E_ALL );
    if( PMC_SKEJOOL_DEBUG ){
        error_log( $msg );
    }
}

?>
