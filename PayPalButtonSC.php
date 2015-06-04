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

include_once('PaypalDigitalDownload_ShortCodeLoader.php');

class PayPalButtonSC extends PaypalDigitalDownload_ShortCodeLoader
{
    /**
     * @param array $atts
     * @return string
     */
    public function handleShortcode($atts) {
//        if (isset($atts['file'])) {
//            $htmlId = basename($atts['file']);
        $htmlId = 'paypal_submit';
//        $formAction = admin_url('admin-ajax.php') . '?action=' . 'pddcheckout'; // todo pddcheckout should be a constant
        $formAction = plugins_url('checkout.php', __FILE__);

            // todo: want to guarantee load scripts only once
            ob_start();
           echo <<< EOT
<form action='$formAction' METHOD='POST'>
	<input type='image' name='$htmlId' id='$htmlId' src='https://www.paypal.com/en_US/i/btn/btn_dg_pay_w_paypal.gif' border='0' align='top' alt='Pay with PayPal'/>
</form>
<script src='https://www.paypalobjects.com/js/external/dg.js' type='text/javascript'></script>
<script>
	var dg = new PAYPAL.apps.DGFlow(
	{
		trigger: '$htmlId',
		expType: 'instant'
	});
</script>
EOT;
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
//        }
//        else {
//            return "No download file given for short code";
//        }
    }


}
