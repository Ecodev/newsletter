Ext.ns("Ext.ux.TYPO3.Newsletter.Planner");

/**
 * @class Ext.ux.TYPO3.Newsletter.Planner.Bootstrap
 * @namespace Ext.ux.TYPO3.Newsletter.Planner
 * @extends Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap
 *
 * Bootrap module planner
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Planner.Bootstrap = Ext.apply(new Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap, {

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
			var component = Ext.ux.TYPO3.Newsletter.Module.contentArea.plannerForm || null;
			if (!component) {
				component = Ext.ComponentMgr.create({
					xtype: 'Ext.ux.TYPO3.Newsletter.Module.PlannerForm',
					ref: 'plannerForm'
				});

				Ext.ux.TYPO3.Newsletter.Module.contentArea.add(component);
				Ext.ux.TYPO3.Newsletter.Module.contentArea.doLayout();
			}

			// temporary line: makes the loading message disappear
			Ext.ux.TYPO3.Newsletter.Module.Application.fireEvent('Ext.ux.TYPO3.Newsletter.Module.afterbusy');

			// Defines the menu as loaded
			this.getMenuItem(this.moduleName).isLoaded = true;
			
			// Shows up the latter panel
			component.setVisible(true);
		});

	}
});

Ext.ux.TYPO3.Newsletter.Module.Application.registerBootstrap(Ext.ux.TYPO3.Newsletter.Planner.Bootstrap);