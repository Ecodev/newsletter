"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.Statistics
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics
 * @extends Ext.Container
 *
 * Class for statistic container
 */
Ext.ux.TYPO3.Newsletter.Statistics.Statistics = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			layout: 'border',
			title: Ext.ux.TYPO3.Newsletter.Language.statistics_tab,
			items: [
			{
				split: true,
				region: 'north',
				xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.NewsletterListMenu',
				ref: 'newsletterListMenu'
			},
			{
				region: 'center',
				xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel',
				ref: 'statisticsPanel'
			}
			]
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.Statistics.superclass.initComponent.call(this);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.Statistics', Ext.ux.TYPO3.Newsletter.Statistics.Statistics);