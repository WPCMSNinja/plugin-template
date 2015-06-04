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

        <style type="text/css">
            .button {
                display: inline-block;
                text-align: center;
                vertical-align: middle;
                padding: 12px 24px;
                border: 1px solid #a12727;
                border-radius: 8px;
                background: #ff4a4a;
                background: -webkit-gradient(linear, left top, left bottom, from(#ff4a4a), to(#992727));
                background: -moz-linear-gradient(top, #ff4a4a, #992727);
                background: linear-gradient(to bottom, #ff4a4a, #992727);
                text-shadow: #591717 1px 1px 1px;
                font: normal normal bold 20px arial;
                color: #ffffff;
                text-decoration: none;
            }
            .button:hover,
            .button:focus {
                background: #ff5959;
                background: -webkit-gradient(linear, left top, left bottom, from(#ff5959), to(#b62f2f));
                background: -moz-linear-gradient(top, #ff5959, #b62f2f);
                background: linear-gradient(to bottom, #ff5959, #b62f2f);
                color: #ffffff;
                text-decoration: none;
            }
            .button:active {
                background: #982727;
                background: -webkit-gradient(linear, left top, left bottom, from(#982727), to(#982727));
                background: -moz-linear-gradient(top, #982727, #982727);
                background: linear-gradient(to bottom, #982727, #982727);
            }
            .button:before{
                content:  "\0000a0";
                display: inline-block;
                height: 24px;
                width: 24px;
                line-height: 24px;
                margin: 0 4px -6px -4px;
                position: relative;
                top: 0px;
                left: 0px;
                background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAABrUlEQVRIibWWv0oDQRDGfzmDZSpJISLhvCr4BBJEySukDD5GSBXIq0QsAvZyRrER8QW0MgbEKl3qcGixs9zc3t3eKfrBwmTnz+582Zm5Bn5EwADoA8fAnuyvgVfgHrgG3iri5BACc2ALfFWsrdiGdYMPgU2NwO7aiK8XYyBRTglwI44hsCsrlL24wH7ku7k2fgFOlP4MmAITkS16YqsPyWUSkqXlAWg5NlOlnzq6lvhoujL/ydy5uRscubm1mRToW04mc6uISF9LQpaWuhlY9Ehp3gJRgHnnTTG4BZ5KnOvgEbgTuQkMAkwRWVx5nJMS2cWlkvsBpkItfLffKZFdPCu5G5CWP8Cnx7EuPpTcDn7g6KOlFE1M49qX3wfAu8gXwJEKfK78TjEvKcHQtQRmojtUdmuABenz0xUYAivKe49dK7JFNVS6BZj+YzdiJ8OO3K4s+FJsNGKlH0N1oXVKMlkVBM8VmlVUtQqXLpcW8LQKG6Cq2XUwlBTRUtnsoLhd9woOcYPXatcWI/IDJBaHiHTgRPxi4OhM/m1kWvzZ0G9UHKQ/W7pAW/Zrf7Z8A9NN2vCvMa7nAAAAAElFTkSuQmCC") no-repeat left center transparent;
                background-size: 100% 100%;
            }
        </style>
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
                        <option value="GPLv3" selected>GPLv3</option>
                        <option value="BSD 3-Clause">BSD 3-Clause</option>
                    </select>
                </td>
                <td><a target="_gpl3" href="http://www.opensource.org/licenses/gpl-3.0.html">GPL-3</a>,
                    <a target="_bsd" href="http://www.opensource.org/licenses/BSD-3-Clause">BSD</a>
                </td>
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

    </form>

    <table>
        <tbody>
        <tr>
            <td>
                <div id="DonateButton">
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
                <a class="button" onclick="validatePluginForm()">Download</a>
            </td>
            <td>
                <iframe src="https://ghbtns.com/github-btn.html?user=mdsimpson&repo=plugin-template&type=fork&count=false&size=large" frameborder="0" scrolling="0" width="158px" height="30px"></iframe>
            </td>
        </tr>
        </tbody>
    </table>

    <?php

    }
}