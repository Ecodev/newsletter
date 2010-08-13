"use strict";

Ext.ns("TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General
 * @namespace TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General = Ext.extend(Ext.Container, {

	initComponent: function() {
		var config = {
//			width: 400,
//			height: 300,
//			style: "background-color: red",
			items: [
				{
					xtype: 'container',
					width: 500,
					height: 300,
					layout:'hbox',
					layoutConfig: {
						align: 'stretch'
//						pack: 'start',
					},
					items: [
						{
							xtype: 'dataview',
							store: TYPO3.Newsletter.Store.Statistics,
							emptyText: 'No text to display',
							tpl: new Ext.XTemplate(
								'<tpl for=".">',
									'<div class="t3-newsletter-span-statistic" style="margin-top: 30px;">',
										'<span class="t3-newsletter-span-statistic-sent">{number_of_recipients}</span> ' + TYPO3.Newsletter.Language.emails_sent,
									'</div>',
									'<div class="t3-newsletter-span-statistic">',
										'<span class="t3-newsletter-span-statistic-opened">{percent_of_opened}%</span> ' + TYPO3.Newsletter.Language.emails_opened,
									'</div>',
									'<div class="t3-newsletter-span-statistic">',
										'<span class="t3-newsletter-span-statistic-not-opened">{percent_of_not_opened}%</span> ' + TYPO3.Newsletter.Language.emails_not_opened,
									'</div>',
									'<div class="t3-newsletter-span-statistic">',
										'<span class="t3-newsletter-span-statistic-bounced">{percent_of_bounced}%</span> ' + TYPO3.Newsletter.Language.emails_bounced,
									'</div>',
									'<div class="t3-newsletter-span-statistic" style="margin-top: 30px;">',
										TYPO3.Newsletter.Language.started + '<span class="t3-newsletter-span-statistic" style="font-size: 120%">{begintime_formatted}</span>',
									'</div>',
									'<div class="t3-newsletter-span-statistic">',
										TYPO3.Newsletter.Language.ended + '<span class="t3-newsletter-span-statistic" style="font-size: 120%">{stoptime_formatted}</span>',
									'</div>',
								'</tpl>'
							),
							border: false,
							width:250
						},
						{
							xtype: 'piechart',
							store: TYPO3.Newsletter.Store.OverviewPieChart,
							dataField: 'total',
							categoryField: 'label',
							flex: 1,
							seriesStyles: {
								colors:['#078207','#25CDF2','#FFAA3C','#F8869D','#DEFE39']
							},
							//extra styles get applied to the chart defaults
							extraStyle:
							{
								legend:
								{
									display: 'bottom',
									padding: 5,
									font:
									{
										family: 'Tahoma',
										size: 10
									}
								},
								background:{
									color: '#EFEFF4'
								}
							}
						}
					]
				}
			]
		};
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General', TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General);