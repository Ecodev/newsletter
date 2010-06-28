Ext.ns("TYPO3.Newsletter.Store");

TYPO3.Newsletter.Store.initListOfNewsletters = function() {
	return new Ext.data.JsonStore({
		storeId: 'listOfNewsletters',
		autoLoad: true,
		remoteSort: true,
		baseParams: {
			ajaxID: 'NewsletterController::getListOfNewsletter',
			pid: TYPO3.Devlog.Data.Parameters.pid
		},
		proxy: new Ext.data.HttpProxy({
			method: 'GET',
			url: '/typo3/ajax.php'
		}),

		listeners : {
			load: function (store, data) {
				if (store.getCount() > 0) {
					var newsletterListMenu = TYPO3.Newsletter.UserInterface.contentArea.statistics.newsletterListMenu;
					newsletterListMenu.setValue(data[0].id);
				}
			}
		}
	});
}
