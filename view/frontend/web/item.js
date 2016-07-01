define ([
	'Magento_Checkout/js/view/payment/default'
	,'jquery'
	, 'df'
	, 'Df_Checkout/js/data'
	, 'mage/translate'
	, 'underscore'
], function(Component, $, df, dfCheckout, $t, _) {
	'use strict';
	return Component.extend({
		defaults: {
			active: false
			,clientConfig: {id: 'dfe-all-pay'}
			,code: 'dfe_all_pay'
			,template: 'Dfe_AllPay/item'
		},
		imports: {onActiveChange: 'active'},
		/**
		 * 2016-07-01
		 * @param {?String} key
		 * @returns {Object}|{*}
	 	 */
		config: function(key) {
			/** @type {Object} */
			var result =  window.checkoutConfig.payment[this.getCode()];
			return !key ? result : result[key];
		},
		/** @returns {String} */
		getCode: function() {return this.code;},
		/**
		 * 2016-07-01
   		 * @override
   		 */
		getData: function() {
			return {
				additional_data: {}
				,method: this.item.method
			};
		},
		/**
		 * 2016-07-01
		 * @return {String}
		*/
		getTitle: function() {
			var result = this._super();
			return result + (!this.isTest() ? '' : ' [<b>TEST MODE</b>]');
		},
		/**
		 * 2016-07-01
		 * @return {Boolean}
		 */
		isTest: function() {return this.config('isTest');},
		/**
		 * 2016-07-01
		 */
		afterPlaceOrder: function() {
			debugger;
		}
	});
});
