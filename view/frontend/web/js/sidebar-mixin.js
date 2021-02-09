/**
 * Created by Q-Solutions Studio
 * Date: 21.07.2020
 *
 * @category    Magespices
 * @package     Magespices_GTMDataLayers
 * @author      Maciej Buchert <maciej@qsolutionsstudio.com>
 */

define([
    'jquery',
    'uiComponent'
], function($, Component) {
    'use strict';
    return function(target) {
        return $.widget('mage.sidebar', $.mage.sidebar, {
            options: {
                isRecursive: true,
            },

            _removeItemAfter: function (elem) {
                let product = this._getProductById(Number(elem.data('cart-item')));

                dataLayer.push({
                    "event": "removeFromCart",
                    "ecommerce": {
                        "currencyCode": product.product_price_currency_code,
                        "remove": {
                            "products": [{
                                "name": product.product_name,
                                "id": product.identifier,
                                "price": product.product_price_value,
                                "brand": product.producent,
                                "category": product.categories,
                                "variant": this._getProductVariantsAsString(product.options),
                                "quantity": product.qty
                            }]
                        }
                    }
                });

                return this._super(elem);
            },

            /**
             * @param {Array} options
             * @returns {String}
             */
            _getProductVariantsAsString: function (options) {
                let string = '',
                    iteration = options.length;

                for(let i  in options) {
                    let option = options[i];

                    string += option.label + ': ' + option.value;

                    if (--iteration) {
                        string += ',';
                    }
                }

                return string;
            },

        });
    }
});
