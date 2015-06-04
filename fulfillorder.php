<?php

// Load WP environment
require_once('../../../wp-load.php');
require_once('PaypalDigitalDownload_Plugin.php');

/**
 * @param array $paymentInfoArray
 */
function fulfill_order($paymentInfoArray)
{
    $plugin = new PaypalDigitalDownload_Plugin;

    ?>
<html>
<body>
<p>This CFDB Editor download link will be good for 24 hours:</p>
<p><a href="<?php echo $plugin->generateDownloadLink() ?>">contact-form-to-database-extension-edit.zip</a></p>
<p>If you have any problems with the download, please contact <a href="mailto:info@cfdbplugin.com">info@cfdbplugin.com</a></p>
</body>
</html>
<?php

    try{
        $cfdb_data= (object) array(
            'title' => 'Paypal',
            'posted_data' => $paymentInfoArray,
            'uploaded_files' => null
        );
        do_action_ref_array( 'cfdb_submit', array( &$cfdb_data ) );
    }
    catch (Exception $ex) {
        error_log(sprintf('CFDB Error: %s:%s %s  %s', $ex->getFile(), $ex->getLine(), $ex->getMessage(), $ex->getTraceAsString()),
                1,
                'info@cfdbplugin.com');
    }
}
?>

