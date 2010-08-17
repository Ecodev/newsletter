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
					dataIndex: 'recipient_id',
					header: TYPO3.Newsletter.Language.link_id,
					sortable: true,
					width: 40
				},
				{
					dataIndex: 'email',
					header: TYPO3.Newsletter.Language.percentage_of_opened,
					width: 200,
					sortable: true,
//					css: 'text-align: center;',
					renderer: this._renderEmail
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

});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGrid', TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGrid);