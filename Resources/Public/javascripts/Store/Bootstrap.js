"use strict";

Ext.ns("TYPO3.Newsletter.Store");

/**
 * @class TYPO3.Newsletter.Store.Bootstrap
 * @namespace TYPO3.Newsletter.Store
 * @extends TYPO3.Newsletter.Application.AbstractBootstrap
 *
 * Bootrap store
 *
 * $Id$
 */
TYPO3.Newsletter.Store.Bootstrap = Ext.apply(new TYPO3.Newsletter.Application.AbstractBootstrap(), {
	initialize: function() {
		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.Application.afterBootstrap', this.initStore, this);
	},
	initStore: function() {
		var api;
		for (api in Ext.app.ExtDirectAPI) {
			if (Ext.app.ExtDirectAPI[api]) {
				Ext.Direct.addProvider(Ext.app.ExtDirectAPI[api]);
			}
		}

		TYPO3.Newsletter.Store.ListOfNewsletters = TYPO3.Newsletter.Store.initListOfNewsletters();

//		TYPO3.Newsletter.LogStore2.doRequest();
	}
});

TYPO3.Newsletter.Application.registerBootstrap(TYPO3.Newsletter.Store.Bootstrap);