(function() {
    "use strict";
    Ext.ns("Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel");
    /**
     * @class Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel.OverviewTab
     * @namespace Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel
     * @extends Ext.Container
     *
     * Class for statistic container
     */
    Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel.OverviewTab = Ext.extend(Ext.Container, {
        initComponent: function() {
            var config = {
                layout: 'border',
                items: [{
                        region: 'north',
                        split: true,
                        layout: 'hbox',
                        items: [{
                                width: 350,
                                height: 200,
                                xtype: 'dataview',
                                tpl: new Ext.XTemplate('<div id="pieChartContainer"><svg id="pieChart"></svg></div>'),
                                store: Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\SelectedNewsletter')
                            },
                            {
                                xtype: 'dataview',
                                store: Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\SelectedNewsletter'),
                                emptyText: 'No text to display',
                                tpl: new Ext.XTemplate(
                                        '<tpl for=".">',
                                        '<div class="t3-newsletter-statistic">',
                                        '<div class="t3-newsletter-statistic-group">',
                                        '<p><span class="sent">{[values.statistics[values.statistics.length - 1].emailCount]}</span> ' + Ext.ux.Ecodev.Newsletter.Language.recipients + '</p>',
                                        '<p><span class="opened">{[Math.round(values.statistics[values.statistics.length - 1].emailOpenedPercentage)]}%</span> ' + Ext.ux.Ecodev.Newsletter.Language.emails_opened + '</p>',
                                        '<p><span class="bounced">{[Math.round(values.statistics[values.statistics.length - 1].emailBouncedPercentage)]}%</span> ' + Ext.ux.Ecodev.Newsletter.Language.emails_bounced + '</p>',
                                        '<p><span class="link-opened">{[values.statistics[values.statistics.length - 1].linkOpenedPercentage.toFixed(2)]}%</span> ' + Ext.ux.Ecodev.Newsletter.Language.links_opened + '</p>',
                                        '</div>',
                                        '<div class="t3-newsletter-statistic-group">',
                                        '<p>' + Ext.ux.Ecodev.Newsletter.Language.planned_to_be_sent_on + ' <span class="plannedTime">{[values.plannedTime ? values.plannedTime.format("l Y-m-d H:i:s") : ""]}</span></p>',
                                        '<p>' + Ext.ux.Ecodev.Newsletter.Language.started + ' <span class="beginTime">{[values.beginTime ? values.beginTime.format("l Y-m-d H:i:s") : ""]}</span></p>',
                                        '</div>',
                                        '</div>',
                                        '</tpl>'
                                        )
                            }
                        ]
                    },
                    {
                        region: 'center',
                        xtype: 'panel',
                        html: '<div id="timelineChartContainer"><svg id="timelineChart"></svg></div>',
                        listeners: {
                            resize: function() {
                                var store = Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\SelectedNewsletter');
                                store.resizeChart();
                            }
                        }
                    }]
            };
            Ext.apply(this, config);
            Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel.OverviewTab.superclass.initComponent.call(this);
        }
    });

    Ext.reg('Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel.OverviewTab', Ext.ux.Ecodev.Newsletter.Statistics.StatisticsPanel.OverviewTab);
}());
