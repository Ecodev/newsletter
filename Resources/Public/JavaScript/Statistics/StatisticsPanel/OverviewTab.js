"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel
 * @extends Ext.Container
 *
 * Class for statistic container
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
			layout: 'border',
			items: [{
				region: 'north',
				split: true,
				layout: 'hbox',
				items:[{
					width: 350,
					height: 200,
					xtype: 'piechart',
					store: Ext.StoreMgr.get('Tx_Newsletter_Overview_Pie_Chart'),
					dataField: 'data',
					categoryField: 'label',
					seriesStyles: {
						colors:['#CCCCCC', '#25CDF2', '#078207', '#E01B4C']
					},
					//extra styles get applied to the chart defaults
					extraStyle:
					{
						legend:
						{
							display: 'bottom'
						},
						background:{
							color: '#EFEFF4'
						}
					}
				},
				{
					xtype: 'dataview',
					store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_SelectedNewsletter'),
					emptyText: 'No text to display',
					tpl: new Ext.XTemplate(
						'<tpl for=".">',
						'<div class="t3-newsletter-statistic">',
							'<div class="t3-newsletter-statistic-group">',
								'<p><span class="sent">{[values.statistics[values.statistics.length - 1].emailCount]}</span> ' + Ext.ux.TYPO3.Newsletter.Language.recipients + '</p>',
								'<p><span class="opened">{[Math.round(values.statistics[values.statistics.length - 1].emailOpenedPercentage)]}%</span> ' + Ext.ux.TYPO3.Newsletter.Language.emails_opened + '</p>',
								'<p><span class="bounced">{[Math.round(values.statistics[values.statistics.length - 1].emailBouncedPercentage)]}%</span> ' + Ext.ux.TYPO3.Newsletter.Language.emails_bounced + '</p>',
								'<p><span class="link-opened">{[values.statistics[values.statistics.length - 1].linkOpenedPercentage.toFixed(2)]}%</span> ' + Ext.ux.TYPO3.Newsletter.Language.links_opened + '</p>',
							'</div>',
							'<div class="t3-newsletter-statistic-group">',
								'<p>' +  Ext.ux.TYPO3.Newsletter.Language.planned_to_be_sent_on + ' <span class="plannedTime">{[values.plannedTime ? values.plannedTime.format("l Y-m-d H:i:s") : ""]}</span></p>',
								'<p>' + Ext.ux.TYPO3.Newsletter.Language.started + ' <span class="beginTime">{[values.beginTime ? values.beginTime.format("l Y-m-d H:i:s") : ""]}</span></p>',
							'</div>',
						'</div>',
						'</tpl>'
						)
				}
				]
			},
			{
				region: 'center',
				xtype: 'linechart',
				store: Ext.StoreMgr.get('Tx_Newsletter_Timeline_Chart'),
				xField: 'time',

				yAxis: new Ext.chart.NumericAxis({
					majorUnit: 20
				}),
				xAxis: new Ext.chart.TimeAxis({
					labelRenderer: function(date) { return date.format("Y-m-d H:i:s"); }
				}),
				series: [
					{
						yField: 'emailNotSentPercentage',
						displayName: Ext.ux.TYPO3.Newsletter.Language.not_sent,
						style: { color: '#CCCCCC' }
					},
					{
						yField: 'emailSentPercentage',
						displayName: Ext.ux.TYPO3.Newsletter.Language.sent,
						style: { color: '#25CDF2' }
					},
					{
						yField: 'emailOpenedPercentage',
						displayName: Ext.ux.TYPO3.Newsletter.Language.opened,
						style: { color: '#078207' }
					},
					{
						yField: 'emailBouncedPercentage',
						displayName: Ext.ux.TYPO3.Newsletter.Language.bounced,
						style: { color: '#E01B4C' }
					},
					{
						yField: 'linkOpenedPercentage',
						displayName: Ext.ux.TYPO3.Newsletter.Language.link_opened,
						style: { color: '#FFB61B' }
					}
				]
			}]
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.superclass.initComponent.call(this);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab);