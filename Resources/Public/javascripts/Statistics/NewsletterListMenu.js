"use strict";

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
			store: TYPO3.Newsletter.Store.NewsletterList,
			displayField: 'newsletter_label_formatted',
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
			'select',
			this.onselect,
			this
		);

		TYPO3.Newsletter.Store.NewsletterList.on(
			'TYPO3.Newsletter.Store.NewsletterList.afterload',
			this.onafterload,
			this
		);
	},

	/**
	 * Defines behaviour on change value
	 *
	 * @access public
	 * @method onafterrender
	 * @return void
	 */
	onafterload: function(data) {
		this.setValue(data[0].id);
		console.log(data[0].id);
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