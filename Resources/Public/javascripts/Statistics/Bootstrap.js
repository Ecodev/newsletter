Ext.ns("TYPO3.Newsletter.Statistics");

TYPO3.Newsletter.Statistics.Bootstrap = Ext.apply(new TYPO3.Newsletter.Application.AbstractBootstrap, {
	initialize: function() {
//		this.addContentArea('management', 'F3-TYPO3-Management', {
//			html: 'Management'
//		});

		this.handleNavigationToken(/statistics/, function(e) {

			if (!TYPO3.Newsletter.UserInterface.mainContainer.formNewsletter) {
				var element = Ext.ComponentMgr.create({
					xtype: 'TYPO3.Newsletter.UserInterface.FormNewsletter',
					ref: 'formNewsletter'
				});

				TYPO3.Newsletter.UserInterface.mainContainer.add(element);
				TYPO3.Newsletter.UserInterface.mainContainer.doLayout();
			}
			else {
				TYPO3.Newsletter.UserInterface.mainContainer.formNewsletter.setVisible(true);
			}

//			TYPO3.Newsletter.UserInterface.Bootstrap.initMainContainer();
//			TYPO3.Newsletter.UserInterface.Layout.sectionMenu.setActiveTab('management');
		});

		// TODO refactor to helper method
//		TYPO3.Newsletter.Application.on('TYPO3.Newsletter.UserInterface.SectionMenu.activated', function(itemId) {
//			if (itemId === 'management') {
//				Ext.History.add('management');
//				Ext.getCmp('F3-TYPO3-UserInterface-center').getLayout().setActiveItem('F3-TYPO3-Management');
//			}
//		});
	}
});

TYPO3.Newsletter.Application.registerBootstrap(TYPO3.Newsletter.Statistics.Bootstrap);