"use strict";

Ext.ns("TYPO3.Newsletter.Store");

TYPO3.Newsletter.Store.initListOfNewsletters = function() {
	return new Ext.data.JsonStore({
		storeId: 'listOfNewsletters',
		autoLoad: true,
		remoteSort: true,
		baseParams: {
			ajaxID: 'NewsletterController::getListOfNewsletter',
			pid: TYPO3.Devlog.Data.Parameters.pid
		},
		proxy: new Ext.data.HttpProxy({
			method: 'GET',
			url: '/typo3/ajax.php'
		}),

		listeners : {

			/**
			 * Called when store is loaded
			 *
			 * @event TYPO3.Newsletter.Store.ListOfNewsletters.afterload
			 * @param {Ext.data.JsonStore} store
			 * @param {Array} data
			 */
			load: function (store, data) {
				if (store.getCount() > 0) {
					this.fireEvent('TYPO3.Newsletter.Store.ListOfNewsletters.afterload', data);
				}
			}
		}
	});
};
