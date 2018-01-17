(function () {
    'use strict';

    Ext.ns('Ext.ux.Ecodev.Newsletter.Store');

    /**
     * A Store for the link model using ExtDirect to communicate with the
     * server side extbase framework.
     */
    Ext.ux.Ecodev.Newsletter.Store.Link = (function () {

        var linkStore = null;

        var initialize = function () {
            if (linkStore === null) {
                linkStore = new Ext.data.DirectStore({
                    storeId: 'Ecodev\\Newsletter\\Domain\\Model\\Link',
                    reader: new Ext.data.JsonReader({
                        totalProperty: 'total',
                        successProperty: 'success',
                        idProperty: '__identity',
                        root: 'data',
                        fields: [
                            {name: '__identity', type: 'int'},
                            {name: 'url', type: 'string'},
                            {name: 'openedCount', type: 'int'},
                            {name: 'openedPercentage', type: 'int'},
                        ],
                    }),
                    writer: new Ext.data.JsonWriter({
                        encode: false,
                        writeAllFields: false,
                    }),
                    api: {
                        read: Ext.ux.Ecodev.Newsletter.Remote.LinkController.listAction,
                        update: Ext.ux.Ecodev.Newsletter.Remote.LinkController.updateAction,
                        destroy: Ext.ux.Ecodev.Newsletter.Remote.LinkController.destroyAction,
                        create: Ext.ux.Ecodev.Newsletter.Remote.LinkController.createAction,
                    },
                    paramOrder: {
                        read: ['data', 'start', 'limit'],
                    },
                });
            }
        };

        /**
         * Public API of this singleton.
         */
        return {
            initialize: initialize,
        };
    }());
}());
