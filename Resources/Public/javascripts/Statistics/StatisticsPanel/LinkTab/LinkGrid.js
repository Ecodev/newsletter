"use strict";

Ext.ns("TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab");

/**
 * @class TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid
 * @namespace TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab
 * @extends Ext.Container
 *
 * Class for statistic container
 *
 * $Id$
 */
TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid = Ext.extend(Ext.grid.GridPanel, {

	initComponent: function() {
//		var config = {
//			width: 400,
//			style: "background-color: blue",
//			items: [
//				{
//					xtype: 'button',
//					text: 'asdf'
//				}
//			]
//		};

		var config = {
			// store
            store:new Ext.data.JsonStore({
                 id:'persID'
                ,root:'rows'
                ,totalProperty:'totalCount'
//                ,url:'process-request.php'
                ,baseParams:{cmd:'getData', objName:'person'}
                ,fields:[
                     {name:'persID', type:'int'}
                    ,{name:'persFirstName', type:'string'}
                    ,{name:'persMidName', type:'string'}
                    ,{name:'persLastName', type:'string'}
                    ,{name:'persNote', type:'string'}
                    ,{name:'phones', type:'string'}
                ]
            })

            // column model
            ,columns:[{
                 dataIndex:'persFirstName'
                ,header:'First'
                ,width:50
            },{
                 dataIndex:'persMidName'
                ,header:'Middle'
                ,width:40
            },{
                 dataIndex:'persLastName'
                ,header:'Last'
                ,width:80
//                ,renderer:this.renderLastName.createDelegate(this)
            },{
                 dataIndex:'persNote'
                ,header:'Note'
                ,width:200
            }]

            // force fit
            ,viewConfig:{forceFit:true, scrollOffset:0}

            // tooltip template
            ,qtipTpl:new Ext.XTemplate(
                 '<h3>Phones:</h3>'
                ,'<tpl for=".">'
                ,'<div><i>{phoneType}:</i> {phoneNumber}</div>'
                ,'</tpl>'
            )
        }; // eo config object
		Ext.apply(this, config);
		TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid.superclass.initComponent.call(this);
	}
});

Ext.reg('TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid', TYPO3.Newsletter.Statistics.StatisticsPanel.LinkTab.LinkGrid);