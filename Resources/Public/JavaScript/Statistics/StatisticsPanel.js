"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics
 * @extends Ext.TabPanel
 *
 * Class for statistic tab panel
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel = Ext.extend(Ext.TabPanel, {

	initComponent: function() {
		
		var config = {
			activeTab: 0,
			border: false,
			items: this._getMenuItems()
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.superclass.initComponent.call(this);
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
		Ext.each(Ext.ux.TYPO3.Newsletter.Module.MenuRegistry.items.mainMenu, function(menuItem) {
			if (menuItem.itemId === 'statistics') {
				Ext.each(menuItem.items, function (subMenuItem) {
					var xtypeName = subMenuItem.itemId.slice(0,1).toUpperCase() + subMenuItem.itemId.slice(1);
					modules.push(
						{
							title: subMenuItem.title,
							items: [
								{
									xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.' + xtypeName,
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

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel);