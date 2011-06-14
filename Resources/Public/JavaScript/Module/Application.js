

Ext.ns("Ext.ux.TYPO3.Newsletter");

/**
 * @class Ext.ux.TYPO3.Newsletter.Module
 * @namespace Ext.ux.TYPO3.Newsletter
 * @extends Ext.util.Observable
 *
 * The main entry point which controls the lifecycle of the application.
 *
 * This is the main event handler of the application.
 *
 * First, it calls all registered bootstrappers, thus other modules can register event listeners.
 * Afterwards, the bootstrap procedure is started. During bootstrap, it will initialize:
 * <ul><li>QuickTips</li>
 * <li>History Manager</li></ul>
 *
 * @singleton
 *
 * $Id$
 */

Ext.ux.TYPO3.Newsletter.Module.Application = Ext.apply(new Ext.util.Observable(), {
	/**
	 * @event Ext.ux.TYPO3.Newsletter.Module.Application.afterBootstrap
	 * After bootstrap event. Should be used for main initialization.
	 */

	bootstrappers: [],

	/**
	 * Main bootstrap. This is called by Ext.onReady and calls all registered bootstraps.
	 *
	 * This method is called automatically.
	 */
	bootstrap: function() {
		//this._configureExtJs();
		//this._initializeExtDirect();
		this._registerEventDebugging();
		this._invokeBootstrappers();
		this._initStateProvider();
		this._initStateDefaultValue();
		this._registerEventBeforeLoading();
		this._registerEventAfterLoading();

		Ext.QuickTips.init();

		this.fireEvent('Ext.ux.TYPO3.Newsletter.Module.Application.afterBootstrap');

		this._initializeHistoryManager();
	},

	/**
	 * Hides the loading message of the application
	 *
	 */
	_registerEventBeforeLoading: function() {
		this.on(
			'Ext.ux.TYPO3.Newsletter.Module.Application.busy',
			function() {
				Ext.get('loading-mask').setStyle({
					visibility: 'visible',
					top: 0,
					left: 0,
					width: '100%',
					height: '100%',
					opacity: 0.4
				});
				Ext.get('loading').setStyle({
					visibility: 'visible',
					opacity: 1
				});
			},
			this
		)
	},
	/**
	 * Hides the loading message of the application
	 *
	 */
	_registerEventAfterLoading: function() {
		this.on(
			'Ext.ux.TYPO3.Newsletter.Module.Application.afterbusy',
			function() {
				var loading;
				loading = Ext.get('loading');

				//  Hide loading message
				loading.fadeOut({
					duration: 0.2,
					remove: false
				});

				//  Hide loading mask
				Ext.get('loading-mask').shift({
					xy: loading.getXY(),
					width: loading.getWidth(),
					height: loading.getHeight(),
					remove: false,
					duration: 0.35,
					opacity: 0,
					easing: 'easeOut'
				});
			},
			this
		)
	},

	/**
	 * Registers a new bootstrap class.
	 *
	 * Every bootstrap class needs to extend Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap.
	 * @param {Ext.ux.TYPO3.Newsletter.Module.AbstractBootstrap} bootstrap The bootstrap class to be registered.
	 * @api
	 */
	registerBootstrap: function(bootstrap) {
		this.bootstrappers.push(bootstrap);
	},

	/**
	 * Invoke the registered bootstrappers.
	 *
	 * @access private
	 * @return void
	 */
	_invokeBootstrappers: function() {
		Ext.each(this.bootstrappers, function(bootstrapper) {
			bootstrapper.initialize();
		});
	},

	/**
	 * Initialize History Manager
	 *
	 * @access private
	 * @return void
	 */
	_initializeHistoryManager: function() {
		Ext.History.on('change', function(token) {
			this.fireEvent('Ext.ux.TYPO3.Newsletter.Module.Application.navigate', token);
		}, this);

		// Handle initial token (on page load)
		Ext.History.init(function(history) {
			history.fireEvent('change', history.getToken());
		}, this);

		Ext.History.add(Ext.state.Manager.get('token'));
	},

	/**
	 * Register Event Debugging
	 *
	 * @access private
	 * @return void
	 */
	_registerEventDebugging: function() {
		Ext.util.Observable.capture(
			this,
			function(e) {
				if (window.console && window.console.log) {
					console.log(e, arguments);
				}
			}
		);
	},

	/**
	 * Initilize state provider
	 *
	 * @access private
	 * @return void
	 */
	_initStateProvider : function() {
		 // set days to be however long you think cookies should last
		var days,date;
		days = 0;		// 0 = expires when browser closes
		if (days) {
			date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			//exptime = "; expires=" + 'Sat, 26 Jun 2010 11:12:28 GMT';
		}

		// register provider with state manager.
		Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
			path: '/',
			expires: date,
			domain: null,
			secure: false
		}));
	},

	/**
	 * Define state default value
	 *
	 * @access private
	 * @return void
	 */
	_initStateDefaultValue : function() {
		if (!Ext.state.Manager.get('token')) {
			Ext.state.Manager.set('token', 'planner');
		}
	}

});