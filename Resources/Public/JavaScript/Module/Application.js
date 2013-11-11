"use strict";

Ext.ns("Ext.ux.TYPO3.Newsletter.Module");

/**
 * @class Ext.ux.TYPO3.Newsletter.Module.Application
 * @namespace Ext.ux.TYPO3.Newsletter.Module
 * @extends Ext.util.Observable
 *
 * The main entry point which controls the lifecycle of the application.
 *
 * This is the main event handler of the application.
 *
 * @singleton
 */

Ext.ux.TYPO3.Newsletter.Module.Application = Ext.apply(new Ext.util.Observable(), {
    /**
     * Main bootstrap. This is called by Ext.onReady.
     *
     * This method is called automatically.
     */
    bootstrap: function() {
        Ext.chart.Chart.CHART_URL = '/typo3/contrib/extjs/resources/charts.swf';

        Ext.QuickTips.init();

        // init Flashmessage
        Ext.ux.TYPO3.Newsletter.DirectFlashMessageDispatcher.initialize();
        Ext.ux.TYPO3.Newsletter.FlashMessageOverlayContainer.initialize({
            minDelay: 5,
            maxDelay: 15,
            logLevel: -1,
            opacity: 1
        });

        if (this.checkIfPage()) {
            this.initStore();
            this.initGui();
        } else if (this.checkIfPageIsFolder()) {
            this.initFolderGui();
        } else {
            this.initHelpGui();
        }
    },
    /**
     * Check if the application can be loaded
     *
     * @return {Boolean}
     */
    checkIfPage: function() {
        return Ext.ux.TYPO3.Newsletter.Configuration.pageType == 'page';
    },
    /**
     * Check if the application can be loaded
     *
     * @return {Boolean}
     */
    checkIfPageIsFolder: function() {
        return Ext.ux.TYPO3.Newsletter.Configuration.pageType == 'folder';
    },
    /**
     * Init menus and content area
     */
    initGui: function() {

        new Ext.Viewport({
            layout: 'fit',
            renderTo: Ext.getBody(),
            items: [{
                    id: 'main-tabs',
                    xtype: 'tabpanel',
                    activeTab: 0,
                    items: [{
                            xtype: 'Ext.ux.TYPO3.Newsletter.Planner.Planner',
                            iconCls: 't3-newsletter-button-planner',
                            api: {
                                load: Ext.ux.TYPO3.Newsletter.Remote.NewsletterController.listPlannedAction,
                                submit: Ext.ux.TYPO3.Newsletter.Remote.NewsletterController.createAction
                            }

                        }, {
                            xtype: 'Ext.ux.TYPO3.Newsletter.Statistics.Statistics',
                            iconCls: 't3-newsletter-button-statistics'
                        }]
                }]
        });
    },
    /**
     * Init ExtDirect stores
     */
    initStore: function() {
        Ext.ux.TYPO3.Newsletter.Store.Newsletter.initialize();
        Ext.ux.TYPO3.Newsletter.Store.SelectedNewsletter.initialize();
        Ext.ux.TYPO3.Newsletter.Store.PlannedNewsletter.initialize();
        Ext.ux.TYPO3.Newsletter.Store.Email.initialize();
        Ext.ux.TYPO3.Newsletter.Store.Link.initialize();
        Ext.ux.TYPO3.Newsletter.Store.BounceAccount.initialize();
        Ext.ux.TYPO3.Newsletter.Store.RecipientList.initialize();
        Ext.ux.TYPO3.Newsletter.Store.Recipient.initialize();
        Ext.ux.TYPO3.Newsletter.Store.TimelineChart.initialize();

        // pie chart depends on SelectedNewsletter store so it must be initialized after it
        Ext.ux.TYPO3.Newsletter.Store.OverviewPieChart.initialize();
    },
    /**
     * Init folder GUI
     */
    initFolderGui: function() {

        new Ext.Viewport({
            layout: 'fit',
            renderTo: Ext.getBody(),
            items: [
                {
                    height: 500,
                    xtype: 'panel',
                    html: [
                        '<div id="typo3-docheader">',
                        '<div id="typo3-docheader-row1">&nbsp;</div>',
                        '<div id="typo3-docheader-row2">&nbsp;</div>',
                        '</div>',
                        '<div id="typo3-docbody">',
                        '<div id="typo3-inner-docbody">',
                        '<h2>' + Ext.ux.TYPO3.Newsletter.Language.message_title_page_selected + '</h2>',
                        '<p>' + Ext.ux.TYPO3.Newsletter.Language.message_page_selected + '</p>',
                        '</div>',
                        '</div>'
                    ]
                }
            ]
        });
    },
    /**
     * Init help Gui
     */
    initHelpGui: function() {

        new Ext.Viewport({
            layout: 'fit',
            renderTo: Ext.getBody(),
            items: [
                {
                    height: 500,
                    xtype: 'panel',
                    html: [
                        '<div id="typo3-docheader">',
                        '<div id="typo3-docheader-row1">&nbsp;</div>',
                        '<div id="typo3-docheader-row2">&nbsp;</div>',
                        '</div>',
                        '<div id="typo3-docbody">',
                        '<div id="typo3-inner-docbody">',
                        '<h2>' + Ext.ux.TYPO3.Newsletter.Language.message_title_no_pid_selected + '</h2>',
                        '<p>' + Ext.ux.TYPO3.Newsletter.Language.message_no_pid_selected + '</p>',
                        '</div>',
                        '</div>'
                    ]
                }
            ]
        });
    }
});