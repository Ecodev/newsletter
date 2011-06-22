"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Module");

/**
 * @class Ext.ux.TYPO3.Newsletter.Module.Bootstrap
 * @namespace Ext.ux.TYPO3.Newsletter.Module
 * @extends Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap
 *
 * Bootrap application
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Module.Bootstrap = Ext.apply(new Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap, {
	initialize: function() {

		this.addToMenu(['mainMenu'], [
		{
			text: Ext.ux.TYPO3.Newsletter.Language.newsletter_button,
			itemId: 'planner'
		},
		{
			text: Ext.ux.TYPO3.Newsletter.Language.statistics_button,
			itemId: 'statistics'
		}
		]);
		
		Ext.ux.TYPO3.Newsletter.Module.Application.on('Ext.ux.TYPO3.Newsletter.Module.Application.afterBootstrap', this.initGUI, this);
	},
	
	/**
	 * Init menus and content area
	 */
	initGUI: function() {
		Ext.ux.TYPO3.Newsletter.Module.contentArea = new Ext.ux.TYPO3.Newsletter.Module.ContentArea({
			region: 'center'
		});
		Ext.ux.TYPO3.Newsletter.Module.sectionMenu = new Ext.ux.TYPO3.Newsletter.Module.SectionMenu({
			region: 'north',
			height: 30
		});
		
		new Ext.Viewport({
			layout: 'border',
			renderTo: Ext.getBody(),
			north: {
				split: true, 
				initialSize: 50
			},
			items: [
			Ext.ux.TYPO3.Newsletter.Module.sectionMenu, 
			Ext.ux.TYPO3.Newsletter.Module.contentArea
			]
		});
	}
});

Ext.ux.TYPO3.Newsletter.Module.Application.registerBootstrap(Ext.ux.TYPO3.Newsletter.Module.Bootstrap);