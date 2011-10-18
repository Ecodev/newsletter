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
						collapsible: true,
						titleCollapse: true,
						// collapsed: true, // fieldset initially collapsed

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
						collapsible: true,
						titleCollapse: true,
						//collapsed: true, // fieldset initially collapsed
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
							forceSelection:true,
							triggerAction : 'all',
							valueField: 'value',
							displayField: 'name'
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
							allowBlank: false

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
					// creates fieldset header
					autoHeight:true,
					layout:'anchor',
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
							allowBlank: false
						}]
					},
					
					{
						// Fieldset in Column 1
						xtype:'fieldset',
						title: 'Testing',
						autoHeight:true,
						items: [
						{
							xtype: 'displayfield',
							html: '<p>Test newsletter are sent immediately. Because the queue system is bypassed, it cannot send many emails at once.</p><p>Also test newsletters will be ignored by default in statistics.</p>'
						},
						{
							xtype: 'button',
							text: 'Sent test emails now',
							handler: function(button, event) {
								createNewsletter(button, true);
							}
						}
						]
					},
					{
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
			],


			listeners: 
			{
				'afterrender': function(formPanel){
					formPanel.getForm().doAction('directload');
				}
			}
		};

		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Planner.Planner.superclass.initComponent.call(this);
	}
	
});
Ext.reg('Ext.ux.TYPO3.Newsletter.Planner.Planner', Ext.ux.TYPO3.Newsletter.Planner.Planner);
