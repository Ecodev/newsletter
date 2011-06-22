"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab");

/**
 * @class Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General
 * @namespace Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General = Ext.extend(Ext.Container, {

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
							store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_SelectedNewsletter'),
							emptyText: 'No text to display',
							tpl: new Ext.XTemplate(
								'<tpl for=".">',
									'<div class="t3-newsletter-span-statistic" style="margin-top: 30px;">',
										'<span class="t3-newsletter-span-statistic-sent">{emailCount}</span> ' + Ext.ux.TYPO3.Newsletter.Language.recipients,
									'</div>',
									'<div class="t3-newsletter-span-statistic">',
										'<span class="t3-newsletter-span-statistic-opened">{emailOpenedPercentage}%</span> ' + Ext.ux.TYPO3.Newsletter.Language.emails_opened,
									'</div>',
									'<div class="t3-newsletter-span-statistic">',
										'<span class="t3-newsletter-span-statistic-bounced">{emailBouncedPercentage}%</span> ' + Ext.ux.TYPO3.Newsletter.Language.emails_bounced,
									'</div>',
									'<div class="t3-newsletter-span-statistic" style="margin-top: 30px;">',
										Ext.ux.TYPO3.Newsletter.Language.started + '<span class="t3-newsletter-span-statistic" style="font-size: 120%">{beginTime}</span>',
									'</div>',
									'<div class="t3-newsletter-span-statistic">',
										Ext.ux.TYPO3.Newsletter.Language.ended + '<span class="t3-newsletter-span-statistic" style="font-size: 120%">{endTime}</span>',
									'</div>',
								'</tpl>'
							),
							border: false,
							width:250
						},
						{
							xtype: 'piechart',
							store: Ext.ux.TYPO3.Newsletter.Store.OverviewPieChart,
							dataField: 'data',
							categoryField: 'label',
							flex: 1,
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
						}
					]
				}
			]
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General.superclass.initComponent.call(this);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General);