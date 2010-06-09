Ext.ns("TYPO3.Newsletter.UserInterface");

TYPO3.Newsletter.UserInterface.TestingButton = Ext.extend(Ext.Button, {

	initComponent: function() {
		var config = {
			text: 'This is a suppose to be a more complex element which is worthily extended. Click me!',
			handler: function(){
				alert(456);
			}
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.UserInterface.TestingButton.superclass.initComponent.call(this);
	}
});

	Ext.reg('TYPO3.Newsletter.UserInterface.TestingButton', TYPO3.Newsletter.UserInterface.TestingButton);