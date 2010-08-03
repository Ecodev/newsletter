"use strict";

Ext.ns("TYPO3.Newsletter.Statistics");

/**
 * @class TYPO3.Newsletter.Statistics.Bootstrap
 * @namespace TYPO3.Newsletter.Statistics
 * @extends TYPO3.Newsletter.Application.AbstractBootstrap
 *
 * Bootrap module statistics
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.Bootstrap = Ext.apply(new TYPO3.Newsletter.Application.AbstractBootstrap(), {
	initialize: function() {

		// xtypeName will be as follows: TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
		this.addToMenu(['mainMenu', 'statistics'], [
			{
				title: TYPO3.Newsletter.Language.overview_tab,
				itemId: 'overviewTab'
			},
			{
				title: TYPO3.Newsletter.Language.links_tab,
				itemId: 'linksTab'
			},
			{
				title: TYPO3.Newsletter.Language.emails_tab,
				itemId: 'emailsTab'
			}
		]);

		/**
		 * Handle a navigation token.
		 *
		 * @param {RegExp} regexp the callback is called if the regexp matches
		 * @param {function} callback Callback to be called
		 * @param scope
		 */
		this.handleNavigationToken(/statistics/, function(e) {
			var component = TYPO3.Newsletter.UserInterface.contentArea.statistics || null;
			if (!component) {
				component = Ext.ComponentMgr.create({
					xtype: 'TYPO3.Newsletter.Statistics.ModuleContainer',
					ref: 'statistics'
				});
				
				TYPO3.Newsletter.UserInterface.contentArea.add(component);
				TYPO3.Newsletter.UserInterface.contentArea.doLayout();
			}

			// Shows up the latter panel
			component.setVisible(true);
		});
	}
});

TYPO3.Newsletter.Application.registerBootstrap(TYPO3.Newsletter.Statistics.Bootstrap);