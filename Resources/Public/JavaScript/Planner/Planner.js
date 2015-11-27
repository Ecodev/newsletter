(function() {
    "use strict";
    Ext.ns("Ext.ux.Ecodev.Newsletter.Planner");
    // turn on validation errors beside the field globally
    Ext.form.Field.prototype.msgTarget = 'side';
    Ext.ux.Ecodev.Newsletter.Planner.Planner = Ext.extend(Ext.form.FormPanel, {
        initComponent: function() {

            function createNewsletter(button, isTest) {

                // Valid the form
                var form = button.findParentByType('form').getForm();
                if (!form.isValid()) {
                    Ext.ux.Ecodev.Newsletter.FlashMessageOverlayContainer.addMessage({
                        severity: 2,
                        message: 'Fix the invalid fields in the form and try again.',
                        title: 'Invalid form'});
                    return;
                }

                // Tweak values for newsletter testing
                var values = form.getFieldValues();
                values.isTest = isTest;
                if (values.uidBounceAccount === null) {
                    values.uidBounceAccount = 0;
                }

                // We specifically convert dates to ISO 8061 format to include client timezone,
                // so the server can correctly convert to his own timezone
                values.plannedTime = values.plannedTime.format('c');
                // Disable the button while processing request to avoid double-submit
                var newsletterStore = Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\Newsletter');
                button.disable();
                newsletterStore.addListener('save', function() {
                    button.enable();
                }, null, {single: true});
                // If something went wrong on server during saving, remove the failing newsletter
                newsletterStore.addListener('exception', function() {
                    newsletterStore.removeAt(0);
                }, null, {single: true});
                // Write to the store who will make an ajax request via ExtDirect
                var newsletter = new newsletterStore.recordType(values);
                newsletterStore.insert(0, newsletter);
            }

            var config = {
                title: Ext.ux.Ecodev.Newsletter.Language.newsletter_tab,
                layout: 'fit',
                clientValidation: false,
                items: [
                    {
                        xtype: 'tabpanel',
                        activeTab: 0,
                        padding: '10px',
                        border: false,
                        items: [
                            {
                                height: 500,
                                // Fieldset in Column 1
                                xtype: 'fieldset',
                                title: Ext.ux.Ecodev.Newsletter.Language.status,
                                items: [
                                    {
                                        height: 500,
                                        xtype: 'dataview',
                                        store: Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\PlannedNewsletter'),
                                        emptyText: 'No text to display',
                                        tpl: new Ext.XTemplate(
                                                '<tpl for=".">',
                                                '<div>',
                                                '<h2>' + Ext.ux.Ecodev.Newsletter.Language.recent_activity + '</h2><p>{status}</p>',
                                                '<h2>' + Ext.ux.Ecodev.Newsletter.Language.newsletter_validity + '</h3>',
                                                '<h3>' + Ext.ux.Ecodev.Newsletter.Language.errors + '</h3>{errors}',
                                                '<h3>' + Ext.ux.Ecodev.Newsletter.Language.warnings + '</h3>{warnings}',
                                                '<h3>' + Ext.ux.Ecodev.Newsletter.Language.infos + '</h3>{infos}',
                                                '</div>',
                                                '</tpl>'
                                                )
                                    }]
                            },
                            {
                                title: Ext.ux.Ecodev.Newsletter.Language.settings,
                                xtype: 'panel',
                                labelWidth: 170,
                                height: 700,
                                items:
                                        [
                                            {
                                                // Fieldset in Column 1
                                                xtype: 'fieldset',
                                                columnWidth: 0.5,
                                                title: Ext.ux.Ecodev.Newsletter.Language.sender,
                                                defaults: {
                                                    anchor: '-20'
                                                }, // leave room for error icon
                                                defaultType: 'textfield',
                                                items:
                                                        [
                                                            {
                                                                xtype: 'hidden',
                                                                name: 'pid'
                                                            },
                                                            {
                                                                fieldLabel: Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_sender_name,
                                                                name: 'senderName',
                                                                allowBlank: false
                                                            },
                                                            {
                                                                fieldLabel: Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_sender_email,
                                                                name: 'senderEmail',
                                                                allowBlank: false
                                                            }
                                                        ]
                                            },
                                            {
                                                // Fieldset in Column 1
                                                xtype: 'fieldset',
                                                columnWidth: 0.5,
                                                title: Ext.ux.Ecodev.Newsletter.Language.advanced_settings,
                                                titleCollapse: true,
                                                collapsed: true,
                                                collapsible: true,
                                                autoHeight: true,
                                                defaults: {
                                                    anchor: '-20'  // leave room for error icon
                                                },
                                                defaultType: 'textfield',
                                                items:
                                                        [
                                                            {
                                                                xtype: 'combo',
                                                                fieldLabel: Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_bounce_account,
                                                                name: 'uidBounceAccount',
                                                                store: Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\BounceAccount'),
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
                                                                fieldLabel: Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_plain_converter,
                                                                name: 'plainConverter',
                                                                allowBlank: false,
                                                                store: new Ext.data.ArrayStore({
                                                                    idIndex: 0,
                                                                    fields: ['value', 'name'],
                                                                    data: [
                                                                        ['Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Builtin', Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_plain_converter_builtin],
                                                                        ['Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Lynx', Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_plain_converter_lynx]
                                                                    ]
                                                                }),
                                                                value: 0,
                                                                mode: 'local',
                                                                forceSelection: true,
                                                                triggerAction: 'all',
                                                                valueField: 'value',
                                                                displayField: 'name',
                                                                editable: false
                                                            },
                                                            {
                                                                xtype: 'combo',
                                                                fieldLabel: Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition,
                                                                name: 'repetition',
                                                                store: new Ext.data.ArrayStore({
                                                                    idIndex: 0,
                                                                    fields: ['value', 'name'],
                                                                    data: [
                                                                        [0, Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_none],
                                                                        [1, Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_daily],
                                                                        [2, Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_weekly],
                                                                        [3, Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_biweekly],
                                                                        [4, Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_monthly],
                                                                        [5, Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_quarterly],
                                                                        [6, Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_semiyearly],
                                                                        [7, Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_repetition_yearly]
                                                                    ]
                                                                }),
                                                                value: 0,
                                                                mode: 'local',
                                                                forceSelection: true,
                                                                triggerAction: 'all',
                                                                valueField: 'value',
                                                                displayField: 'name',
                                                                allowBlank: false,
                                                                editable: false
                                                            },
                                                            {
                                                                xtype: 'checkbox',
                                                                fieldLabel: Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_inject_open_spy,
                                                                name: 'injectOpenSpy'
                                                            },
                                                            {
                                                                xtype: 'checkbox',
                                                                fieldLabel: Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_inject_links_spy,
                                                                name: 'injectLinksSpy'
                                                            }
                                                        ]
                                            }
                                        ]
                            },
                            {
                                // Fieldset in Column 2 - Panel inside
                                layout: 'border',
                                title: Ext.ux.Ecodev.Newsletter.Language.sending,
                                header: false, // Do not want double title in tab + panel
                                items: [
                                    {
                                        region: 'center',
                                        xtype: 'fieldset',
                                        title: Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_recipientlist,
                                        layout: {type: 'vbox', align: 'stretch'},
                                        defaults: {
                                            anchor: '-20'  // leave room for error icon
                                        },
                                        items: [
                                            {
                                                xtype: 'combo',
                                                fieldLabel: Ext.ux.Ecodev.Newsletter.Language.tx_newsletter_domain_model_newsletter_recipient_list,
                                                name: 'uidRecipientList',
                                                store: Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\RecipientList'),
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
                                                    // When the combo is shown, simulate the selection of item (if any value exist) to also load the recipientList details
                                                    afterrender: function(combo) {
                                                        var recipientList = combo.getStore().getById(combo.getValue());
                                                        if (recipientList) {
                                                            combo.fireEvent('select', combo, recipientList, 0);
                                                        }
                                                    },
                                                    /**
                                                     * When an uidRecipientList is selected, we update other depending stores (recipients)
                                                     * TODO: it should be the depending stores listening to the uidRecipientList, but I couldn't
                                                     * find an easy way to access the uidRecipientList from the stores
                                                     */
                                                    select: function(combo, recipientList) {
                                                        var recipientStore = Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\Recipient');
                                                        recipientStore.load({params: {data: recipientList.data.__identity, start: 0, limit: 50}});
                                                    }
                                                }
                                            },
                                            {
                                                xtype: 'grid',
                                                loadMask: true,
                                                store: Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\Recipient'),
                                                flex: 1,
                                                stripeRows: true,
                                                disableSelection: true,
                                                // When the grid is ready, we add a listener to its store, so we can reconfigure
                                                // the grid whenever the store's metadata change, and thus updating available columns for the grid
                                                listeners: {
                                                    added: function(grid) {
                                                        grid.getStore().addListener('metachange', function(store, meta) {
                                                            var columns = [];
                                                            columns.push({
                                                                header: Ext.ux.Ecodev.Newsletter.Language.preview,
                                                                dataIndex: 'email',
                                                                renderer: function(value, parent, record) {

                                                                    var form = grid.findParentByType('form').getForm();
                                                                    var values = form.getFieldValues();
                                                                    var hasQuery = String(Ext.ux.Ecodev.Newsletter.Configuration.emailShowUrl).indexOf('?') > -1 ? '&' : '?';
                                                                    var url = String.format('{0}' +  hasQuery + 'pid={1}&uidRecipientList={2}&plainConverter={3}&injectOpenSpy={4}&injectLinksSpy={5}&email={6}',
                                                                            Ext.ux.Ecodev.Newsletter.Configuration.emailShowUrl,
                                                                            encodeURIComponent(values.pid),
                                                                            encodeURIComponent(values.uidRecipientList),
                                                                            encodeURIComponent(values.plainConverter),
                                                                            encodeURIComponent(values.injectOpenSpy ? '1' : '0'),
                                                                            encodeURIComponent(values.injectLinksSpy ? '1' : '0'),
                                                                            encodeURIComponent(value));

                                                                    // Append language if defined
                                                                    if (record.json.L) {
                                                                        url += '&L=' + record.json.L;
                                                                    }

                                                                    return String.format('<a href="{0}">{1}</a> | <a href="{0}&plain=1">{2}</a>', url, Ext.ux.Ecodev.Newsletter.Language.preview_html, Ext.ux.Ecodev.Newsletter.Language.preview_plain);
                                                                }
                                                            });
                                                            for (var i = 0; i < meta.fields.length; i++) {
                                                                columns.push({
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
                                                columns: [{header: '&nbsp;', width: 0, sortable: false, fixed: true, menuDisabled: true}], // No columns when loading, it will be dynamically set when the store is loaded and we know what columns are available

                                                // paging bar on the bottom
                                                bbar: new Ext.PagingToolbar({
                                                    pageSize: 50,
                                                    store: Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\Recipient'),
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
                                        split: true,
                                        region: 'south',
                                        height: 150,
                                        layout: {
                                            type: 'hbox',
                                            align: 'stretch'
                                        },
                                        items: [
                                            {
                                                flex: 0.5,
                                                xtype: 'fieldset',
                                                title: Ext.ux.Ecodev.Newsletter.Language.testing,
                                                items: [
                                                    {
                                                        xtype: 'panel',
                                                        items: [
                                                            {
                                                                xtype: 'displayfield',
                                                                html: Ext.ux.Ecodev.Newsletter.Language.testing_explanation
                                                            }]
                                                    },
                                                    {
                                                        xtype: 'button',
                                                        text: Ext.ux.Ecodev.Newsletter.Language.send_test_now,
                                                        handler: function(button) {
                                                            createNewsletter(button, true);
                                                        }
                                                    }
                                                ]
                                            },
                                            {
                                                flex: 0.5,
                                                xtype: 'fieldset',
                                                title: Ext.ux.Ecodev.Newsletter.Language.planning,
                                                items: [
                                                    {
                                                        xtype: 'xdatetime',
                                                        fieldLabel: Ext.ux.Ecodev.Newsletter.Language.date_start_sending,
                                                        name: 'plannedTime',
                                                        hiddenFormat: 'c',
                                                        labelStyle: 'width: auto;'
                                                    },
                                                    {
                                                        xtype: 'button',
                                                        text: Ext.ux.Ecodev.Newsletter.Language.add_to_queue,
                                                        handler: function(button) {
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
                            afterrender: function(formPanel) {
                                formPanel.getForm().doAction('directload', {
                                    // When the form loaded its data, we also store those data in our store
                                    // so they can be also be used by the dataview to display newsletter status
                                    success: function(form, action) {
                                        var plannedNewsletter = action.result.data;
                                        var plannedNewsletterStore = Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\PlannedNewsletter');
                                        plannedNewsletterStore.loadData({data: plannedNewsletter});
                                    }
                                });
                            }
                        }
            };
            Ext.apply(this, config);
            Ext.ux.Ecodev.Newsletter.Planner.Planner.superclass.initComponent.call(this);
        }

    });
    Ext.reg('Ext.ux.Ecodev.Newsletter.Planner.Planner', Ext.ux.Ecodev.Newsletter.Planner.Planner);
}());
