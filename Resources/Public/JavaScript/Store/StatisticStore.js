"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Store");

Ext.ux.TYPO3.Newsletter.Store.initStatistic = function() {
	 return new Ext.data.JsonStore({
		storeId: 'statistic',
		autoLoad: false,
		remoteSort: true,
		baseParams: {
			pid: 50, //TODO: TYPO3.Devlog.Data.Parameters.pid,
			M: 'web_NewsletterTxNewsletterM1',
			'tx_newsletter_web_newslettertxnewsletterm1[controller]': 'Statistic',
			'tx_newsletter_web_newslettertxnewsletterm1[action]': 'show',
			'tx_newsletter_web_newslettertxnewsletterm1[format]': 'json'
		},
		proxy: new Ext.data.HttpProxy({
			method: 'GET',
			url: '/typo3/mod.php'
		}),

		listeners : {

			/**
			 * Fired when User has changed the newsletter in drop down menu.
			 * The function will load the statistics for a newsletter.
			 *
			 * @event Ext.ux.TYPO3.Newsletter.Store.Statistic.load
			 * @param {int} uid: the uid of the newsletter to load
			 */
			'Ext.ux.TYPO3.Newsletter.Store.Statistic.load': function(uid) {
				this.setBaseParam('tx_newsletter_web_newslettertxnewsletterm1[uid]', uid);
				this.load();
			}
		}
	});
};
