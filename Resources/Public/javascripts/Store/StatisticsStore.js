"use strict";

Ext.ns("TYPO3.Newsletter.Store");

TYPO3.Newsletter.Store.initListOfNewsletters = function() {
	return new Ext.data.JsonStore({
		storeId: 'listOfNewsletters',
		autoLoad: true,
		remoteSort: true,
		baseParams: {
//			ajaxID: 'NewsletterController::getListOfNewsletter',
			pid: TYPO3.Devlog.Data.Parameters.pid,
			M: 'web_NewsletterTxNewsletterM1',
			// Not working nested object
			'tx_newsletter_web_newslettertxnewsletterm1[controller]': 'Statistic',
			'tx_newsletter_web_newslettertxnewsletterm1[action]': 'index',
			'tx_newsletter_web_newslettertxnewsletterm1[format]': 'json'
		},
		proxy: new Ext.data.HttpProxy({
			method: 'GET',
			url: '/typo3/mod.php'
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
