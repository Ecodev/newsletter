"use strict";

Ext.ns("TYPO3.Newsletter.Store");

TYPO3.Newsletter.Store.initNewsletterList = function() {
	return new Ext.data.JsonStore({
		storeId: 'newsletterList',
		autoLoad: true,
		remoteSort: true,
		baseParams: {
			pid: TYPO3.Devlog.Data.Parameters.pid,
			M: 'web_NewsletterTxNewsletterM1',
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
			 * @event TYPO3.Newsletter.Store.NewsletterList.afterload
			 * @param {Ext.data.JsonStore} store
			 * @param {Array} data
			 */
			load: function (store, data) {
				if (store.getCount() > 0) {
					this.fireEvent('TYPO3.Newsletter.Store.NewsletterList.afterload', data);
				}
			}
		}
	});
};
