"use strict";

Ext.ns("TYPO3.Newsletter.Statistics");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel
 * @namespace TYPO3.Newsletter.Statistics
 * @extends Ext.TabPanel
 *
 * Class for statistic tab panel
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel = Ext.extend(Ext.TabPanel, {

	initComponent: function() {
		
		var config = {
			activeTab: 0,
//			hideBorders: true,
//			hideLabel: true,
//			bodyBorder: false,
			border: false,
			defaults: {
				autoHeight: true
			},
			items: this._getMenuItems()
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.superclass.initComponent.call(this);
	},

	/**
	 * Returns the section menu
	 *
	 * @access private
	 * @return array
	 */
	_getMenuItems: function() {
		var modules = [];

		// traverses menus
		Ext.each(TYPO3.Newsletter.Application.MenuRegistry.items.mainMenu, function(menuItem) {
			if (menuItem.itemId === 'statistics') {
				Ext.each(menuItem.items, function (subMenuItem) {
					var xtypeName = subMenuItem.itemId.slice(0,1).toUpperCase() + subMenuItem.itemId.slice(1);
					modules.push(
						{
							title: subMenuItem.title,
							items: [
								{
									xtype: 'TYPO3.Newsletter.Statistics.StatisticsPanel.' + xtypeName,
									ref: subMenuItem.itemId
								}
							]
						}
					);
				});

			}
		});
		return modules;
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel', TYPO3.Newsletter.Statistics.StatisticsPanel);