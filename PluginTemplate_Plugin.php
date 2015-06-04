<?php


include_once('PluginTemplate_LifeCycle.php');

class PluginTemplate_Plugin extends PluginTemplate_LifeCycle {

//    protected function getMainPluginFileName() {
//        return 'plugin-template.php';
//    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
        );
    }

    public function getPluginDisplayName() {
        return 'Plugin Template Code Generator';
    }


    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }


    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37


        // Adding scripts & styles to all pages
        // Examples:
        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39
        include_once('InputFormShortCode.php');
        $sc = new InputFormShortCode();
        $sc->actionUrl = $this->getAjaxUrl('GenerateDownload');
        $sc->register('plugin-template-form');


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41
        add_action('wp_ajax_GenerateDownload', array(&$this, 'ajaxGenerateDownload'));
        add_action('wp_ajax_nopriv_GenerateDownload', array(&$this, 'ajaxGenerateDownload'));

        //echo $this->getAjaxUrl('GenerateDownload');
        //http://blog.michael-simpson.com/wp-admin/admin-ajax.php?action=GenerateDownload
    }

    public function ajaxGenerateDownload() {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

        // Validate input
        if (!isset($_REQUEST['PLUGIN_NAME'])) {
            header("Content-type: text/plain");
            echo 'PLUGIN_NAME not set';
            die();
        }
        if (!isset($_REQUEST['PLUGIN_DIR'])) {
            header("Content-type: text/plain");
            echo 'PLUGIN_DIR not set';
            die();
        }
        if (!isset($_REQUEST['PREFIX'])) {
            header("Content-type: text/plain");
            echo 'PREFIX not set';
            die();
        }

        $PLUGIN_DIR = trim(stripslashes($_REQUEST['PLUGIN_DIR']));
        $PREFIX = trim(stripslashes($_REQUEST['PREFIX']));

        $PLUGIN_DIR= trim($PLUGIN_DIR);
        if (preg_match('/[^A-Za-z0-9_-]/', $PLUGIN_DIR)) {
            header("Content-type: text/plain");
            echo 'Invalid PLUGIN_DIR';
            die();
        }

        $PREFIX = trim($PREFIX);
        if (preg_match('/[^A-Za-z0-9_]/', $PREFIX)) {
            header("Content-type: text/plain");
            echo 'Invalid PREFIX';
            die();
        }


        // create tmp dir
        $tempPath = tempnam(sys_get_temp_dir(), 'PluginTemp');
        unlink($tempPath);

        $success = mkdir($tempPath);
        if (!$success) {
            header("Content-type: text/plain");
            echo 'Failed to write to tmp dir ';
            if ($this->canUserDoRoleOption('Administrator')) {
                echo $tempPath;
            }
            die();
        }

        // Copy files
        $templateSrcDir = dirname(__FILE__) . '/template';
        shell_exec("cp -r $templateSrcDir $tempPath");
        shell_exec("mv $tempPath/template $tempPath/$PLUGIN_DIR");

        $search = array(
            'PLUGIN_NAME',
            'PLUGIN_DIR',
            'AUTHOR',
            'DESCRIPTION_SHORT',
            'TEXT_DOMAIN',
            'PREFIX',
            'LICENSE_TYPE',
            'LICENSE_TEXT',
            'LICENSE_URI',
            'LICENSE_CONDITIONAL_TYPE',
            'LICENSE_CONDITIONAL_URI',
            'COPYRIGHT_TEXT');
        $replace = array();
        $replace[] = isset($_REQUEST['PLUGIN_NAME']) ? trim(stripslashes($_REQUEST['PLUGIN_NAME'])) : '';
        $replace[] = $PLUGIN_DIR;
        $replace[] = isset($_REQUEST['AUTHOR']) ? trim(stripslashes($_REQUEST['AUTHOR'])) : '';
        $replace[] = isset($_REQUEST['DESCRIPTION_SHORT']) ? trim(stripslashes($_REQUEST['DESCRIPTION_SHORT'])) : '';
        $replace[] = isset($_REQUEST['TEXT_DOMAIN']) ? trim(stripslashes($_REQUEST['TEXT_DOMAIN'])) : '';
        $replace[] = $PREFIX;
        $LICENSE_TYPE = isset($_REQUEST['LICENSE_TYPE']) ? trim(stripslashes($_REQUEST['LICENSE_TYPE'])) : '';
        $replace[] = $LICENSE_TYPE;
        if (strpos($LICENSE_TYPE, 'BSD') === 0) {
            $replace[] = $this->getBsdLicenseText();
            $replace[] = 'http://opensource.org/licenses/BSD-3-Clause';
            $replace[] = '';
            $replace[] = '';
        }
        else {
            $replace[] = self::LICENSE_TEXT_GPL3;
            $replace[] = 'http://www.gnu.org/licenses/gpl-3.0.html';
            $replace[] = $LICENSE_TYPE;
            $replace[] = 'http://www.gnu.org/licenses/gpl-3.0.html';
        }
        $replace[] = '"WordPress Plugin Template" Copyright (C) ' . date('Y') . ' Michael Simpson  (email : michael.d.simpson@gmail.com)';

        // Rename files
        shell_exec("mv $tempPath/$PLUGIN_DIR/PLUGIN_DIR.php $tempPath/$PLUGIN_DIR/$PLUGIN_DIR.php");
        shell_exec("mv $tempPath/$PLUGIN_DIR/PLUGIN_DIR_init.php $tempPath/$PLUGIN_DIR/${PLUGIN_DIR}_init.php");

        shell_exec("mv $tempPath/$PLUGIN_DIR/PREFIX_Plugin.php $tempPath/$PLUGIN_DIR/${PREFIX}_Plugin.php");
        shell_exec("mv $tempPath/$PLUGIN_DIR/PREFIX_InstallIndicator.php $tempPath/$PLUGIN_DIR/${PREFIX}_InstallIndicator.php");
        shell_exec("mv $tempPath/$PLUGIN_DIR/PREFIX_LifeCycle.php $tempPath/$PLUGIN_DIR/${PREFIX}_LifeCycle.php");
        shell_exec("mv $tempPath/$PLUGIN_DIR/PREFIX_OptionsManager.php $tempPath/$PLUGIN_DIR/${PREFIX}_OptionsManager.php");
        shell_exec("mv $tempPath/$PLUGIN_DIR/PREFIX_ShortCodeLoader.php $tempPath/$PLUGIN_DIR/${PREFIX}_ShortCodeLoader.php");
        shell_exec("mv $tempPath/$PLUGIN_DIR/PREFIX_ShortCodeScriptLoader.php $tempPath/$PLUGIN_DIR/${PREFIX}_ShortCodeScriptLoader.php");

        // Replace Strings in files
        if ($handle = opendir("$tempPath/$PLUGIN_DIR")) {
            // Foreach file, replace tokens
            while (false !== ($file = readdir($handle))) {
                if (!is_dir("$tempPath/$PLUGIN_DIR/$file")) {
                    $fileString = file_get_contents("$tempPath/$PLUGIN_DIR/$file");
                    $fileString = str_replace($search, $replace, $fileString);
                    file_put_contents("$tempPath/$PLUGIN_DIR/$file", $fileString);
                }
            }
            closedir($handle);
        }

        // create a zip file
        //shell_exec("zip -r $tempPath/$PLUGIN_DIR.zip $tempPath/$PLUGIN_DIR");
        shell_exec("cd $tempPath; zip -r $PLUGIN_DIR.zip $PLUGIN_DIR");

        // Download it
        header('Content-type: application/zip');
        header("Content-Disposition: inline; filename=\"$PLUGIN_DIR.zip\"");
        if ($stream = fopen("$tempPath/$PLUGIN_DIR.zip", 'r')) {
            echo stream_get_contents($stream);
            fclose($stream);
        }

        // cleanup
        shell_exec("rm -rf $tempPath");

        // Log submission
        $postData = array();
        for ($idx=0; $idx<count($search); ++$idx) {
            if ('LICENSE_TEXT' != $search[$idx]) {
                $postData[$search[$idx]] = $replace[$idx];
            }
        }
        $postData['HostName'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $data = (object)array(
            'title' => 'PluginTemplate',
            'posted_data' => $postData,
            'uploaded_files' => null);
        do_action_ref_array('cfdb_submit', array(&$data));

        wp_mail('michael.d.simpson@gmail.com', 'Plugin Template Download', print_r($postData, true) . "\nhttp://plugin.michael-simpson.com/wp-admin/admin.php?page=CF7DBPluginSubmissions&form_name=PluginTemplate");

        die();
    }


    const LICENSE_TEXT_GPL3 = 'WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html';


    function getBsdLicenseText() {
        $date = date('Y');
        $organization = 'Michael Simpson';
        return "Copyright (c) $date, $organization
    All rights reserved.

    Redistribution and use in source and binary forms, with or without modification,
    are permitted provided that the following conditions are met:

    - Redistributions of source code must retain the above copyright notice, this list of conditions and the
      following disclaimer.

    - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following
      disclaimer in the documentation and/or other materials provided with the distribution.

    - Neither the name of $organization nor the names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS \"AS IS\" AND ANY EXPRESS OR IMPLIED WARRANTIES,
    INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
    SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
    SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
    WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
    OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.";
    }

}
