Ext.ns("TYPO3.Newsletter.UserInterface");

TYPO3.Newsletter.UserInterface.FormNewsletter = Ext.extend(Ext.form.FormPanel, {

	initComponent: function() {
		var config = {
		    title: 'My newsletter form',
	        width: 650,
	        
	        
	        items: [{
	            // Fieldset in Column 1
	            xtype:'fieldset',
	            columnWidth: 0.5,
	            title: 'Sender',
	            collapsible: true,
	            collapsed: true, // fieldset initially collapsed
	            autoHeight:true,
	            defaults: {
	                anchor: '-20' // leave room for error icon
	            },
	            defaultType: 'textfield',
				items: [{
	                fieldLabel: 'Name',
	                name: 'username',
	                allowBlank:false
	            },
	            {
	                fieldLabel: 'Address',
	                name: 'sender_email',
	                allowBlank:false
	            }
	            
		        ],
	        }
	        ,{
	            // Fieldset in Column 2 - Panel inside
	            xtype:'fieldset',
	            title: 'Planning', // title, header, or checkboxToggle creates fieldset header
	            autoHeight:true,
	            columnWidth: 0.5,
	            
	            collapsible: true, // fieldset initially collapsed
	            layout:'anchor',
	            items :[{
	                xtype: 'panel',
	                anchor: '100%',
	                title: 'list of recipients ...',
	                frame: true,
	                height: 100
	            },

	            {
	                xtype: 'datepicker',
	                anchor: '100%',
	                title: 'list of recipients ...',
	            },
	            {
	                xtype: 'timefield',
	                anchor: '100%',
	                title: 'list of recipients ...',
	                frame: true,
	                height: 100
	            }]
	        },
	        
	        {
	            // Fieldset in Column 1
	            xtype:'fieldset',
	            columnWidth: 0.5,
	            title: 'Testing',
	            collapsible: true,
	            collapsed: true, // fieldset initially collapsed
	            autoHeight:true,
	            defaults: {
	                anchor: '-20' // leave room for error icon
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
	            }],
	        },
	        {
	            // Fieldset in Column 1
	            xtype:'fieldset',
	            columnWidth: 0.5,
	            title: 'Advanced settings',
	            collapsible: true,
	            collapsed: true, // fieldset initially collapsed
	            autoHeight:true,
	            defaults: {
	                anchor: '-20' // leave room for error icon
	            },
	            defaultType: 'textfield',
				items: [{
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
	                name: 'plain_text',
	                allowBlank:false
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
	            }],
	        }
	        
	        ],
	        
	        
	        buttons: [{
	            text: 'Submit',
	            
	        }]

		};
		
		Ext.apply(this, config);
		TYPO3.Newsletter.UserInterface.FormNewsletter.superclass.initComponent.call(this);
	}
});
Ext.reg('TYPO3.Newsletter.UserInterface.FormNewsletter', TYPO3.Newsletter.UserInterface.FormNewsletter);
