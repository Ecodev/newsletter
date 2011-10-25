"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab = Ext.extend(Ext.grid.GridPanel, {

	initComponent: function() {
		var config = {
			loadMask: true,
			
			// store
			store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Link'),

			// column model
			columns:[
				{
					dataIndex: '__identity',
					header: Ext.ux.TYPO3.Newsletter.Language.link_id,
					sortable: true,
					width: 40
				},
				{
					dataIndex: 'openedPercentage',
					header: Ext.ux.TYPO3.Newsletter.Language.percentage_of_opened,
					width: 100,
					sortable: true,
					css: 'text-align: center;',
					renderer: this._renderPercentageOfOpened
				},
				{
					dataIndex: 'openedCount',
					header: Ext.ux.TYPO3.Newsletter.Language.number_of_opened,
					width: 100,
					sortable: true,
					css: 'text-align: center;'
				},
				{
					dataIndex: 'url',
					header: 'URL',
					sortable: true,
					width: 600,
					renderer: this._renderUrl
				}
			]
		}; // eo config object

		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.superclass.initComponent.call(this);
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
	_renderPercentageOfOpened: function(value, parent, record) {
		return String.format('{0}%', value);
	},

	_renderUrl: function(value, parent, record) {
		return String.format('<a href="{0}">{0}</a>', value);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab);