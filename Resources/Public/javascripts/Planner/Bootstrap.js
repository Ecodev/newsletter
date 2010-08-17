Ext.ns("TYPO3.Newsletter.Planner");

/**
 * @class TYPO3.Newsletter.Planner.Bootstrap
 * @namespace TYPO3.Newsletter.Planner
 * @extends TYPO3.Newsletter.Application.AbstractBootstrap
 *
 * Bootrap module planner
 *
 * $Id$
 */
TYPO3.Newsletter.Planner.Bootstrap = Ext.apply(new TYPO3.Newsletter.Application.AbstractBootstrap, {

	// Properties
	moduleName: 'planner',

	initialize: function() {

		/**
		 * Handle a navigation token.
		 *
		 * @param {RegExp} regexp the callback is called if the regexp matches
		 * @param {function} callback Callback to be called
		 * @param scope
		 */
		this.handleNavigationToken(/planner/, function(e) {
			var component = TYPO3.Newsletter.UserInterface.contentArea.plannerForm || null;
			if (!component) {
				component = Ext.ComponentMgr.create({
					xtype: 'TYPO3.Newsletter.UserInterface.PlannerForm',
					ref: 'plannerForm'
				});

				TYPO3.Newsletter.UserInterface.contentArea.add(component);
				TYPO3.Newsletter.UserInterface.contentArea.doLayout();
			}

			// temporary line: makes the loading message disappear
			TYPO3.Newsletter.Application.fireEvent('TYPO3.Newsletter.Application.afterload');

			// Defines the menu as loaded
			this.getMenuItem(this.moduleName).isLoaded = true;
			
			// Shows up the latter panel
			component.setVisible(true);
		});

	}
});

TYPO3.Newsletter.Application.registerBootstrap(TYPO3.Newsletter.Planner.Bootstrap);