"use strict";

Ext.ns("TYPO3.Newsletter.Store");

TYPO3.Newsletter.Store.initStatistic = function() {
	 return new Ext.data.JsonStore({
		storeId: 'statistic',
		autoLoad: false,
		remoteSort: true,
		baseParams: {
			pid: TYPO3.Devlog.Data.Parameters.pid,
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
			 * Fired after the store is loaded.
			 * The event's name is a bit missleading as the event is fired after load.
			 *
			 * @event load
			 * @param {Ext.data.JsonStore} store
			 * @param {Array} data
			 */
			load: function (store, data) {
				if (store.getCount() > 0) {
					this.fireEvent('TYPO3.Newsletter.Store.Statistic.afterload', data);
					TYPO3.Newsletter.Application.fireEvent('TYPO3.Newsletter.Application.afterbusy');
				}
			},

			/**
			 * Fired when User has changed the newsletter in drop down menu.
			 * The function will load the statistics for a newsletter.
			 *
			 * @event TYPO3.Newsletter.Store.Statistic.beforeload
			 * @param {int} uid: the uid of the newsletter to load
			 */
			'TYPO3.Newsletter.Store.Statistic.beforeload': function(uid) {
				TYPO3.Newsletter.Application.fireEvent('TYPO3.Newsletter.Application.beforebusy');
				this.setBaseParam('tx_newsletter_web_newslettertxnewsletterm1[uid]', uid);
				this.load();
			}
		}
	});
};
