Ext.ns("Ext.ux.TYPO3.Newsletter.Module");

    // turn on validation errors beside the field globally
    Ext.form.Field.prototype.msgTarget = 'side';
	
Ext.ux.TYPO3.Newsletter.Module.PlannerForm = Ext.extend(Ext.form.FormPanel, {

	initComponent: function() {
		var config = {
			title: 'My newsletter form',
			height: 700,
//			standardSubmit: true,
			clientValidation: false,
			method: 'GET',

			baseParams: {
				pid: 50, // TODO: TYPO3.Devlog.Data.Parameters.pid,
				M: 'web_NewsletterTxNewsletterM1',
				'tx_newsletter_web_newslettertxnewsletterm1[controller]': 'Statistic',
				'tx_newsletter_web_newslettertxnewsletterm1[action]': 'index',
				'tx_newsletter_web_newslettertxnewsletterm1[format]': 'json',
				'tx_newsletter_web_newslettertxnewsletterm1[pid]': 50 // TODO: TYPO3.Devlog.Data.Parameters.pid
			},
			proxy: new Ext.data.HttpProxy({
				method: 'GET',
				url: '/typo3/mod.php'
			}),
			url: '/typo3/mod.php',

			items: [
			{
				xtype: 'tabpanel', 
				items : [
				{
					// Fieldset in Column 1
					xtype:'fieldset',
					columnWidth: 0.5,
					title: 'Status'
				},

				{
					title: 'Tests & Settings',
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
							fieldLabel: 'Name',
							name: 'username',
							allowBlank:false
						},
						{
							fieldLabel: 'Address',
							name: 'sender_email',
							allowBlank:false
						}
						]
					},
					{
						// Fieldset in Column 1
						xtype:'fieldset',
						columnWidth: 0.5,
						title: 'Testing',
						collapsible: true,
						titleCollapse: true,
						collapsed: true, // fieldset initially collapsed
						autoHeight:true,
						defaults: {
							anchor: '-20'  // leave room for error icon
						},

						defaultType: 'textfield',
						items: [{
							xtype: 'panel',
							anchor: '100%',
							title: 'list of recipients ...',
							frame: true,
							height: 100
						},
						{
							xtype: 'button',
							text: 'Sent test emails',
							handler: function(){
								alert("email just sent :-)");
							}
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
						collapsed: true, // fieldset initially collapsed
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
							name: 'bounce',
							allowBlank:false
						},
						{
							xtype: 'combo',
							fieldLabel: 'Plain text method',
							name: 'plain_text',
							allowBlank:false
						},
						{
							xtype: 'combo',
							fieldLabel: 'Repeat periodically',
							name: 'periodicity',
							store:new Ext.data.SimpleStore({
								id:0,
								fields:['choice', 'periodicity'],
								data:[
								["0", "Never"],
								["1", "One day"],
								["2", "One week"],
								["3", "Two weeks"],
								["4", "One month"],
								["5", "Three months"],
								["6", "Six months"],
								["7", "One year"]
								]
							}),
							mode:'local',
							disableKeyFilter : true,
							allowBlank: false,
							forceSelection:true,
							valueField:'choice',
							displayField:'periodicity',
							value:'0'

						},
						{
							xtype: 'checkbox',
							fieldLabel: 'Detect opened emails',
							name: 'sender_email',
							allowBlank:false	            
						},
						{
							xtype: 'checkbox',
							fieldLabel: 'Detect clicked links',
							name: 'sender_email',
							allowBlank:false	            
						}
						]
					}
					]
				}

				,{
					// Fieldset in Column 2 - Panel inside
					xtype:'panel',
					title: 'Planning', // title, header, or checkboxToggle
					header: false, // Do not want double title in tab + panel
					// creates fieldset header
					autoHeight:true,
					layout:'anchor',
					items :[
					{
						xtype: 'fieldset',
						title: 'Recipients', 
						items: [{
							xtype: 'panel',
							anchor: '100%',
							title: 'list of recipients ...',
							frame: true,
							height: 100
						}]
					}
					,
					{
						xtype: 'fieldset',
						title: 'Time to send',
						items: [
						{
							xtype: 'datepicker',
							title: 'Date to send'
						},
						{
							xtype: 'timefield',
							title: 'Time to send'
						}
						]
					}
					]
				}
				]
			}
			],

			buttons: [{
				text: 'Submit',
				handler: this.submit

			}]

		};

		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Module.PlannerForm.superclass.initComponent.call(this);
	},


	/**
 * Submits the form. Called after Submit buttons is clicked
 * 
 * @private
 */
	submit:function() {
		//alert("Oui :)");
		
		var fp = this.ownerCt.ownerCt,
		form = fp.getForm();
		console.log(form);
		form.submit({clientValidation: false});
		console.log(this.url);
		return; 
				
		this.getForm().submit({
			url:this.url,
			scope:this
		});
	}, // eo function submit

	onRender: function() {		
		Ext.ux.TYPO3.Newsletter.Module.PlannerForm.superclass.onRender.apply(this, arguments);

		Ext.apply(this.getForm(),{
			api: {
				load: Ext.ux.TYPO3.Newsletter.Remote.getFormData,
				submit: Ext.ux.TYPO3.Newsletter.Remote.getFormData
			},
			paramsAsHash: false

		}); 
	//	this.form.load();
	}
});
Ext.reg('Ext.ux.TYPO3.Newsletter.Module.PlannerForm', Ext.ux.TYPO3.Newsletter.Module.PlannerForm);
