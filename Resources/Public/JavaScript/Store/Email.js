"use strict";

Ext.ns('Ext.ux.Ecodev.Newsletter.Store');

/**
 * A Store for the email model using ExtDirect to communicate with the
 * server side extbase framework.
 */
Ext.ux.Ecodev.Newsletter.Store.Email = function() {

    var emailStore = null;

    var initialize = function() {
        if (emailStore == null) {
            emailStore = new Ext.data.DirectStore({
                storeId: 'Ecodev\\Newsletter\\Domain\\Model\\Email',
                reader: new Ext.data.JsonReader({
                    totalProperty: 'total',
                    successProperty: 'success',
                    idProperty: '__identity',
                    root: 'data',
                    fields: [
                        {name: '__identity', type: 'int'},
                        {name: 'recipientAddress', type: 'string'},
                        {name: 'beginTime', type: 'date'},
                        {name: 'endTime', type: 'date'},
                        {name: 'openTime', type: 'date'},
                        {name: 'bounceTime', type: 'date'},
                        {name: 'authCode', type: 'string'},
                        {name: 'recipientAddress', type: 'string'},
                        {name: 'unsubscribed', type: 'boolean'}
                    ]
                }),
                writer: new Ext.data.JsonWriter({
                    encode: false,
                    writeAllFields: false
                }),
                api: {
                    read: Ext.ux.Ecodev.Newsletter.Remote.EmailController.listAction,
                    update: Ext.ux.Ecodev.Newsletter.Remote.EmailController.updateAction,
                    destroy: Ext.ux.Ecodev.Newsletter.Remote.EmailController.destroyAction,
                    create: Ext.ux.Ecodev.Newsletter.Remote.EmailController.createAction
                },
                paramOrder: {
                    read: ['data', 'start', 'limit']
                }
            });
        }
    }
    /**
     * Public API of this singleton.
     */
    return {
        initialize: initialize
    }
}();
