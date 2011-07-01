"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Store");

/**
 * @class Ext.ux.TYPO3.Newsletter.Store.Bootstrap
 * @namespace Ext.ux.TYPO3.Newsletter.Store
 * @extends Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap
 *
 * Bootrap store
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Store.Bootstrap = Ext.apply(new Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap(), {
	initialize: function() {
		Ext.ux.TYPO3.Newsletter.Module.Application.on('Ext.ux.TYPO3.Newsletter.Module.Application.afterBootstrap', this.initStore, this);
	},
	
	initStore: function() {
		Ext.ux.TYPO3.Newsletter.Store.Newsletter.initialize();
		Ext.ux.TYPO3.Newsletter.Store.SelectedNewsletter.initialize();
		Ext.ux.TYPO3.Newsletter.Store.PlannedNewsletter.initialize();
		Ext.ux.TYPO3.Newsletter.Store.Email.initialize();
		Ext.ux.TYPO3.Newsletter.Store.Link.initialize();
		Ext.ux.TYPO3.Newsletter.Store.BounceAccount.initialize();
		Ext.ux.TYPO3.Newsletter.Store.RecipientList.initialize();
		
		// pie chart depends on SelectedNewsletter store so it must be initialized after it
		Ext.ux.TYPO3.Newsletter.Store.OverviewPieChart = Ext.ux.TYPO3.Newsletter.Store.initOverviewPieChart();
	}
});

Ext.ux.TYPO3.Newsletter.Module.Application.registerBootstrap(Ext.ux.TYPO3.Newsletter.Store.Bootstrap);