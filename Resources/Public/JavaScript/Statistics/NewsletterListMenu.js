"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.NewsletterListMenu
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics
 * @extends Ext.form.ComboBox
 *
 * Class for newsletter drop down menu
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.NewsletterListMenu = Ext.extend(Ext.form.ComboBox, {

	initComponent: function() {
		
		var config = {
			id: 'newsletterListMenu',
			//store: Ext.ux.TYPO3.Newsletter.Store.NewsletterList,
			//store: Ext.ux.TYPO3.Newsletter.Newsletter.Store,
			store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Newsletter'),
			displayField: 'statistic_label_formatted',
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
		Ext.ux.TYPO3.Newsletter.Statistics.NewsletterListMenu.superclass.initComponent.call(this);
		
		// Defines listener
		this.on(
			'select',
			this.onselect,
			this
		);

		Ext.ux.TYPO3.Newsletter.Store.NewsletterList.on(
			'Ext.ux.TYPO3.Newsletter.Store.NewsletterList.afterload',
			this.onafterload,
			this
		);
	},

	/**
	 * Defines behaviour after component is loaded
	 *
	 * @access public
	 * @method onafterrender
	 * @return void
	 */
	onafterload: function(data) {
		this.setValue(data[0].id);
		Ext.ux.TYPO3.Newsletter.Store.Statistic.fireEvent('Ext.ux.TYPO3.Newsletter.Store.Statistic.load', data[0].id);
	},

	/**
	 * Defines behaviour on change value
	 *
	 * @access public
	 * @method onafterrender
	 * @return void
	 */
	onselect: function() {
		Ext.ux.TYPO3.Newsletter.Store.Statistic.fireEvent('Ext.ux.TYPO3.Newsletter.Store.Statistic.load', this.getValue());
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.NewsletterListMenu', Ext.ux.TYPO3.Newsletter.Statistics.NewsletterListMenu);