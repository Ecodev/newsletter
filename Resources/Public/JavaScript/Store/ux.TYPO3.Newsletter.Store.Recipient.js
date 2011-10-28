"use strict";

Ext.ns('Ext.ux.TYPO3.Newsletter.Store'); 

/**
 * A Store for the selectedRecipientList model using ExtDirect to communicate with the
 * server side extbase framework.
 */
Ext.ux.TYPO3.Newsletter.Store.Recipient = function() {
	
	var selectedRecipientListStore = null;
	
	var initialize = function() {
		if (selectedRecipientListStore == null) {
			selectedRecipientListStore = new Ext.data.DirectStore({
				storeId: 'Tx_Newsletter_Domain_Model_Recipient',
				
				// Here the JsonReader will be configured by metadata sent by server-side, because the columns available not known in advance
				reader: new Ext.data.JsonReader(),
				
				api: {
					read: Ext.ux.TYPO3.Newsletter.Remote.RecipientListController.listRecipientAction
				},
				paramOrder: {
					read: ['data', 'start', 'limit']
				}
			});
		}
	}
	/**
	 * Public API of this singleton.
	 */
	return {
		initialize: initialize
	}
}();
