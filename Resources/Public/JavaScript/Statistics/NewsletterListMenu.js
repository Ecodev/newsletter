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
Ext.ux.TYPO3.Newsletter.Statistics.NewsletterListMenu = Ext.extend(Ext.grid.GridPanel, {

	initComponent: function() {
		var thisNewsletterListMenu = this;
		var newsletterStore = Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Newsletter');
		
		var config = {
			emptyText: Ext.ux.TYPO3.Newsletter.Language.no_statistics,
			id: 'newsletterListMenu',
			store: newsletterStore,
			autoExpandColumn: 'title',
			height: 160,
			mode: 'local',
			stripeRows: true,
			selModel: new Ext.grid.RowSelectionModel({
				singleSelect:true,
				listeners:{
					rowselect: function(selectionModel, rowIndex, newsletter){thisNewsletterListMenu.onNewsletterSelected(newsletter);}
				}
			}),
			listeners: {
				// Select the first item after the everything is loaded
				viewready: function(grid){ grid.getSelectionModel().selectFirstRow(); }
			},
			columns: [
				{
					id: 'title',
					header: Ext.ux.TYPO3.Newsletter.Language.newsletter,
					dataIndex: 'title',
					width: 300,
					sortable: true
				},
				{
					header: Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_planned_time,
					dataIndex: 'plannedTime',
					width: 150,
					sortable: true,
					renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s')
				},
				{
					header: Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_begin_time,
					dataIndex: 'beginTime',
					width: 150,
					sortable: true,
					renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s')
				},
				{
					header: Ext.ux.TYPO3.Newsletter.Language.recipients,
					dataIndex: 'emailCount',
					width: 100,
					sortable: true
				},
				{
					header: Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_is_test,
					dataIndex: 'isTest',
					width: 70,
					sortable: true,
					renderer: function(value){return value ? '✔' : ''; }
				}			
			]
			
		};
		
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.NewsletterListMenu.superclass.initComponent.call(this);
	},

	/**
	 * When a newsletter is selected, we update the store representing the selected newsletter.
	 * TODO: there probably is a cleaner way to do this wihtout an intermediary store, but I couldn't find how to do yet
	 * 
	 * And we also update other depending stores (links and email)
	 * TODO: it should be the depending stores listening to the newsletterList, but I couldn't 
	 * find an easy way to access the newsletterList from the stores
	 */
	onNewsletterSelected: function(newsletter) {
		
		var selectedNewsletterStore = Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_SelectedNewsletter');
		selectedNewsletterStore.load({params: {data: newsletter.data.__identity }});
		
		var linkStore = Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Link');
		linkStore.load({params: {data: newsletter.data.__identity }});
		
		var linkEmail = Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Email');
		linkEmail.load({params: {data: newsletter.data.__identity }});
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.NewsletterListMenu', Ext.ux.TYPO3.Newsletter.Statistics.NewsletterListMenu);