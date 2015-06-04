<?php
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

function PluginTemplate_init($file) {

    require_once('PluginTemplate_Plugin.php');
    $aPlugin = new PluginTemplate_Plugin();

    // Install the plugin
    // NOTE: this file gets run each time you *activate* the plugin.
    // So in WP when you "install" the plugin, all that does it dump its files in the plugin-templates directory
    // but it does not call any of its code.
    // So here, the plugin tracks whether or not it has run its install operation, and we ensure it is run only once
    // on the first activation
    if (!$aPlugin->isInstalled()) {
        $aPlugin->install();
    }
    else {
        // Perform any version-upgrade activities prior to activation (e.g. database changes)
        $aPlugin->upgrade();
    }

    // Add callbacks to hooks
    $aPlugin->addActionsAndFilters();

    if (!$file) {
        $file = __FILE__;
    }
    // Register the Plugin Activation Hook
    register_activation_hook($file, array(&$aPlugin, 'activate'));


    // Register the Plugin Deactivation Hook
    register_deactivation_hook($file, array(&$aPlugin, 'deactivate'));
}
