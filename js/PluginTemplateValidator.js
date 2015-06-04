function pluginNameChange() {
    var aVal = jQuery('#PLUGIN_NAME').val();
    aVal = aVal.toLowerCase();
    aVal = aVal.replace(/[^a-z0-9 _-]/g, "");
    aVal = aVal.trim();

    var dir = aVal.replace(/ /g, "-");
    jQuery('#PLUGIN_DIR,#TEXT_DOMAIN').val(dir);

    //http://www.mediacollege.com/internet/javascript/text/case-capitalize.html
    var prefix = aVal.replace(/(^|\s)([a-z])/g,
                              function(m, p1, p2) {
                                  return p1 + p2.toUpperCase();
                              });
    prefix = prefix.replace(/ /g, "");
    jQuery('#PREFIX').val(prefix);

}

function validatePluginForm() {

    if ("" == jQuery('#PLUGIN_NAME').val()) {
        alert("Plugin Name not set");
        jQuery('#PLUGIN_NAME').focus();
        return;
    }

    if ("" == jQuery('#PLUGIN_DIR').val()) {
        alert("Plugin Dir not set");
        jQuery('#PLUGIN_DIR').focus();
        return;
    }

    if ("" == jQuery('#PREFIX').val()) {
        alert("PHP Class Name Prefix not set");
        jQuery('#PREFIX').focus();
        return;
    }

    if ("BSD" == jQuery('#LICENSE_TYPE').val()) {
        if (!confirm("Please be sure to donate $10 for the BSD-licensed version.")) {
            return;
        }
    }
    jQuery('#PluginTemplateForm').submit();
}