"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Planner");

// turn on validation errors beside the field globally
Ext.form.Field.prototype.msgTarget = 'side';
	
Ext.ux.TYPO3.Newsletter.Planner.Planner = Ext.extend(Ext.form.FormPanel, {

	initComponent: function() {
		
		function createNewsletter(button, isTest) {
			
			// Valid the form
			var form = button.findParentByType('form').getForm();
			if (!form.isValid()) {
				Ext.ux.TYPO3.Newsletter.FlashMessageOverlayContainer.addMessage({
					severity: 2,
					message: 'Fix the invalid fields in the form and try again.',
					title: 'Invalid form'});
				
				return; 
			}
			
			// Tweak values for newsletter testing
			var values = form.getFieldValues();
			values.isTest = isTest;
			if (values.isTest) {
				values.plannedTime = new Date();
			}
			if (values.uidBounceAccount == null) {
				values.uidBounceAccount = 0;
			}

			// Disable the button while processing request to avoid double-submit
			var newsletterStore = Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Newsletter');
			button.disable();
			newsletterStore.addListener('save', function(){button.enable();}, null, {single: true});
			
			// If something went wrong on server during saving, remove the failing newsletter
			newsletterStore.addListener('exception', function(){newsletterStore.removeAt(0);}, null, {single: true});
			
			// Write to the store who will make an ajax request via ExtDirect
			var newsletter = new newsletterStore.recordType(values);
			newsletterStore.insert(0, newsletter);
		}
		
		var config = {
			title: Ext.ux.TYPO3.Newsletter.Language.newsletter_button,
			height: 700,
			//			standardSubmit: true,
			clientValidation: false,
			
			items: [
			{
				xtype: 'tabpanel',
				activeTab: 0,
				items : [
				{
					height: 500,
					// Fieldset in Column 1
					xtype:'fieldset',
					title: 'Status',
	
					items:[
					{
						height: 500,
						xtype: 'dataview',
						store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_PlannedNewsletter'),
						emptyText: 'No text to display',
						tpl: new Ext.XTemplate(
							'<tpl for=".">',
							'<div>',
							'<h2>Recent activity</h2><p>{status}</p>',
							'<h2>Newsletter validity</h3>',
							'<h3>Errors</h3>{errors}',
							'<h3>Warnings</h3>{warnings}',
							'<h3>Infos</h3>{infos}',
							'</div>',
							'</tpl>'
							)
					}]
				},

				{
					title: 'Settings',
					xtype: 'panel',
					height: 700,
					items: 
					[
					{
						// Fieldset in Column 1
						xtype:'fieldset',
						columnWidth: 0.5,
						title: 'Sender',
						defaults: {
							anchor: '-20'
						},// leave room for error icon
						defaultType: 'textfield',
						items: 
						[
						{
							xtype: 'hidden',
							name: 'pid'
						},
						{
							fieldLabel: 'Name',
							name: 'senderName',
							allowBlank:false
						},
						{
							fieldLabel: 'Email address',
							name: 'senderEmail',
							allowBlank:false
						}
						]
					},
					{
						// Fieldset in Column 1
						xtype:'fieldset',
						columnWidth: 0.5,
						title: 'Advanced settings',
						titleCollapse: true,
						collapsed: true,
						collapsible: true,
						autoHeight:true,
						defaults: {
							anchor: '-20'  // leave room for error icon
						},

						defaultType: 'textfield',
						items:
						[
						{
							xtype: 'combo',
							fieldLabel: 'Bounce account',
							name: 'uidBounceAccount',
							store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_BounceAccount'),
							displayField: 'fullName',
							valueField: '__identity',
							mode: 'local',
							forceSelection: true,
							triggerAction: 'all',
							selectOnFocus: true,
							autoSelect: true,
							typeAhead: false
						},
						{
							xtype: 'combo',
							fieldLabel: 'Plain text method',
							name: 'plainConverter',
							allowBlank:false,
							store: new Ext.data.ArrayStore({
								idIndex: 0,
								fields: ['value', 'name'],
								data: [
									['Tx_Newsletter_Domain_Model_PlainConverter_Builtin', Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_plain_converter_builtin],
									['Tx_Newsletter_Domain_Model_PlainConverter_Template', Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_plain_converter_template],
									['Tx_Newsletter_Domain_Model_PlainConverter_Lynx', Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_plain_converter_lynx]
								]
							}),
							value: 0,
							mode:'local',
							forceSelection: true,
							triggerAction: 'all',
							valueField: 'value',
							displayField: 'name',
							editable: false
						},
						{
							xtype: 'combo',
							fieldLabel: 'Repeat periodically',
							name: 'repetition',
							store: new Ext.data.ArrayStore({
								idIndex: 0,
								fields: ['value', 'name'],
								data: [
									[0, Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_none],
									[1, Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_daily],
									[2, Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_weekly],
									[3, Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_biweekly],
									[4, Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_monthly],
									[5, Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_quarterly],
									[6, Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_semiyearly],
									[7, Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_yearly]
								]
							}),
							value: 0,
							mode:'local',
							forceSelection:true,
							triggerAction : 'all',
							valueField: 'value',
							displayField: 'name',
							allowBlank: false,
							editable: false
						},
						{
							xtype: 'checkbox',
							fieldLabel: 'Detect opened emails',
							name: 'injectOpenSpy'           
						},
						{
							xtype: 'checkbox',
							fieldLabel: 'Detect clicked links',
							name: 'injectLinksSpy'          
						}
						]
					}
					]
				}

				,{
					// Fieldset in Column 2 - Panel inside
					xtype:'panel',
					title: 'Sending', // title, header, or checkboxToggle
					header: false, // Do not want double title in tab + panel
					items :[
					{
						xtype: 'fieldset',
						title: 'Recipients',
						defaults: {
							anchor: '-20'  // leave room for error icon
						},

						items: [
						{
							xtype: 'combo',
							fieldLabel: Ext.ux.TYPO3.Newsletter.Language.tx_newsletter_domain_model_newsletter_recipient_list,
							name: 'uidRecipientList',
							store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_RecipientList'),
							displayField: 'fullName',
							valueField: '__identity',
							mode: 'local',
							forceSelection: true,
							triggerAction: 'all',
							selectOnFocus: true,
							autoSelect: true,
							typeAhead: false,
							allowBlank: false,
							editable: false,
							listeners: {
								
								// Forward event when the store is loaded to simulate that first item is selected (and then load recipient list in grid)
								added: function(combo) {

									var store = combo.getStore();
									store.addListener('load', function(store, records) {
										if (records.length > 0) combo.fireEvent('select', combo, records[0], 0);
									}, null, {single: true});
								},

								/**
								 * When an uidRecipientList is selected, we update other depending stores (recipients)
								 * TODO: it should be the depending stores listening to the uidRecipientList, but I couldn't 
								 * find an easy way to access the uidRecipientList from the stores
								 */
								select: function(combo, recipientList, index) {
									var recipientStore = Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Recipient');
									recipientStore.load({params: {data: recipientList.data.__identity, start: 0, limit: 50 }});
								}
							}
						},
						{
							xtype: 'grid',
							loadMask: true,
							store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Recipient'),
							height: 200,
							
							// When the grid is ready, we add a listener to its store, so we can reconfigure
							// the grid whenever the store's metadata change, and thus updating available columns for the grid
							listeners: {
								added: function(grid) {
									grid.getStore().addListener('metachange', function(store, meta) {	
										var columns = [];
										columns.push({
											header: Ext.ux.TYPO3.Newsletter.Language.preview,
											dataIndex: 'email',
											renderer: function(value, parent, record) {
														
												var form = grid.findParentByType('form').getForm();
												var values = form.getFieldValues();
												var params = String.format('?pid={0}&uidRecipientList={1}&plainConverter={2}&injectOpenSpy={3}&injectLinksSpy={4}&email={5}',
													values.pid,
													values.uidRecipientList,
													values.plainConverter,
													values.injectOpenSpy,
													values.injectLinksSpy,
													value);
												
												return String.format('<a href="/typo3conf/ext/newsletter/web/view.php' + params + '">preview</a>', value);
											}
										});

										for (var i = 0; i < meta.fields.length; i++ ) {
												columns.push( {
													header: meta.fields[i].name,
													dataIndex: meta.fields[i].name,
													type: meta.fields[i].type,
													width: 150
												});
										}
										
										grid.reconfigure(store, new Ext.grid.ColumnModel(columns));
									});
								}
							},
							columns: [], // No columns when loading, it will be dynamically set when the store is loaded and we know what columns are available
							//
							// paging bar on the bottom
							bbar: new Ext.PagingToolbar({
								pageSize: 50,
								store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_Recipient'),
								displayInfo: true,
								listeners: {
									beforechange: function(pagingToolbar, params) {
										var form = pagingToolbar.findParentByType('form').getForm();
										var uidRecipientList = form.findField('uidRecipientList').getValue();
										params.data = uidRecipientList;
									}
								}
							})
						}]
					},
					{
						height: 120,
						layout: {
							type:'hbox',
							align: 'stretch'
						},
						
						items: [
						{
							flex: 0.5,
							xtype:'fieldset',
							title: 'Testing',
							items: [
							{
								xtype: 'panel',
								items: [
								{
									xtype: 'displayfield',
									html: '<p>Test newsletter are sent immediately. Because the queue system is bypassed, it cannot send many emails at once.</p><p>Also test newsletters will be ignored by default in statistics.</p>'
								}]
							},
							{
								xtype: 'button',
								text: 'Send test emails now',
								label: 'asds',
								handler: function(button, event) {
									createNewsletter(button, true);
								}
							}
							]
						},
						{
							flex: 0.5,
							xtype: 'fieldset',
							title: 'Planning',
							items: [
							{
								xtype: 'xdatetime',
								fieldLabel: 'Date to start sending',
								name: 'plannedTime',
								hiddenFormat: 'c'
							},
							{
								xtype: 'button',
								text: 'Add to queue',
								handler: function(button, event) {
									createNewsletter(button, false);
								}
							}
							]
						}
						]
					}
					]
				}
				]
			}
			],


			listeners: 
			{
				afterrender: function(formPanel){
					formPanel.getForm().doAction('directload');
				}
			}
		};

		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Planner.Planner.superclass.initComponent.call(this);
	}
	
});
Ext.reg('Ext.ux.TYPO3.Newsletter.Planner.Planner', Ext.ux.TYPO3.Newsletter.Planner.Planner);
