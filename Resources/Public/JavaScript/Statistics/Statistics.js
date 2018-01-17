(function () {
    'use strict';

    Ext.ns('Ext.ux.Ecodev.Newsletter.Statistics');

    /**
     * @class Ext.ux.Ecodev.Newsletter.Statistics.Statistics
     * @namespace Ext.ux.Ecodev.Newsletter.Statistics
     * @extends Ext.Container
     *
     * Class for statistic container
     */
    Ext.ux.Ecodev.Newsletter.Statistics.Statistics = Ext.extend(Ext.Container, {
        initComponent: function () {
            var config = {
                layout: 'border',
                title: Ext.ux.Ecodev.Newsletter.Language.statistics_tab,
                items: [
                    {
                        split: true,
                        region: 'north',
                        xtype: 'Ext.ux.Ecodev.Newsletter.Statistics.NewsletterListMenu',
                        ref: 'newsletterListMenu',
                    },
                    {
                        region: 'center',
                        xtype: 'Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel',
                        ref: 'statisticsPanel',
                    },
                ],
            };
            Ext.apply(this, config);
            Ext.ux.Ecodev.Newsletter.Statistics.Statistics.superclass.initComponent.call(this);
        },
    });

    Ext.reg('Ext.ux.Ecodev.Newsletter.Statistics.Statistics', Ext.ux.Ecodev.Newsletter.Statistics.Statistics);
}());
