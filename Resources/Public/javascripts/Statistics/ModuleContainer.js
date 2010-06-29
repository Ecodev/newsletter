Ext.ns("TYPO3.Newsletter.Statistics");

/**
 * @class TYPO3.Newsletter.Statistics.ModuleContainer
 * @namespace TYPO3.Newsletter.Statistics
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.ModuleContainer = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			items: [
				{
					xtype: 'TYPO3.Newsletter.Statistics.NewsletterListMenu',
					ref: 'newsletterListMenu'
				},
				{
					xtype: 'TYPO3.Newsletter.Statistics.StatisticsPanel',
					ref: 'statisticsPanel'
				}
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.ModuleContainer.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.ModuleContainer', TYPO3.Newsletter.Statistics.ModuleContainer);