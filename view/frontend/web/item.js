define ([
	'jquery'
	, 'df'
	, 'Df_Checkout/js/action/place-order'
	, 'Df_Checkout/js/data'
	, 'Df_Core/js/redirectWithPost'
	, 'Magento_Checkout/js/model/payment/additional-validators'
 	, 'Magento_Checkout/js/view/payment/default'
], function(
	$, df, placeOrderAction, dfCheckout, redirectWithPost, additionalValidators, Component
) {
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
		 * Перекрыли родительский метод,
		 * чтобы подставить свой placeOrderAction вместо родительского.
		 * @override
		 * https://github.com/magento/magento2/blob/981d1f/app/code/Magento/Checkout/view/frontend/web/js/view/payment/default.js#L161-L165
		 * @return {jQuery.Deferred}
		*/
		getPlaceOrderDeferredObject: function() {
			return $.when(placeOrderAction(this.getData(), this.messageContainer, 'dfe-allpay'));
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
		 * @override
		 * https://github.com/magento/magento2/blob/981d1f/app/code/Magento/Checkout/view/frontend/web/js/view/payment/default.js#L127-L159
		 * @return {Boolean}
		*/
		placeOrder: function(data, event) {
			var _this = this;
			if (event) {
				event.preventDefault();
			}
			/** @type {Boolean} */
			var result = this.validate() || additionalValidators.validate();
			if (result) {
				this.isPlaceOrderActionAllowed(false);
				this.getPlaceOrderDeferredObject()
					.fail(function() {_this.isPlaceOrderActionAllowed(true);})
					.done(function(json) {
						 debugger;
					  	_this.afterPlaceOrder();
					 	/** @type {Object} */
						var data = $.parseJSON(json);
						// 2016-07-10
						// @see \Dfe\AllPay\Method::getConfigPaymentAction()
					  	redirectWithPost(data.uri, data.params);
					})
				;
			}
			return result;
		}
	});
});
