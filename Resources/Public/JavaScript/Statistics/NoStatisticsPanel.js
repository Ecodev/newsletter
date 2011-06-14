"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.NoStatisticsPanel
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics
 * @extends Ext.TabPanel
 *
 * Class for statistic tab panel
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.NoStatisticsPanel = Ext.extend(Ext.BoxComponent, {

	initComponent: function() {
		var config = {
			autoEl: {
				tag: 'div',
				html: '<p>' + Ext.ux.TYPO3.Newsletter.Language.no_statistics + '</p>' +
					'<span class="t3-newsletter-img-no-statistics" alt=""/>',
				cls: 't3-newsletter-div-no-statistics t3-newsletter-hidden'
			}
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.NoStatisticsPanel.superclass.initComponent.call(this);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.NoStatisticsPanel', Ext.ux.TYPO3.Newsletter.Statistics.NoStatisticsPanel);