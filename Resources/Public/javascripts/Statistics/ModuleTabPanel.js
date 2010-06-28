Ext.ns("TYPO3.Newsletter.Statistics");

/**
 * @class TYPO3.Newsletter.Statistics.ModuleTabPanel
 * @namespace TYPO3.Newsletter.Statistics
 * @extends Ext.TabPanel
 *
 * Class for statistic tab panel
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.ModuleTabPanel = Ext.extend(Ext.TabPanel, {

	initComponent: function() {
		var config = {
			activeTab: 0,
//			hideBorders: true,
//			hideLabel: true,
//			bodyBorder: false,
			border: false,
			items: [
				{
					title: TYPO3.Newsletter.Language.overview_panel,
					html: 'A simple tab'
				},
				{
					title: TYPO3.Newsletter.Language.clickedlinks_panel,
					html: 'Another one'
				},
				{
					title: TYPO3.Newsletter.Language.sentemails_panel,
					html: 'Another one'
				}
			]

		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.ModuleTabPanel.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.ModuleTabPanel', TYPO3.Newsletter.Statistics.ModuleTabPanel);