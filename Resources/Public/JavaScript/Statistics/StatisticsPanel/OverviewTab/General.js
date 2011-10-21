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
			layout: 'column',
			items: [{
				items:[{
					width: 1000,
					height: 300,
					xtype: 'linechart',
					store: Ext.StoreMgr.get('Tx_Newsletter_Timeline_Chart'),
					xField: 'time',
					series: [
						{
							yField: 'not_sent',
							displayName: Ext.ux.TYPO3.Newsletter.Language.not_sent,
							style: { color: '#CCCCCC' }
						}
						,
						{
							yField: 'sent',
							displayName: Ext.ux.TYPO3.Newsletter.Language.sent,
							style: { color: '#25CDF2' }
						},
						{
							yField: 'opened',
							displayName: Ext.ux.TYPO3.Newsletter.Language.opened,
							style: { color: '#078207' }
						},
						{
							yField: 'bounced',
							displayName: Ext.ux.TYPO3.Newsletter.Language.bounced,
							style: { color: '#E01B4C' }
						}
					]
				}
				]
			},{
				items:[{
					width: 300,
					height: 300,
					xtype: 'piechart',
					store: Ext.ux.TYPO3.Newsletter.Store.OverviewPieChart,
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
				}
				]
			},
			{
				columnWidth: 1,
				items:[{
					xtype: 'dataview',
					store: Ext.StoreMgr.get('Tx_Newsletter_Domain_Model_SelectedNewsletter'),
					emptyText: 'No text to display',
					tpl: new Ext.XTemplate(
						'<tpl for=".">',
						'<div class="t3-newsletter-statistic">',
							'<div class="t3-newsletter-statistic-group">',
								'<p><span class="sent">{emailCount}</span> ' + Ext.ux.TYPO3.Newsletter.Language.recipients + '</p>',
								'<p><span class="opened">{emailOpenedPercentage}%</span> ' + Ext.ux.TYPO3.Newsletter.Language.emails_opened + '</p>',
								'<p><span class="bounced">{emailBouncedPercentage}%</span> ' + Ext.ux.TYPO3.Newsletter.Language.emails_bounced + '</p>',
							'</div>',
							'<div class="t3-newsletter-statistic-group">',
								'<p>' +  Ext.ux.TYPO3.Newsletter.Language.planned_to_be_sent_on + ' <span class="plannedTime">{plannedTime}</span></p>',
								'<p>' + Ext.ux.TYPO3.Newsletter.Language.started + ' <span class="beginTime">{beginTime}</span></p>',
							'</div>',
						'</div>',
						'</tpl>'
						)
				}]
			}]
		};
		Ext.apply(this, config);
		Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General.superclass.initComponent.call(this);
	}
});

Ext.reg('Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General', Ext.ux.TYPO3.Newsletter.Statistics.StatisticsPanel.OverviewTab.General);