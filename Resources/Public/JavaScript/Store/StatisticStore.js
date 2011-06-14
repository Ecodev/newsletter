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
			 * Fired after the store is loaded.
			 * The event's name is a bit missleading as the event is fired after loading.
			 *
			 * @event load
			 * @param {Ext.data.JsonStore} store
			 * @param {Array} data
			 */
			load: function (store, data) {
				if (store.getCount() > 0) {
					this.fireEvent('Ext.ux.TYPO3.Newsletter.Store.Statistic.afterload', data);
					Ext.ux.TYPO3.Newsletter.Module.Application.fireEvent('Ext.ux.TYPO3.Newsletter.Module.afterbusy');
				}
			},

			/**
			 * Fired when User has changed the newsletter in drop down menu.
			 * The function will load the statistics for a newsletter.
			 *
			 * @event Ext.ux.TYPO3.Newsletter.Store.Statistic.load
			 * @param {int} uid: the uid of the newsletter to load
			 */
			'Ext.ux.TYPO3.Newsletter.Store.Statistic.load': function(uid) {
				Ext.ux.TYPO3.Newsletter.Module.Application.fireEvent('Ext.ux.TYPO3.Newsletter.Module.busy');
				this.setBaseParam('tx_newsletter_web_newslettertxnewsletterm1[uid]', uid);
				this.load();
			}
		}
	});
};
