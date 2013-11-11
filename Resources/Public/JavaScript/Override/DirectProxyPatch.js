/**
 * Patch the DirectProxy to accept paramOrders for store delete, update and create requests.
 */
Ext.override(Ext.data.DirectProxy, {
    doRequest: function(action, rs, params, reader, callback, scope, options) {
        var args = [],
                directFn = this.api[action] || this.directFn;

        if (Ext.isObject(this.paramOrder)) {
            paramOrder = this.paramOrder[action];
            if (typeof paramOrder == 'string') {
                paramOrder = this.paramOrder.split(/[\s,|]/);
            }
        } else {
            paramOrder = this.paramOrder;
        }

        paramsCreateUpdateDestroy = params.jsonData;

        switch (action) {
            case Ext.data.Api.actions.create:
                if (directFn.directCfg.method.len > 0) {
                    if (paramOrder) {
                        for (var i = 0, len = paramOrder.length; i < len; i++) {
                            args.push(paramsCreateUpdateDestroy[paramOrder[i]]);
                        }
                    }
                }
                break;
            case Ext.data.Api.actions.read:
                // If the method has no parameters, ignore the paramOrder/paramsAsHash.
                if (directFn.directCfg.method.len > 0) {
                    if (paramOrder) {
                        for (var i = 0, len = paramOrder.length; i < len; i++) {
                            args.push(params[paramOrder[i]]);
                        }
                    } else if (this.paramsAsHash) {
                        args.push(params);
                    }
                }
                break;
            case Ext.data.Api.actions.update:
                if (directFn.directCfg.method.len > 0) {
                    if (paramOrder) {
                        for (var i = 0, len = paramOrder.length; i < len; i++) {
                            args.push(paramsCreateUpdateDestroy[paramOrder[i]]);
                        }
                    }
                }
                break;
            case Ext.data.Api.actions.destroy:
                if (directFn.directCfg.method.len > 0) {
                    if (paramOrder) {
                        for (var i = 0, len = paramOrder.length; i < len; i++) {
                            args.push(paramsCreateUpdateDestroy[paramOrder[i]]);
                        }
                    }
                }
                break;
        }

        var trans = {
            params: params || {},
            request: {
                callback: callback,
                scope: scope,
                arg: options
            },
            reader: reader
        };

        args.push(this.createCallback(action, rs, trans), this);
        directFn.apply(window, args);
    }
});