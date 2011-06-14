"use strict";

Ext.ns('Ext.ux.TYPO3.Newsletter');

/**
 * @class Ext.ux.TYPO3.Newsletter.Utils
 * @namespace Ext.ux.TYPO3.Newsletter
 *
 * Utility class
 * 
 * @singleton
 *
 * $Id$
 */
Ext.ux.TYPO3.Newsletter.Utils = {};

/**
 * Clone Function
 *
 * @param {Object/Array} o Object or array to clone
 * @return {Object/Array} Deep clone of an object or an array
 */
Ext.ux.TYPO3.Newsletter.Utils.clone = function(o) {
	if (!o || 'object' !== typeof o) {
		return o;
	}
	if ('function' === typeof o.clone) {
		return o.clone();
	}
	var c,p,v;
	c = '[object Array]' === Object.prototype.toString.call(o) ? [] : {};
	for (p in o) {
		if (o.hasOwnProperty(p)) {
			v = o[p];
			if (v && 'object' === typeof v) {
				c[p] = Ext.ux.TYPO3.Newsletter.Utils.clone(v);
			} else {
				c[p] = v;
			}
		}
	}
	return c;
};
