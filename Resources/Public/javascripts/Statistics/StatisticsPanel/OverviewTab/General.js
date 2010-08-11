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
			style: "background-color: red",
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

							html:'panel 2',
							emptyText: 'No images to display',
							tpl: new Ext.XTemplate(
								'<tpl for=".">',
									'<div class="thumb-wrap" id="{name}">',
									'<span>Name: {name} {vorname}, {alter} Jahre',
									'<input type="button" name="addButton" value="Add"/>',
									'</div>',
								'</tpl>'
							),
							border: false,
							width:150
						},
						{
							flex:1,
							store: new Ext.data.JsonStore({
								fields: ['season', 'total'],
								data: [{
									season: 'Summer',
									total: 150
								},{
									season: 'Fall',
									total: 245
								},{
									season: 'Winter',
									total: 117
								},{
									season: 'Spring',
									total: 184
								}]
							}),
							xtype: 'piechart',
							dataField: 'total',
							categoryField: 'season',
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
										size: 13
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