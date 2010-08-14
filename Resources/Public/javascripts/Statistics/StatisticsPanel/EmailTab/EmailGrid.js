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
			store: TYPO3.Newsletter.Store.EmailSent,

			// column model
			columns:[
				{
					dataIndex: 'link_id',
					header: TYPO3.Newsletter.Language.link_id,
					sortable: true,
					width: 40
				},
//				{
//					dataIndex: 'percentage_of_opened',
//					header: TYPO3.Newsletter.Language.percentage_of_opened,
//					width: 100,
//					sortable: true,
//					css: 'text-align: center;',
//					renderer: this._renderPercentageOfOpened
//				},
//				{
//					dataIndex: 'number_of_opened',
//					header: TYPO3.Newsletter.Language.number_of_opened,
//					width: 100,
//					sortable: true,
//					css: 'text-align: center;',
////					css:'background-color: #EEFFAA;border-style:solid;border-color:#0000ff;',
//					renderer: this._renderNumberOfOpened
//				},
//				{
//					dataIndex: 'url',
//					header: 'URL',
//					width: 600
//				}
			],

			height: 300
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
		return String.format('{0}/{1}', value, record.data['total_number_of_opened']);
	}

});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGrid', TYPO3.Newsletter.Statistics.StatisticsPanel.EmailTab.EmailGrid);