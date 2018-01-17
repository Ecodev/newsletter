(function () {
    'use strict';

    Ext.ns('Ext.ux.Ecodev.Newsletter');
    /**
     *
     *
     * @class Ext.ux.Ecodev.Newsletter.FlashMessageOverlayContainer
     * @singleton
     */
    Ext.ux.Ecodev.Newsletter.FlashMessageOverlayContainer = (function () {
        /**
         * Container in the DOM to show Messages.
         */
        var msgCt = null;
        var messageStore = new Ext.data.Store({
            reader: new Ext.data.JsonReader({
                successProperty: 'success',
                totalProperty: 'total',
                root: 'data',
                fields: [
                    {name: 'message', type: 'string'},
                    {name: 'tstamp', type: 'date', dateFormat: 'timestamp'},
                    {name: 'severity'},
                    {name: 'title', type: 'string'},
                ],
            }),
            sortInfo: {
                field: 'tstamp',
                direction: 'DESC' // or 'DESC' (case sensitive for local sorting)
            },
        });
        var TYPE_OK = 0;
        var TYPE_WARN = 1;
        var TYPE_INFO = -1;
        var TYPE_ERROR = 2;
        var TYPE_NOTICE = -2;

        var config = {
            minDelay: 3,
            maxDelay: 9,
            width: 400,
            opacity: 0.9,
            logLevel: 0,
        };

        /***
         * buildMessageBox
         */
        var buildMessageBox = function (type, title, msg) {
            var typeIcon = null;
            switch (type) {
                case TYPE_OK:
                    typeIcon = 'message-ok';
                    break;
                case TYPE_ERROR:
                    typeIcon = 'message-error';
                    break;
                case TYPE_WARN:
                    typeIcon = 'message-warning';
                    break;
                case TYPE_INFO:
                    typeIcon = 'message-notice';
                    break;
                case TYPE_NOTICE:
                /* falls through */
                default:
                    typeIcon = 'message-information';
                    break;
            }
            return [
                '<div class="flashmessage">',
                '<div class="messenge-box">',
                '<h3 class="' + typeIcon + '">',
                title,
                '</h3><p>',
                msg,
                '</p>',
                '</div>',
                '</div>',
            ].join('');
        };

        /**
         * creates a msgbox for a incoming message and shows it for while
         * based on the text length.
         */
        var showMessageBox = function (type, title, msg) {
            if (type < config.logLevel) {
                return;
            }

            var delay = config.minDelay;
            delay = msg.length / 13.3;
            if (delay < config.minDelay) {
                delay = config.minDelay;
            } else if (delay > config.maxDelay) {
                delay = config.maxDelay;
            }
            msgCt.alignTo(document, 't-tr?');
            var html = buildMessageBox(type, title, String.format.apply(String, Array.prototype.slice.call(arguments, 2)));
            Ext.DomHelper.append(msgCt, {html: html}, true).slideIn('t').pause(delay).ghost('t', {remove: true});
        };

        /***
         * Adds a message to queue.
         *
         * @param {String} message
         */
        var addMessage = function (message) {
            delete message.sessionMessage;
            var type = message.severity;
            var msg = message.message;
            var title = message.title || '&nbsp;';
            showMessageBox(type, title, msg);
            message.tstamp = new Date();
            messageStore.addSorted(new messageStore.recordType(message));
        };

        /**
         * React on incoming Messages.
         */
        var handleMessages = function (flashMessages) {
            Ext.each(flashMessages, function (message) {
                addMessage(message);
            });
        };

        /**
         * Create needed stuff in DOM and register at the FlashMessageDispatcher
         * to receive incoming Messages.
         */
        var initialize = function (incomingConfig) {
            if (incomingConfig) {
                config = Ext.applyIf(incomingConfig, config);
            }

            msgCt = Ext.DomHelper.insertFirst(document.body, {id: 'flashmessages-div'}, true);
            msgCt.setStyle('position', 'absolute');
            msgCt.setStyle('z-index', 9999);
            msgCt.setWidth(config.width);
            msgCt.setOpacity(config.opacity);
            Ext.ux.Ecodev.Newsletter.DirectFlashMessageDispatcher.on('new', handleMessages);
        };

        var getMessageGrid = function () {
            var grid = new Ext.grid.GridPanel({
                store: messageStore,
                title: 'Nachrichten-Chronik',
                closable: true,
                colModel: new Ext.grid.ColumnModel({
                    columns: [
                        {header: 'Nachricht', sortable: true, dataIndex: 'message'},
                        {header: 'Typ', sortable: true, dataIndex: 'type'},
                        {header: 'Zeitpunkt', xtype: 'datecolumn', format: 'H:i:s', sortable: true, dataIndex: 'tstamp'},
                    ],
                    defaults: {
                        sortable: true,
                    },
                }),
                viewConfig: {
                    forceFit: true,
                },
            });
            return grid;
        };

        /**
         * Public API stuff is located here.
         */
        return Ext.apply(new Ext.util.Observable(), {
            initialize: initialize,
            addMessage: addMessage,
            getMessageGrid: getMessageGrid,
        });
    }());
}());
