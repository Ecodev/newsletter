Ext.ns("TYPO3.Newsletter.UserInterface");

TYPO3.Newsletter.UserInterface.Layout = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			renderTo: 't3-newsletter-application',
			height: 700,
			items: [
//				{
//				xtype: 'TYPO3.Devlog.UserInterface.SectionMenu',
//				ref: 'sectionMenu',
//				flex: 0
//			},
			{
				xtype: 'TYPO3.Newsletter.UserInterface.FormNewsletter',
				ref: 'formNewsletter'
			},
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.UserInterface.Layout.superclass.initComponent.call(this);
	}
});