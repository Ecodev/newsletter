"use strict";

Ext.ns("TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid
 * @namespace TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			width: 400,
			style: "background-color: blue",
			items: [
				{
					xtype: 'button',
					text: 'asdf'
				}
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid', TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid);