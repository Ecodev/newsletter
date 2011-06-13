"use strict";

Ext.ns("TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGrid
 * @namespace TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGrid = Ext.extend(Ext.grid.GridPanel, {

	initComponent: function() {

		var config = {
			// store
			store: TYPO3.Newsletter.Store.SentEmail,

			// column model
			columns:[
				{
					dataIndex: 'uid',
					header: TYPO3.Newsletter.Language.link_id,
					sortable: true,
					width: 40
				},
				{
					dataIndex: 'recipient_address',
					header: TYPO3.Newsletter.Language.recipients,
					width: 300,
					sortable: true,
					renderer: this._renderEmail
				},
				{
					dataIndex: 'opened',
					header: TYPO3.Newsletter.Language.opened,
					width: 100,
					sortable: true
				},
				{
					dataIndex: 'bounced',
					header: TYPO3.Newsletter.Language.bounced,
					width: 100,
					sortable: true
				},
				{
					dataIndex: 'preview',
					header: TYPO3.Newsletter.Language.preview,
					width: 100,
					sortable: true,
					renderer: this._renderPreview
				},
			],

			height: 300,
			width: 700
		};

		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGrid.superclass.initComponent.call(this);
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

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGrid', TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGrid);