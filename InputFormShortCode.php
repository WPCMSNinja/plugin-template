<?php

require_once('PluginTemplate_ShortCodeScriptLoader.php');

class InputFormShortCode extends PluginTemplate_ShortCodeScriptLoader {

    var $actionUrl;

    /**
     * @param  $atts shortcode inputs
     * @return string shortcode content
     */
    public function handleShortcode($atts) {
        ob_start();
        $this->outputForm();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function addScript() {
        //wp_enqueue_script('PluginTemplateValidator', plugins_url('js/PluginTemplateValidator.js', __FILE__), 'jquery', false, true);
        wp_register_script('PluginTemplateValidator', plugins_url('js/PluginTemplateValidator.js', __FILE__), array('jquery'), false, true);
        wp_print_scripts('PluginTemplateValidator');
    }

    public function outputForm() {
        ?>
            <script type="text/javascript">
//                jQuery(document).ready(function() {
//                    var pt = new PluginTemplateValidator();
//                });
            </script>
    <form id="PluginTemplateForm" action="<?php echo $this->actionUrl ?>" method="post">
        <table>
            <tbody>
            <tr>
                <td><label for="PLUGIN_NAME">Plugin Name*</label></td>
                <td><input id="PLUGIN_NAME" name="PLUGIN_NAME" type="text" onchange="pluginNameChange();"/></td>
                <td>(Example: "My Cool Plugin")</td>
            </tr>
            <tr>
                <td><label for="DESCRIPTION_SHORT">Short Description</label></td>
                <td><input id="DESCRIPTION_SHORT" name="DESCRIPTION_SHORT" type="text" maxlength="150"/></td>
                <td>(Max 150 characters)</td>
            </tr>
            <tr>
                <td><label for="LICENSE_TYPE">License*</label></td>
                <td>
                    <select id="LICENSE_TYPE" name="LICENSE_TYPE">
                        <option value="GPLv3" selected>GPLv3  (Free)</option>
                        <option value="BSD 3-Clause">BSD 3-Clause  (Donate $10)</option>
                    </select>
                </td>
                <td>(see below)</td>
            </tr>
            <tr>
                <td><label for="AUTHOR">Author</label></td>
                <td><input id="AUTHOR" name="AUTHOR" type="text"/></td>
                <td>(Put just a name or an HTML A tag with link to your site)</td>
            </tr>
            <tr>
                <td><label for="PLUGIN_DIR">Plugin Dir</label></td>
                <td><input id="PLUGIN_DIR" name="PLUGIN_DIR" type="text"/></td>
                <td>(Directory name under wp-content/plugins/)</td>
            </tr>
            <tr>
                <td><label for="TEXT_DOMAIN">Text Domain</label></td>
                <td><input id="TEXT_DOMAIN" name="TEXT_DOMAIN" type="text"/></td>
                <td>(label for i18n)</td>
            </tr>
            <tr>
                <td><label for="PREFIX">PHP Class Name Prefix</label></td>
                <td><input id="PREFIX" name="PREFIX" type="text"/></td>
                <td>(like "PREFIX_Plugin.php")</td>
            </tr>
            </tbody>
        </table>
        <p>
            *<a target="_gpl3" href="http://www.opensource.org/licenses/gpl-3.0.html">GPL-3</a><br/>
            *<a target="_bsd" href="http://www.opensource.org/licenses/BSD-3-Clause">BSD</a>
        </p>


    </form>

    <table>
        <tbody>
        <tr>
            <td>
                <div id="DonateButton">
                    Suggested donation: $10
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_paypal">
                        <input type="hidden" name="cmd"
                               value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="7E97P9Z99U2AG">
                        <input type="image"
                               src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif"
                               border="0" name="submit"
                               alt="PayPal - The safer, easier way to pay online!">
                        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1"
                             height="1">
                    </form>
                </div>
            </td>
            <td>
                <button onclick="validatePluginForm()">Download</button>
            </td>
        </tr>
        </tbody>
    </table>

    <?php

    }
}