Ext.ns('Ext.ux.TYPO3.Newsletter.Store'); 
/**
 * A Store for the link model using ExtDirect to communicate with the
 * server side extbase framework.
 */
Ext.ux.TYPO3.Newsletter.Store.Link = function() {
	
	linkStore = null;
	
	var initialize = function() {
		if (linkStore == null) {
			linkStore = new Ext.data.DirectStore({
				storeId: 'Tx_Newsletter_Domain_Model_Link',
				reader: new Ext.data.JsonReader({
					totalProperty:'total',
					successProperty:'success',
					idProperty:'__identity',
					root:'data',
					fields:[
					    {name: '__identity', type: 'int'},
					    {name: 'url', type: 'string'},
					    {name: 'openedCount', type: 'int'},
					    {name: 'openedPercentage', type: 'int'}
					]
				}),
				writer: new Ext.data.JsonWriter({
					encode:false,
					writeAllFields:false
				}),
				api: {
					read: Ext.ux.TYPO3.Newsletter.Remote.LinkController.listAction,
					update: Ext.ux.TYPO3.Newsletter.Remote.LinkController.updateAction,
					destroy: Ext.ux.TYPO3.Newsletter.Remote.LinkController.destroyAction,
					create: Ext.ux.TYPO3.Newsletter.Remote.LinkController.createAction
				},
				paramOrder: {
					read: ['data'],
					update: ['data'],
					create: ['data'],
					destroy: ['data']
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
