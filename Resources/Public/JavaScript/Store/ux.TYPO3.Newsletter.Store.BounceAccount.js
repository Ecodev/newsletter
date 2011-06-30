Ext.namespace('Ext.ux.TYPO3.Newsletter.BounceAccount'); 
/**
 * A Store for the bounceAccount model using ExtDirect to communicate with the
 * server side extbase framework.
 */
Ext.ux.TYPO3.Newsletter.Store.BounceAccount = function() {
	
	bounceAccountStore = null;
	
	var initialize = function() {
		if (bounceAccountStore == null) {
			bounceAccountStore = new Ext.data.DirectStore({
				storeId: 'Tx_Newsletter_Domain_Model_BounceAccount',
				reader: new Ext.data.JsonReader({
					totalProperty:'total',
					successProperty:'success',
					idProperty:'__identity',
					root:'data',
					fields:[
					    {name: '__identity', type: 'int'},
						{name: 'email', type: 'string'},
						{name: 'server', type: 'string'},
						{name: 'protocol', type: 'string'},
						{name: 'username', type: 'string'},
					    {name: 'fullName', convert: function(v, bounceAccount) { return String.format('{0} ({1}://{2}@{3})', bounceAccount.email, bounceAccount.protocol, bounceAccount.username, bounceAccount.server); }}
					]
				}),
				writer: new Ext.data.JsonWriter({
					encode:false,
					writeAllFields:false
				}),
				api: {
					read: Ext.ux.TYPO3.Newsletter.Remote.BounceAccountController.listAction,
					update: Ext.ux.TYPO3.Newsletter.Remote.BounceAccountController.updateAction,
					destroy: Ext.ux.TYPO3.Newsletter.Remote.BounceAccountController.destroyAction,
					create: Ext.ux.TYPO3.Newsletter.Remote.BounceAccountController.createAction
				},
				paramOrder: {
					read: [],
					update: ['data'],
					create: ['data'],
					destroy: ['data']
				},
				autoLoad: true
				
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
