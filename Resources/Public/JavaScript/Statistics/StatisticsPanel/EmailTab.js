"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab = Ext.extend(Ext.grid.GridPanel, {

	initComponent: function() {

		var config = {
			loadMask: true,
			// store
			store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Email'),

			// column model
			columns:[
				{
					dataIndex:'__identity',
					header: Ext.ux.TYPO3.Newsletter.Language.link_id,
					sortable: true,
					width: 40
				},
				{
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
					format: 'Y-m-d h:m:s',
					width: 150,
					sortable: true
				},
				{
					dataIndex: 'openTime',
					header: Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_email_open_time,
					xtype: 'datecolumn',
					format: 'Y-m-d h:m:s',
					width: 100,
					sortable: true
				},
				{
					dataIndex: 'bounceTime',
					header: Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_email_bounce_time,
					xtype: 'datecolumn',
					format: 'Y-m-d h:m:s',
					width: 100,
					sortable: true
				},
				{
					dataIndex: 'authCode',
					header: Ext.ux.TYPO3.Newsletter.Language.preview,
					width: 100,
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
		return String.format('<a href="/typo3conf/ext/newsletter/web/view.php?c={0}">view</a>', value);
	}

});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab);