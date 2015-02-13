(function() {
    "use strict";

    Ext.ns('Ext.ux.Ecodev.Newsletter.Store');

    /**
     * A Store for the recipientList model using ExtDirect to communicate with the
     * server side extbase framework.
     */
    Ext.ux.Ecodev.Newsletter.Store.RecipientList = (function() {

        var recipientListStore = null;

        var initialize = function() {
            if (recipientListStore === null) {
                recipientListStore = new Ext.data.DirectStore({
                    storeId: 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList',
                    reader: new Ext.data.JsonReader({
                        totalProperty: 'total',
                        successProperty: 'success',
                        idProperty: '__identity',
                        root: 'data',
                        fields: [
                            {name: '__identity', type: 'int'},
                            {name: 'title', type: 'string'},
                            {name: 'plainOnly', type: 'boolean'},
                            {name: 'lang', type: 'string'},
                            {name: 'type', type: 'string'},
                            {name: 'count', type: 'integer'},
                            {name: 'fullName', convert: function(v, recipientList) {
                                    return String.format('{0} ({1})', recipientList.title, recipientList.count);
                                }}
                        ]
                    }),
                    writer: new Ext.data.JsonWriter({
                        encode: false,
                        writeAllFields: false
                    }),
                    api: {
                        read: Ext.ux.Ecodev.Newsletter.Remote.RecipientListController.listAction,
                        update: Ext.ux.Ecodev.Newsletter.Remote.RecipientListController.updateAction,
                        destroy: Ext.ux.Ecodev.Newsletter.Remote.RecipientListController.destroyAction,
                        create: Ext.ux.Ecodev.Newsletter.Remote.RecipientListController.createAction
                    },
                    paramOrder: {
                        read: [],
                        update: ['data'],
                        create: ['data'],
                        destroy: ['data']
                    },
                    autoLoad: true

                });
            }
        };
        /**
         * Public API of this singleton.
         */
        return {
            initialize: initialize
        };
    }());
}());
