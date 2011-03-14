Ext.ns('Ext.ux.TYPO3.MvcExtjs');
/**
 * 
 */
Ext.ux.TYPO3.MvcExtjs.FlashMessagesTabPanel = function(){
	
	var oldMessages;
	var newMessages;
	var tabPabel;
	
	var addMessages = function(msgs) {
		flush();
		newMessages.addAll(msgs);
		newMessages.each(function(message,index,length) {
			tmpTab = new Ext.Panel({
				title: message.type,
				tstamp: message.tstamp,
				id: message.message + '-' + message.tstamp,
				html: message.message,
				iconCls: 't3-icon-' + message.type, // TODO: receive a icon class that works
				closable: true
			});
			tabPanel.add(tmpTab);
		});
	};
	/**
	 * Makes all currently new Messages become old.
	 * The tabs are removed and the message object move from newMessages to oldMessages
	 */
	var flush = function(message) {
		newMessages.each(function(message,index,length) {
			panelToRemove = tabPanel.findById(message.message + '-' + message.tstamp);
			if (panelToRemove) tabPanel.remove(panelToRemove);
			oldMessages.add(message);
			newMessages.remove(message);
		});
	}

	var getTabPanel = function() {
		return tabPanel;
	}
	
	var createTabPanel = function(config) {
		tabConfig = Ext.apply({
			region: 'north',
			height: 120,
	    	listeners: {
				add: function(tabPanel, addedComponent, index) {
					tabPanel.activate(addedComponent);
				}
			}
		},config);
		tmpTabs = new Ext.TabPanel(tabConfig);
		tmpTabs.add({
			xtype: 'panel',
			title: 'FlashMessages',
			html: 'FlashMessages will be opened in new tab, if there are any sent by a controller.',
			closable: false
		});
		return tmpTabs;
	}
	
	var initialize = function(config) {
		oldMessages = new Ext.util.MixedCollection();
		newMessages = new Ext.util.MixedCollection();
		tabPanel = createTabPanel(config);
		Ext.ux.TYPO3.MvcExtjs.DirectFlashMessageDispatcher.on('new',addMessages);
		
	};
	
    return Ext.apply(new Ext.util.Observable, {
    	getTabPanel: getTabPanel,
    	addMessage: function(msg) {
    		addMessages([msg]);
    	},
    	initialize: initialize
    })
}();