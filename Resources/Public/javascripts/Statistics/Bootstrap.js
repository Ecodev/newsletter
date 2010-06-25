Ext.ns("TYPO3.Newsletter.Statistics");

TYPO3.Newsletter.Statistics.Bootstrap = Ext.apply(new TYPO3.Newsletter.Application.AbstractBootstrap, {
	initialize: function() {
//		this.addContentArea('management', 'F3-TYPO3-Management', {
//			html: 'Management'
//		});

		/**
		 * Handle a navigation token.
		 *
		 * @param {RegExp} regexp the callback is called if the regexp matches
		 * @param {function} callback Callback to be called
		 * @param scope
		 */
		this.handleNavigationToken(/statistics/, function(e) {
			var component = TYPO3.Newsletter.UserInterface.mainContainer.statisticsPanel || null;
			if (!component) {
				component = Ext.ComponentMgr.create({
//					xtype: 'TYPO3.Newsletter.UserInterface.StatisticsPanel',
					xtype: 'panel',
					title: 'There is more to come',
					ref: 'statisticsPanel'
				});
				
				TYPO3.Newsletter.UserInterface.mainContainer.add(component);
				TYPO3.Newsletter.UserInterface.mainContainer.doLayout();
			}

			Ext.iterate(TYPO3.Newsletter.UserInterface.mainContainer.items.items, function (element) {
				element.setVisible(false)
			});
			component.setVisible(true);
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