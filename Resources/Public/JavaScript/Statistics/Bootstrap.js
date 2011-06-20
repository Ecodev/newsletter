"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.Bootstrap
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics
 * @extends Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap
 *
 * Bootrap module statistics
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.Bootstrap = Ext.apply(new Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap(), {

	// Properties
	moduleName: 'statistics',
	
	initialize: function() {

		// xtypeName will be as follows: Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
		this.addToMenu(['mainMenu', this.moduleName], [
			{
				title: Ext.ux.TYPO3.Newsletter.Language.overview_tab,
				itemId: 'overviewTab'
			},
			{
				title: Ext.ux.TYPO3.Newsletter.Language.links_tab,
				itemId: 'linkTab'
			},
			{
				title: Ext.ux.TYPO3.Newsletter.Language.emails_tab,
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
			var component = Ext.ux.TYPO3.Newsletter.Module.contentArea.statistics || null;
			if (!component) {
				component = Ext.ComponentMgr.create({
					xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.ModuleContainer',
					ref: this.moduleName
				});
				
				Ext.ux.TYPO3.Newsletter.Module.contentArea.add(component);
				Ext.ux.TYPO3.Newsletter.Module.contentArea.doLayout();
			}

			// Defines the current module as loaded
			this.getMenuItem(this.moduleName).isLoaded = true;

			// Shows up the panel
			component.setVisible(true);

			// Check wheter there are staticis for the page.
			// If not load a special panel for that case.
			if (false){ // TODO FIXME: Ext.ux.TYPO3.Newsletter.Data.numberOfStatistics == 0) {
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
		Ext.ux.TYPO3.Newsletter.Module.Application.fireEvent('Ext.ux.TYPO3.Newsletter.Module.afterbusy');
		Ext.ux.TYPO3.Newsletter.Module.contentArea.statistics.noStatisticsPanel.removeClass('t3-newsletter-hidden');
		Ext.ux.TYPO3.Newsletter.Module.contentArea.statistics.statisticsPanel.hide();
		Ext.ux.TYPO3.Newsletter.Module.contentArea.statistics.newsletterListMenu.hide();
	},

	/**
	 * Loads the first newsletter's statistics when clicking on the module button
	 * This will prevent a bug when the page was not loaded firstly (not anchor #statistics)
	 *
	 * @return void
	 */
	_loadStatisticsInCaseNotAlreadyLoaded: function() {
		var menu, store;
		menu = Ext.ux.TYPO3.Newsletter.Module.contentArea.statistics.newsletterListMenu;
		store = Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Newsletter');
		if (menu.getValue() == '' && store.getAt(0)) {
			menu.setValue(store.getAt(0).json.uid);
			menu.fireEvent('select');
		}
	}
});

Ext.ux.TYPO3.Newsletter.Module.Application.registerBootstrap(Ext.ux.TYPO3.Newsletter.Statistics.Bootstrap);