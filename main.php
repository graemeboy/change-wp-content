<?php

/*
Plugin Name: Change WP Content
Plugin URI: http://www.graemeboy.com/how-to-hide-that-you-use-wordpress
Description: Renames wp content directory to "media"
Author: Graeme Boy
Version: 1.0
Author URI: http://www.graemeboy.com
*/

function rename_wp_content_deactivate() {
    $new_name = 'wp-content';
    $old_name = 'media';
    rename_remove_definitions($new_name);
    change_wp_content_dir($old_name, $new_name);
}
function rename_wp_content_activate() {
    $new_name = 'media';
    $old_name = 'wp-content';
    rename_add_definitions($new_name);
    change_wp_content_dir($old_name, $new_name);
} // rename_wp_content_activate()

function change_wp_content_dir($old_name, $new_name)
{   
    rename ( ABSPATH . $old_name , ABSPATH . $new_name);
} // change_wp_content_dir

function rename_add_definitions ($new_name) {
    // This is the path to the config file
    $path_to_wp_config = ABSPATH . 'wp-config.php';
    // This is the part of the config-file before which we add the definitions
    $inclusion_string = "require_once(ABSPATH . 'wp-settings.php');";
    // These are the definitions to add
    $definitions_to_add = array (
        "define ( 'WP_CONTENT_FOLDERNAME', '$new_name' );",
        "define ( 'WP_CONTENT_DIR', ABSPATH . WP_CONTENT_FOLDERNAME );",
        "define ( 'WP_SITEURL', 'http://" . $_SERVER['HTTP_HOST'] . "/' );",
        "define ( 'WP_CONTENT_URL', WP_SITEURL . WP_CONTENT_FOLDERNAME );"
    );
    // Concat these in a readable way
    $definitions = implode("\n", $definitions_to_add);
    $definitions =  $definitions . "\n" . $inclusion_string;
    $pre = "\n/* Created by Rename WP Content plugin */\n";
    
    // Replace the inclusion string with our defitions
    $config_content = file_get_contents($path_to_wp_config);
    update_option( 'remove_wp_content_old_config', $config_content );
    $to_replace = $pre . $definitions;
    
    $new_config = str_replace( $inclusion_string, $to_replace, $config_content );
    // Put contents, which is identical to fopen, fwrite, fclose.
    file_put_contents( $path_to_wp_config, $new_config );
} // rename_add_definitions()

function rename_remove_definitions ($new_name) {
    $saved_old_config = get_option( 'remove_wp_content_old_config' );
    if ($saved_old_config != '') {
        $path_to_wp_config = ABSPATH . 'wp-config.php';
        file_put_contents( $path_to_wp_config, $saved_old_config );
    } else {
        // This is the path to the config file
        $path_to_wp_config = ABSPATH . 'wp-config.php';
        // This is the part of the config-file before which we add the definitions
        $inclusion_string = "require_once(ABSPATH . 'wp-settings.php');";
        // These are the definitions to add
        $definitions_to_remove = array (
            "define ( 'WP_CONTENT_FOLDERNAME', '$new_name' );",
            "define ( 'WP_CONTENT_DIR', ABSPATH . WP_CONTENT_FOLDERNAME );",
            "define ( 'WP_SITEURL', 'http://" . $_SERVER['HTTP_HOST'] . "/' );",
            "define ( 'WP_CONTENT_URL', WP_SITEURL . WP_CONTENT_FOLDERNAME );"
        );
        // Concat these in a readable way
        $definitions = implode("\n", $definitions_to_remove);
        $definitions =  $definitions . "\n" . $inclusion_string;
        $pre = "\n/* Created by Rename WP Content plugin */\n";
        
        // Replace the inclusion string with our defitions
        $config_content = file_get_contents($path_to_wp_config);
        $to_replace = $pre . $definitions;
        $old_config = str_replace($to_replace, $inclusion_string, $config_content);
        file_put_contents( $path_to_wp_config, $old_config );
    }
} // rename_add_definitions()

register_activation_hook( __FILE__, 'rename_wp_content_activate' );
register_deactivation_hook( __FILE__, 'rename_wp_content_deactivate' );

?>
