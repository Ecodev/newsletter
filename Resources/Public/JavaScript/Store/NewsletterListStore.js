"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Store");

Ext.ux.TYPO3.Newsletter.Store.initNewsletterList = function() {
	return new Ext.data.JsonStore({
		storeId: 'newsletterList',
		autoLoad: true,
		remoteSort: true,
		baseParams: {
			pid: 50, // TODO: TYPO3.Devlog.Data.Parameters.pid,
			M: 'web_NewsletterTxNewsletterM1',
			'tx_newsletter_web_newslettertxnewsletterm1[controller]': 'Statistic',
			'tx_newsletter_web_newslettertxnewsletterm1[action]': 'list',
			'tx_newsletter_web_newslettertxnewsletterm1[format]': 'json',
			'tx_newsletter_web_newslettertxnewsletterm1[pid]': 50 // TODO: TYPO3.Devlog.Data.Parameters.pid
		},
		proxy: new Ext.data.HttpProxy({
			method: 'GET',
			url: '/typo3/mod.php'
		}),

		listeners : {

			/**
			 * Called after store is loaded
			 *
			 * @event Ext.ux.TYPO3.Newsletter.Store.NewsletterList.afterload
			 * @param {Ext.data.JsonStore} store
			 * @param {Array} data
			 */
			load: function (store, data) {
				if (store.getCount() > 0) {
					//this.fireEvent('Ext.ux.TYPO3.Newsletter.Store.NewsletterList.afterload', data);
				}
			}
		}
	});
};
