Ext.ns("TYPO3.Newsletter");

/**
 * @class TYPO3.Newsletter.Application
 * @namespace TYPO3.Newsletter
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
 */
TYPO3.Newsletter.Application = Ext.apply(new Ext.util.Observable, {
	/**
	 * @event TYPO3.Newsletter.Application.afterBootstrap
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

		Ext.QuickTips.init();

		this.fireEvent('TYPO3.Newsletter.Application.afterBootstrap');
		
		this._initializeHistoryManager();
	},

	/**
	 * Registers a new bootstrap class.
	 *
	 * Every bootstrap class needs to extend TYPO3.Newsletter.Application.AbstractBootstrap.
	 * @param {TYPO3.Newsletter.Application.AbstractBootstrap} bootstrap The bootstrap class to be registered.
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
			this.fireEvent('TYPO3.Newsletter.Application.navigate', token);
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
		var days = 0;		// 0 = expires when browser closes
		var date = null;
		if(days){
			date = new Date();
			date.setTime(date.getTime() + (days*24*60*60*1000));
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
			Ext.state.Manager.set('token', 'newsletter');
		}
	}

});

Ext.onReady(TYPO3.Newsletter.Application.bootstrap, TYPO3.Newsletter.Application);