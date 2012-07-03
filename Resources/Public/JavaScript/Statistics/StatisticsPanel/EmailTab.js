"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel
 * @extends Ext.Container
 *
 * Class for statistic container
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab = Ext.extend(Ext.grid.GridPanel, {

	initComponent: function() {

		var config = {
			loadMask: true,
			autoExpandColumn: 'recipientAddress',
			// store
			store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Email'),

			// paging bar on the bottom
			bbar: new Ext.PagingToolbar({
				pageSize: 50,
				store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Email'),
				displayInfo: true,
				listeners: {

					// Before we change page, we inject the currently selected newsletter as params for Ajax request
					beforechange: function(pagingToolbar, params) {
						var selectedNewsletterStore = Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_SelectedNewsletter');
						var newsletter = selectedNewsletterStore.getAt(0);
						params.data = newsletter.data.__identity;
					}
				}
			}),

			// column model
			columns:[
				{
					dataIndex:'__identity',
					header: Ext.ux.TYPO3.Newsletter.Language.link_id,
					sortable: true,
					width: 40
				},
				{
					id: 'recipientAddress',
					dataIndex: 'recipientAddress',
					header: Ext.ux.TYPO3.Newsletter.Language.recipients,
					width: 300,
					sortable: true,
					renderer: this._renderEmail
				},
				{
					dataIndex: 'endTime',
					header: Ext.ux.TYPO3.Newsletter.Language.sent,
					xtype: 'datecolumn',
					format: 'Y-m-d H:i:s',
					width: 150,
					sortable: true
				},
				{
					dataIndex: 'openTime',
					header: Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_email_open_time,
					xtype: 'datecolumn',
					format: 'Y-m-d H:i:s',
					width: 150,
					sortable: true
				},
				{
					dataIndex: 'bounceTime',
					header: Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_email_bounce_time,
					xtype: 'datecolumn',
					format: 'Y-m-d H:i:s',
					width: 150,
					sortable: true
				},
				{
					dataIndex: 'unsubscribed',
					header: Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_email_unsubscribed,
					width: 100,
					sortable: true,
					renderer: function(value){ return value ? '✔' : ''; }
				},
				{
					dataIndex: 'authCode',
					header: Ext.ux.TYPO3.Newsletter.Language.view,
					width: 70,
					sortable: true,
					renderer: this._renderPreview
				},
			]
		};

		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.superclass.initComponent.call(this);
	},

	/**
	 * Renders the "called from" column
	 *
	 * @access private
	 * @method _renderPercentageOfOpened
	 * @param {string} value
	 * @param {Object} parent
	 * @param {Object} record
	 * @return string
	 */
	_renderEmail: function(value, parent, record) {
		return String.format('<a href="mailto:{0}">{0}</a>', value);
	},

	_renderPreview: function(value, parent, record) {
		return String.format('<a href="/typo3conf/ext/newsletter/web/view.php?injectOpenSpy=0&injectLinksSpy=0&c={0}">{1}</a>', value, Ext.ux.TYPO3.Newsletter.Language.view);
	}

});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab);
