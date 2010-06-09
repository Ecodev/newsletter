Ext.ns("TYPO3.Newsletter.Store");


TYPO3.Newsletter.initLogStore = function() {
	return new Ext.data.DirectStore({
		paramsAsHash: true,
		autoLoad: true,
	//	idProperty: 'source',
		root: 'data',
		directFn: TYPO3.Newsletter.Remote.getLogs,
		fields: [
			{name: 'source'},
			{name: 'description'},
			{name: 'datetime'}
		]
	});
}


/**
 * Button of the rootline menu
 * @class TYPO3.Newsletter.Store.LogPanel
 * @extends Ext.LogPanel
 */


Ext.reg('TYPO3.Newsletter.Store.LogPanel', TYPO3.Newsletter.Store.LogPanel);