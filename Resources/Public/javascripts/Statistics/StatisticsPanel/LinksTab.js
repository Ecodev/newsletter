Ext.ns("TYPO3.Newsletter.Statistics.StatisticsPanel");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel.LinksTab
 * @namespace TYPO3.Newsletter.Statistics.StatisticsPanel
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel.LinksTab = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			items: [
				{
					xtype: 'button',
					text: 'asdf'
				},
				{
					xtype: 'button',
					text: 'asdf'
				},
				{
					xtype: 'button',
					text: 'asdf'
				}
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.LinksTab.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.LinksTab', TYPO3.Newsletter.Statistics.StatisticsPanel.LinksTab);