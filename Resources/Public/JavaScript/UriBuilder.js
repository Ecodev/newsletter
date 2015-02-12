Ext.ns('Ext.ux.Ecodev.Newsletter');
/**
 * Creates URL's to call controller/actions
 *
 * TODO: make FE url's available
 *
 * @class Ext.ux.Ecodev.Newsletter.UriBuilder
 * @singleton
 */
Ext.ux.Ecodev.Newsletter.UriBuilder = function() {
    /**
     * Private data and logic
     */
    var initialized = false;

    var pluginName = 'mainKey_subKey'; // pluginName/moduleName

    var parameterPrefix = 'tx_extensionname_modulename';

    var initialize = function(extensionName, piName) {
        pluginName = piName;
        parameterPrefix = 'tx_' + (extensionName + '').toLowerCase() + '_' + (pluginName + '').toLowerCase();
        initialized = true;
    }

    var createExtbaseUrl = function(parameters) {
        if (!initialized)
            return;
        var url = 'mod.php?';
        url += 'M=' + pluginName + '&';
        Ext.iterate(parameters, function(key, value) {
            if (Ext.isObject(value)) {
                value = value.__identity;
            }
            if (Ext.isArray(value)) {
                Ext.each(value, function(v, i) {
                    if (Ext.isObject(v)) {
                        v = v.__identity;
                    }
                    url += parameterPrefix + '[' + key + '][' + i + ']=' + v + '&';
                });
            } else {
                url += parameterPrefix + '[' + key + ']=' + value + '&';
            }
        });
        url = url.substr(0, url.length - 1);
        return url;
    };
    /**
     * Public API description.
     * We can call all the private methods here.
     */
    return Ext.apply(new Ext.util.Observable, {
        initialize: initialize,
        uriFor: function(action, controller, parameters, format) {
            actionParameters = {
                'action': action,
                'controller': controller,
                'format': format
            };
            // merge parameters
            callParameters = Ext.apply(actionParameters, parameters);
            return createExtbaseUrl(callParameters);
        }
    })
}();