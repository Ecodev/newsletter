Ext.ns('Ext.ux.Ecodev.Newsletter');
/**
 * This class fetches Ext.Direct events, fires a event if
 * new FlashMessages are available and removes the messages from the
 * Ext.Direct response event.
 *
 * Register your FlashMessage-processing ExtJS component like this:
 * Ext.ux.Ecodev.Newsletter.DirectFlashMessages.on('new',function(flashMessages) {
 * 		//do something with incoming FlashMessages
 * });
 *
 */
Ext.ux.Ecodev.Newsletter.DirectFlashMessageDispatcher = function() {
    /**
     * @class Ext.util.Observable
     */
    var directFlashMessages = new Ext.util.Observable;
    directFlashMessages.addEvents('new');

    var initialize = function() {
        Ext.Direct.on('event', fetchRemoteMessages);
    };

    var fetchRemoteMessages = function(event, provider) {
        if (event.result && event.result.flashMessages) {
            flashMessages = event.result.flashMessages;
            delete event.result.flashMessages;
            directFlashMessages.fireEvent('new', flashMessages);
        }
    };

    return Ext.apply(directFlashMessages, {
        initialize: initialize,
        addMessage: function(message) {
            this.fireEvent('new', [message]);
        },
        addMessages: function(messages) {
            this.fireEvent('new', messages);
        }
    });
}();