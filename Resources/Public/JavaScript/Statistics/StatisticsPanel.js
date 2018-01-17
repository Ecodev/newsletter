(function () {
    'use strict';

    Ext.ns('Ext.ux.Ecodev.Newsletter.Statistics');

    /**
     * @class Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel
     * @namespace Ext.ux.Ecodev.Newsletter.Statistics
     * @extends Ext.TabPanel
     *
     * Class for statistic tab panel
     */
    Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel = Ext.extend(Ext.TabPanel, {
        initComponent: function () {

            var config = {
                activeTab: 0,
                border: false,
                items: [
                    {
                        title: Ext.ux.Ecodev.Newsletter.Language.overview_tab,
                        xtype: 'Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel.OverviewTab',
                        itemId: 'overviewTab',
                    },
                    {
                        title: Ext.ux.Ecodev.Newsletter.Language.emails_tab,
                        xtype: 'Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel.EmailTab',
                        itemId: 'emailTab',
                    },
                    {
                        title: Ext.ux.Ecodev.Newsletter.Language.links_tab,
                        xtype: 'Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel.LinkTab',
                        itemId: 'linkTab',
                    },
                ],
            };
            Ext.apply(this, config);
            Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel.superclass.initComponent.call(this);
        },

    });

    Ext.reg('Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel', Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel);
}());
