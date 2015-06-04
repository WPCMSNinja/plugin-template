<?php
/*
   Plugin Name: PLUGIN_NAME
   Plugin URI: http://wordpress.org/extend/plugins/PLUGIN_DIR/
   Version: 0.1
   Author: AUTHOR
   Description: DESCRIPTION_SHORT
   Text Domain: TEXT_DOMAIN
   License: LICENSE_TYPE
  */

/*
    COPYRIGHT_TEXT

    This following part of this file is part of WordPress Plugin Template for WordPress.

    LICENSE_TEXT
*/

$PREFIX_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function PREFIX_noticePhpVersionWrong() {
    global $PREFIX_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "PLUGIN_NAME" requires a newer version of PHP to be running.',  'TEXT_DOMAIN').
            '<br/>' . __('Minimal version of PHP required: ', 'TEXT_DOMAIN') . '<strong>' . $PREFIX_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'TEXT_DOMAIN') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function PREFIX_PhpVersionCheck() {
    global $PREFIX_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $PREFIX_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'PREFIX_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function PREFIX_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('TEXT_DOMAIN', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// First initialize i18n
PREFIX_i18n_init();


// Next, run the version check.
// If it is successful, continue with initialization for this plugin
if (PREFIX_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('PLUGIN_DIR_init.php');
    PREFIX_init(__FILE__);
}
