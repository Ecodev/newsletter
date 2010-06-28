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
			store: TYPO3.Newsletter.Store.ListOfNewsletters,
			displayField: 'newsletter_formatted',
			valueField: 'uid',
			typeAhead: false,
			width: 300,
			mode: 'local',
			forceSelection: true,
			editable: false,
			triggerAction: 'all',
			selectOnFocus: true
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
		
//		if (TYPO3.Newsletter.Store.ListOfNewsletters.getAt(0).id) {
//			this.setValue(TYPO3.Newsletter.Store.ListOfNewsletters.getAt(0).id);
//		}
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
		console.log(this.getValue());
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.NewsletterListMenu', TYPO3.Newsletter.Statistics.NewsletterListMenu);