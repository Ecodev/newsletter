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
					width: 400,
					height: 300,
					layout:'hbox',
					layoutConfig: {
						align: 'stretch'
//						pack: 'start',
					},
					items: [
//						{
//							xtype: 'button',
//							text: 'asdf',
////							flex:1
//						},
						{
							xtype: 'dataview',
							store: new Ext.data.SimpleStore({
								fields: [
								   {name: 'name'},
								   {name: 'url'},
								   {name: 'vorname'},
								   {name: 'alter'}
								],
								data: [["xx", "aa", "fff", "cco"]]
							}),

							emptyText: 'No text to display',
							tpl: new Ext.XTemplate(
								'<tpl for=".">',
									'<div class="thumb-wrap" id="{name}">',
										'<span>Name: {name} {vorname}, {alter} Jahre',
									'</div>',
								'</tpl>'
							),
							border: false,
							width:150
						},
						{
							xtype: 'piechart',
							store: TYPO3.Newsletter.Store.OverviewPieChart,
							dataField: 'total',
							categoryField: 'label',
							flex:1,
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