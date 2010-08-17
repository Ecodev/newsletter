"use strict";

Ext.ns("TYPO3.Newsletter.UserInterface");

/**
 * @class TYPO3.Newsletter.UserInterface.Bootstrap
 * @namespace TYPO3.Newsletter.UserInterface
 * @extends TYPO3.Newsletter.Application.AbstractBootstrap
 *
 * Bootrap application
 *
 * $Id$
 */
TYPO3.Newsletter.UserInterface.Bootstrap = Ext.apply(new TYPO3.Newsletter.Application.AbstractBootstrap, {
	initialize: function() {

		this.addToMenu(['mainMenu'], [
			{
				text: TYPO3.Newsletter.Language.newsletter_button,
				itemId: 'planner'
			},
			{
				text: TYPO3.Newsletter.Language.statistics_button,
				itemId: 'statistics'
			}
		]);
		
		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.Application.afterBootstrap', this.initContentArea, this);
		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.Application.afterBootstrap', this.initSectionMenu, this);
	},
	
	initContentArea: function() {
		TYPO3.Newsletter.UserInterface.contentArea = new TYPO3.Newsletter.UserInterface.ContentArea();
	},

	initSectionMenu: function() {
		TYPO3.Newsletter.UserInterface.sectionMenu = new TYPO3.Newsletter.UserInterface.SectionMenu();
	}
});

TYPO3.Newsletter.Application.registerBootstrap(TYPO3.Newsletter.UserInterface.Bootstrap);