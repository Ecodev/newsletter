Ext.namespace('Ext.ux.TYPO3.Newsletter.Newsletter'); 
/**
 * A Store for the movie model using ExtDirect to communicate with the
 * server side extbase framework.
 */
Ext.ux.TYPO3.Newsletter.Store.Newsletter = function() {
	
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
						{name: 'beginTime', type: 'date'},
						//{name: 'bounceAccount', type: 'string'},
						{name: 'domain', type: 'string'},
						{name: 'endTime', type: 'date'},
						{name: 'injectLinksSpy', type: 'boolean'},
						{name: 'injectOpenSpy', type: 'boolean'},
						{name: 'isTest', type: 'boolean'},
						//{name: 'plainConverter', type: 'string'},
						{name: 'plannedTime', type: 'date'},
						{name: 'repetition', type: 'int'},
						{name: 'senderEmail', type: 'string'},
						{name: 'senderName', type: 'string'},
						{name: 'title', type: 'string'},
						{name: 'emailCount', type: 'int'},
						{name: 'emailNotSentCount', type: 'int'},
						{name: 'emailSentCount', type: 'int'},
						{name: 'emailOpenedCount', type: 'int'},
						{name: 'emailBouncedCount', type: 'int'},
						{name: 'emailNotSentPercentage', convert: function(v, newsletter) {return newsletter.emailCount ? Math.round(100 * newsletter.emailNotSentCount / newsletter.emailCount) : 0;}},
						{name: 'emailSentPercentage', convert: function(v, newsletter) {return newsletter.emailCount ? Math.round(100 * newsletter.emailSentCount / newsletter.emailCount) : 0;}},
						{name: 'emailOpenedPercentage', convert: function(v, newsletter) {return newsletter.emailCount ? Math.round(100 * newsletter.emailOpenedCount / newsletter.emailCount) : 0;}},
						{name: 'emailBouncedPercentage', convert: function(v, newsletter) {return newsletter.emailCount ? Math.round(100 * newsletter.emailBouncedCount / newsletter.emailCount) : 0;}},
						{name: 'fullTitle', convert: function(v, newsletter) { return String.format('{0} @ {1} - {2} emails', newsletter.title, newsletter.plannedTime, newsletter.emailCount);}}
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