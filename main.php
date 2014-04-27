<?php

/*
Plugin Name: Rename WP Content
Plugin URI: http://www.graemeboy.com
Description: Renames wp content directory
Author: Graeme Boy
Version: 1.6
Author URI: http://www.graemeboy.com
*/

function rename_wp_content_activate() {
    rename_add_definitions();
}

function rename_add_definitions () {
    // This is the path to the config file
    $path_to_wp_config = ABSPATH.'wp-config.php';
    // This is the part of the config-file before which we add the definitions
    $inclusion_string = "require_once(ABSPATH . 'wp-settings.php');";
    // These are the definitions to add
    $definitions_to_add = array (
        "define ( 'WP_CONTENT_FOLDERNAME', 'media' );",
        "define ( 'WP_CONTENT_DIR', ABSPATH . WP_CONTENT_FOLDERNAME );",
        "define ( 'WP_SITEURL', 'http://" . $_SERVER['HTTP_HOST'] . "' );",
        "define ( 'WP_CONTENT_URL', WP_SITEURL . WP_CONTENT_FOLDERNAME );"
    );
    // Concat these in a readable way
    $definitions = implode("\n", $definitions_to_add);
    $definitions =  $definitions . "\n" . $inclusion_string;
    $pre = "\n/* Created by Rename WP Content plugin */\n";
    
    // Replace the inclusion string with our defitions
    $config_content = file_get_contents($path_to_wp_config);
    $to_replace = $pre . $definitions;
    
    echo $to_replace;
    $new_config = str_replace( $inclusion_string, $to_replace, $config_content );
    // Put contents, which is identical to fopen, fwrite, fclose.
    file_put_contents( $path_to_wp_config, $new_config );
} // rename_add_definitions()

register_activation_hook( __FILE__, 'rename_wp_content_activate' );

?>