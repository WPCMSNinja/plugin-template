<?php
/*
    "PayPal Digital Download" Copyright (C) 2013 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This following part of this file is part of PayPal Digital Download for WordPress.

    PayPal Digital Download is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    PayPal Digital Download is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

include_once('PaypalDigitalDownload_LifeCycle.php');

class PaypalDigitalDownload_Plugin extends PaypalDigitalDownload_LifeCycle
{

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData()
    {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array( //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
        );
    }

    public function getPluginDisplayName()
    {
        return 'PayPal Digital Download';
    }

    protected function getMainPluginFileName()
    {
        return 'paypal-digital-download.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables()
    {
        global $wpdb;
        $tableName = $this->prefixTableName('downloads');
        $sql =
            'CREATE TABLE IF NOT EXISTS ' . $tableName . ' ('
                . ' id int NOT NULL AUTO_INCREMENT,'
                . ' product varchar(64),'
                . ' code varchar(256),'
                . ' expire datetime not null,'
                . ' PRIMARY KEY(id))';
        $wpdb->query($sql);
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables()
    {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('downloads');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade()
    {
    }


    public function addActionsAndFilters()
    {

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
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39
        require_once('PayPalButtonSC.php');
        $sc = new PayPalButtonSC();
        $sc->register('pp-digital-download');

        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41
//        add_action('wp_ajax_pddcheckout', array(&$this, 'ajaxCheckout'));
//        add_action('wp_ajax_nopriv_pddcheckout', array(&$this, 'ajaxCheckout'));

        add_action('wp_ajax_pddorderconfirm', array(&$this, 'ajaxOrderConfirm'));
        add_action('wp_ajax_nopriv_pddorderconfirm', array(&$this, 'ajaxOrderConfirm'));

        add_action('wp_ajax_pdddownload', array(&$this, 'ajaxDownload'));
        add_action('wp_ajax_nopriv_pdddownload', array(&$this, 'ajaxDownload'));


//        add_action('wp_ajax_pddcancel', array(&$this, 'ajaxCancel'));
//        add_action('wp_ajax_nopriv_pddcancel', array(&$this, 'ajaxCancel'));

    }


    public function generateCode()
    {
        return md5(uniqid(rand(), true));
    }

    public function generateUrl($code)
    {
        return admin_url('admin-ajax.php') . '?action=pdddownload&c=' . $code;
    }

    public function saveCodeToDatabase($product, $code)
    {
        global $wpdb;
        $tableName = $this->getTableName();
        $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO $tableName( product, code, expire ) VALUES (%s,  %s, NOW( ) + INTERVAL 1 DAY)",
                $product, $code));
    }

    public function generateDownloadLink()
    {
        $code = $this->generateCode();
        $this->saveCodeToDatabase('CFDB Editor', $code); // todo hardcoded
        return $this->generateUrl($code);
    }


//    public function ajaxCheckout()
//    {
//        die();
//    }

//    public function ajaxCancel()
//    {
//        die();
//    }

    public function ajaxOrderConfirm()
    {
        include("orderconfirm.php");
        die();
    }


    public function ajaxDownload()
    {
        // Don't let IE cache this request
        header("Pragma: no-cache");
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");

        if (isset($_REQUEST['c'])) {
            global $wpdb;
            $tableName = $this->getTableName();
            $rows =
                $wpdb->get_var(
                    $wpdb->prepare(
                        "select count(code) from $tableName where code = %s and expire >= NOW()", $_REQUEST['c']));
            if ($rows > 0) {
                header('Content-type: application/zip');
                header('Content-Disposition: inline; filename="contact-form-to-database-extension-edit.zip"'); // todo hardcoded
                if ($stream = fopen('/kunden/homepages/17/d274877261/htdocs/downloads/contact-form-to-database-extension-edit.zip', 'r')) {
                    echo stream_get_contents($stream);
                    fclose($stream);
                }
            } else {
                header('Content-type: text/html');
                //print_r($rows); // debug
                ?>
            <p>Download link is not valid</p>
            <p><a href="http://cfdbplugin.com/?page_id=939">Get a new link</a></p>
            <p>If you have any problems with the download, please contact <a
                    href="mailto:info@cfdbplugin.com">info@cfdbplugin.com</a></p>
            <?php
            }
        } else {
            header('Content-type: text/html');
            ?>
        <p>Download link is not valid</p>
        <p><a href="http://cfdbplugin.com/?page_id=939">Get a new link</a></p>
        <p>If you have any problems with the download, please contact <a href="mailto:info@cfdbplugin.com">info@cfdbplugin.com</a>
        </p>
        <?php
        }
        die();
    }


    public function getTableName() {
        return $this->prefixTableName('downloads');
    }

    public function settingsPage() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'paypal-digital-download'));
        }
        ?>
        <p>This CFDB Editor download link will be good for 24 hours:</p>
        <p><a href="<?php echo $this->generateDownloadLink() ?>">contact-form-to-database-extension-edit.zip</a></p>
        <p>If you have any problems with the download, please contact <a href="mailto:cfdbplugin.com">info@cfdbplugin.com</a></p>
        <?php
    }


}
