"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid = Ext.extend(Ext.grid.GridPanel, {

	initComponent: function() {
		var config = {
			// store
			//store: Ext.ux.TYPO3.Newsletter.Store.ClickedLink,
			store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Link'),

			// column model
			columns:[
				{
					dataIndex: 'uid',
					header: Ext.ux.TYPO3.Newsletter.Language.link_id,
					sortable: true,
					width: 40
				},
				{
					dataIndex: 'percentage_of_opened',
					header: Ext.ux.TYPO3.Newsletter.Language.percentage_of_opened,
					width: 100,
					sortable: true,
					css: 'text-align: center;',
					renderer: this._renderPercentageOfOpened
				},
				{
					dataIndex: 'opened_count',
					header: Ext.ux.TYPO3.Newsletter.Language.number_of_opened,
					width: 100,
					sortable: true,
					css: 'text-align: center;',
//					css:'background-color: #EEFFAA;border-style:solid;border-color:#0000ff;',
					renderer: this._renderNumberOfOpened
				},
				{
					dataIndex: 'url',
					header: 'URL',
					sortable: true,
					width: 600,
					renderer: this._renderUrl
				}
			],

			height: 300
		}; // eo config object

		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid.superclass.initComponent.call(this);
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
	_renderNumberOfOpened: function(value, parent, record) {
		return String.format('{0}/{1}', value, record.data['number_of_recipients']);
	},

	_renderUrl: function(value, parent, record) {
		return String.format('<a href="{0}">{0}</a>', value);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid);