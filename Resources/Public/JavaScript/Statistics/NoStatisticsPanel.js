"use strict";

Ext.ns("TYPO3.Newsletter.Statistics");

/**
 * @class TYPO3.Newsletter.Statistics.NoStatisticsPanel
 * @namespace TYPO3.Newsletter.Statistics
 * @extends Ext.TabPanel
 *
 * Class for statistic tab panel
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.NoStatisticsPanel = Ext.extend(Ext.BoxComponent, {

	initComponent: function() {
		var config = {
			autoEl: {
				tag: 'div',
				html: TYPO3.Newsletter.Language.no_statistics +
					'<br />\n\
					<img src="' + TYPO3.Newsletter.Data.imagePath + 'cog.png" class="t3-newsletter-img-no-statistics" alt=""/>',
				
				cls: 't3-newsletter-div-no-statistics t3-newsletter-hidden'
			}
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.NoStatisticsPanel.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.NoStatisticsPanel', TYPO3.Newsletter.Statistics.NoStatisticsPanel);