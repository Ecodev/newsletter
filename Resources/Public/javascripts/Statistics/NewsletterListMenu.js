Ext.ns("TYPO3.Newsletter.Statistics");

/**
 * @class TYPO3.Newsletter.Statistics.NewsletterListMenu
 * @namespace TYPO3.Newsletter.Statistics
 * @extends Ext.form.ComboBox
 *
 * Class for newsletter drop down menu
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.NewsletterListMenu = Ext.extend(Ext.form.ComboBox, {

	initComponent: function() {
		var config = {
			id: 'newsletterListMenu',
			store: [
			['AL', 'Alabama', 'The Heart of Dixie'],
			['AK', 'Alaska', 'The Land of the Midnight Sun'],
			['AZ', 'Arizona', 'The Grand Canyon State'],
			['AR', 'Arkansas', 'The Natural State'],
			['CA', 'California', 'The Golden State'],
			['CO', 'Colorado', 'The Mountain State'],
			['CT', 'Connecticut', 'The Constitution State'],
			['DE', 'Delaware', 'The First State'],
			['DC', 'District of Columbia', "The Nation's Capital"],
			['FL', 'Florida', 'The Sunshine State'],
			['GA', 'Georgia', 'The Peach State']
			],
			displayField:'state',
			typeAhead: false,
			mode: 'local',
			forceSelection: true,
			editable: false,
			triggerAction: 'all',
			selectOnFocus:true
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.NewsletterListMenu.superclass.initComponent.call(this);

		// Defines listener
		this.on(
			'afterrender',
			this.onafterrender,
			this
		);

		this.on(
			'select',
			this.onselect,
			this
		);
	},

	/**
	 * Defines default value
	 *
	 * @access public
	 * @method onafterrender
	 * @return void
	 */
	onafterrender: function() {
//		this.setValue();
	},

	/**
	 * Defines behaviour on change value
	 *
	 * @access public
	 * @method onafterrender
	 * @return void
	 */
	onselect: function() {
//		var value = this.value - 0; // makes sure it is a number
//		TYPO3.Devlog.LogStore.baseParams.limit = value;
//		TYPO3.Devlog.UserInterface.container.gridPanel.pagebrowser.pageSize = value
//		TYPO3.Devlog.LogStore.load();
		console.log(123);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.NewsletterListMenu', TYPO3.Newsletter.Statistics.NewsletterListMenu);