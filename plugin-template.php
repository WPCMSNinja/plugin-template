<?php
/*
   Plugin Name: Plugin Template Code Generator
   Plugin URI: http://wordpress.org/extend/plugins/plugin-template/
   Version: 0.1
   Author: msimpson
   Description: Plugin Template Code Generator
   Text Domain: plugin-template
   License: LICENSE_TYPE
  */

/*
    "WordPress Plugin Template" Copyright (C) 2011 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of WordPress Plugin Template.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

$PluginTemplate_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function PluginTemplate_noticePhpVersionWrong() {
    global $PluginTemplate_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
         __('Error: plugin "Plugin Template Code Generator" requires a newer version of PHP to be running.', 'plugin-template') .
         '<br/>' . __('Minimal version of PHP required: ', 'plugin-template') . '<strong>' . $PluginTemplate_minimalRequiredPhpVersion . '</strong>' .
         '<br/>' . __('Your server\'s PHP version: ', 'plugin-template') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function PluginTemplate_PhpVersionCheck() {
    global $PluginTemplate_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $PluginTemplate_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'PluginTemplate_noticePhpVersionWrong');
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
function PluginTemplate_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('plugin-template', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// First initialize i18n
PluginTemplate_i18n_init();

// Next, run the version check.
// If it is successful, continue with initialization for this plugin
if (PluginTemplate_PhpVersionCheck()) {
    try {
        // Only load and run the init function if we know PHP version can parse it
        require_once('plugin-template_init.php');
        PluginTemplate_init(__FILE__);
    }
    catch (Exception $ex) {
        echo '<div class="updated fade">' . $ex->getMessage() . '</div>';
    }
}
