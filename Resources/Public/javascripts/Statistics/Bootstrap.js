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

	// Properties
	moduleName: 'statistics',
	
	initialize: function() {

		// xtypeName will be as follows: TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
		this.addToMenu(['mainMenu', this.moduleName], [
			{
				title: TYPO3.Newsletter.Language.overview_tab,
				itemId: 'overviewTab'
			},
			{
				title: TYPO3.Newsletter.Language.links_tab,
				itemId: 'linkTab'
			},
			{
				title: TYPO3.Newsletter.Language.emails_tab,
				itemId: 'emailTab'
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
					ref: this.moduleName
				});
				
				TYPO3.Newsletter.UserInterface.contentArea.add(component);
				TYPO3.Newsletter.UserInterface.contentArea.doLayout();
			}

			// Defines the current module as loaded
			this.getMenuItem(this.moduleName).isLoaded = true;

			// Shows up the panel
			component.setVisible(true);

			// Check wheter there are staticis for the page.
			// If not load a special panel for that case.
			if (TYPO3.Newsletter.Data.numberOfStatistics == 0) {
				this._loadNoStatisticsPanel();
			}
			else {
				this._loadStatisticsInCaseNotAlreadyLoaded();
			}

			
		});
	},

	/**
	 * Loade the no statistics panel and hide not wanted components
	 *
	 * @return void
	 */
	_loadNoStatisticsPanel: function() {
		TYPO3.Newsletter.Application.fireEvent('TYPO3.Newsletter.Application.afterbusy');
		TYPO3.Newsletter.UserInterface.contentArea.statistics.noStatisticsPanel.removeClass('t3-newsletter-hidden');
		TYPO3.Newsletter.UserInterface.contentArea.statistics.statisticsPanel.hide();
		TYPO3.Newsletter.UserInterface.contentArea.statistics.newsletterListMenu.hide();
	},

	/**
	 * Loads the first newsletter's statistics when clicking on the module button
	 * This will prevent a bug when the page was not loaded firstly (not anchor #statistics)
	 *
	 * @return void
	 */
	_loadStatisticsInCaseNotAlreadyLoaded: function() {
		var menu, store;
		menu = TYPO3.Newsletter.UserInterface.contentArea.statistics.newsletterListMenu;
		store = TYPO3.Newsletter.Store.NewsletterList;
		if (menu.getValue() == '' && store.getAt(0)) {
			menu.setValue(store.getAt(0).json.uid);
			menu.fireEvent('select');
		}
	}
});

TYPO3.Newsletter.Application.registerBootstrap(TYPO3.Newsletter.Statistics.Bootstrap);