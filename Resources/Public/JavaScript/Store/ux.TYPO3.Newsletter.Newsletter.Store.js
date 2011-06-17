Ext.namespace('Ext.ux.TYPO3.Newsletter.Newsletter'); 
/**
 * A Store for the movie model using ExtDirect to communicate with the
 * server side extbase framework.
 */
Ext.ux.TYPO3.Newsletter.Newsletter.Store = function() {
	
	newsletterStore = null;
	
	var initialize = function() {
		if (newsletterStore == null) {
			newsletterStore = new Ext.data.DirectStore({
				storeId: 'Tx_Newsletter_Domain_Model_Newsletter',
				reader: new Ext.data.JsonReader({
					totalProperty:'total',
					successProperty:'success',
					idProperty:'__identity',
					root:'data',
					fields:[
					    {name: '__identity', type: 'int'},
					    {name: 'title', type: 'string'},
					    {name: 'director', type: 'string'},
					    {name: 'releaseDate', type: 'date'},
					    {name: 'genre', type: 'object'}
					]
				}),
				writer: new Ext.data.JsonWriter({
					encode:false,
					writeAllFields:false
				}),
				api: {
					read: Ext.ux.TYPO3.Newsletter.Remote.NewsletterController.listAction,
					update: Ext.ux.TYPO3.Newsletter.Remote.NewsletterController.updateAction,
					destroy: Ext.ux.TYPO3.Newsletter.Remote.NewsletterController.deleteAction,
					create: Ext.ux.TYPO3.Newsletter.Remote.NewsletterController.createAction
				},
				paramOrder: {
					read: [],
					update: ['data'],
					create: ['data'],
					destroy: ['data']
				},
				autoLoad: true,
				restful: false,
				batch: false,
				remoteSort: false
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