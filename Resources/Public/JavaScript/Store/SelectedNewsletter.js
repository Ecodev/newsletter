"use strict";

Ext.ns('Ext.ux.Ecodev.Newsletter.Store');

/**
 * A Store for the selectedNewsletter model using ExtDirect to communicate with the
 * server side extbase framework.
 */
Ext.ux.Ecodev.Newsletter.Store.SelectedNewsletter = function() {

    var selectedNewsletterStore = null;

    var initialize = function() {
        if (selectedNewsletterStore == null) {
            var newsletterStore = Ext.StoreMgr.get('Ecodev\\Newsletter\\Domain\\Model\\Newsletter');
            selectedNewsletterStore = new Ext.data.DirectStore({
                storeId: 'Ecodev\\Newsletter\\Domain\\Model\\SelectedNewsletter',
                // Here we use the same JsonReader as NewsletterStore to
                // get the exact same definition of fields as both store have same RecordType
                reader: newsletterStore.reader,
                api: {
                    read: Ext.ux.Ecodev.Newsletter.Remote.NewsletterController.statisticsAction
                },
                paramOrder: {
                    read: ['data']
                },
                restful: false,
                batch: false,
                remoteSort: false
            });

            var timelineChart;

            selectedNewsletterStore.resizeChart = function() {
                if (timelineChart) {
                    timelineChart.update();
                }
            };

            var createTimelineChart = function(statistics) {

                // Reorganise data for graph
                var emailNotSentPercentage = [];
                var emailSentPercentage = [];
                var emailOpenedPercentage = [];
                var emailBouncedPercentage = [];
                var linkOpenedPercentage = [];
                Ext.each(statistics, function(a, b) {
                    emailNotSentPercentage.push({x: a.time, y: a.emailNotSentCount});
                    emailSentPercentage.push({x: a.time, y: a.emailSentCount});
                    emailOpenedPercentage.push({x: a.time, y: a.emailOpenedCount});
                    emailBouncedPercentage.push({x: a.time, y: a.emailBouncedCount});
                    linkOpenedPercentage.push({x: a.time, y: a.linkOpenedCount});
                });

                var timelineData = [
                    {
                        key: Ext.ux.Ecodev.Newsletter.Language.link_opened,
                        values: linkOpenedPercentage,
                        color: '#FFB61B',
                        disabled: true

                    },
                    {
                        key: Ext.ux.Ecodev.Newsletter.Language.bounced,
                        values: emailBouncedPercentage,
                        color: '#E01B4C'
                    },
                    {
                        key: Ext.ux.Ecodev.Newsletter.Language.opened,
                        values: emailOpenedPercentage,
                        color: '#078207'
                    },
                    {
                        key: Ext.ux.Ecodev.Newsletter.Language.sent,
                        values: emailSentPercentage,
                        color: '#25CDF2'
                    },
                    {
                        key: Ext.ux.Ecodev.Newsletter.Language.not_sent,
                        values: emailNotSentPercentage,
                        color: '#CCCCCC'
                    }
                ];

                nv.addGraph(function() {
                    timelineChart = nv.models.stackedAreaChart()
                            .useInteractiveGuideline(true)
                            .x(function(d) {
                                return d.x;
                            })
                            .y(function(d) {
                                return d.y;
                            })
                            .transitionDuration(300);

                    timelineChart.xAxis
                            .tickFormat(function(d) {
                                return d3.time.format("%Y-%m-%d %H:%M")(new Date(d * 1000));
                            });

                    timelineChart.yAxis
                            .tickFormat(d3.format(",f"));

                    d3.select('#timelineChart')
                            .datum(timelineData)
                            .transition().duration(1000)
                            .call(timelineChart);

                    // by default, disable the serie of clicked links (because it mess with stacked percentage)
                    var state = timelineChart.state();
                    state.disabled[0] = true;
                    timelineChart.dispatch.changeState(state);
                    timelineChart.update();

                    return timelineChart;
                });
            };

            var createPieChart = function(statistics) {
                var mostRecentState = statistics[statistics.length - 1];
                var pieData = [
                    {
                        label: Ext.ux.Ecodev.Newsletter.Language.not_sent,
                        value: mostRecentState.emailNotSentCount
                    },
                    {
                        label: Ext.ux.Ecodev.Newsletter.Language.sent,
                        value: mostRecentState.emailSentCount
                    },
                    {
                        label: Ext.ux.Ecodev.Newsletter.Language.opened,
                        value: mostRecentState.emailOpenedCount
                    },
                    {
                        label: Ext.ux.Ecodev.Newsletter.Language.bounced,
                        value: mostRecentState.emailBouncedCount
                    }
                ];


                nv.addGraph(function() {
                    var pieChart = nv.models.pieChart().width(350).height(200)
                            .x(function(d) {
                                return d.label;
                            })
                            .y(function(d) {
                                return d.value;
                            })
                            .showLabels(false)
                            .color(['#CCCCCC', '#25CDF2', '#078207', '#E01B4C'])
                            .valueFormat(d3.format(",f"));

                    d3.select("#pieChart")
                            .datum(pieData)
                            .transition().duration(350)
                            .call(pieChart);

                    return pieChart;
                });

            };

            // When a newsletter is selected, we update the timeline chart
            selectedNewsletterStore.on(
                    'datachanged',
                    function(selectedNewsletterStore) {

                        var newsletter = selectedNewsletterStore.getAt(0);
                        var statistics = newsletter.json.statistics;
                        createTimelineChart(statistics);
                        createPieChart(statistics);
                    }
            );
        }
    };

    /**
     * Public API of this singleton.
     */
    return {
        initialize: initialize
    };
}();
