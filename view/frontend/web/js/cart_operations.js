/**
 * Created by Q-Solutions Studio
 * Date: 09.07.2020
 *
 * @category    Magespices
 * @package     Magespices_GTMDataLayers
 * @author      Maciej Buchert <maciej@qsolutionsstudio.com>
 */

define(
    [
        'jquery',
        'underscore',
        'uiComponent',
        'Magento_Customer/js/customer-data',
        'mage/url'
    ],
    function ($, _, Component, customerData, url) {
        return Component.extend(
            {
                /**
                 * Init
                 */
                initialize: function (config, element) {
                    let self = this,
                        cart = customerData.get('cart'),
                        items = cart().items,
                        loaded = false;

                    cart.subscribe(function () {
                        if(items === undefined) {
                            if(cart().items.length > 0 && loaded) {
                                let product = cart().items[0];

                                dataLayer.push({
                                    "event": "addToCart",
                                    "ecommerce": {
                                        "currencyCode": config.currency_code,
                                        "add": {
                                            "products": [{
                                                "name": product.product_name,
                                                "id": product.identifier,
                                                "price": String(product.product_price_value),
                                                "brand": product.brand,
                                                "category": product.categories,
                                                "variant": self.getProductVariantsAsString(product.options),
                                                "quantity": product.qty
                                            }]
                                        }
                                    }
                                });
                            }
                        } else if(typeof items === 'object') {
                            if(cart().items.length > items.length) {
                                let product = self.getChangedProduct(items.sort(), cart().items.sort());
                                dataLayer.push({
                                    "event": "addToCart",
                                    "ecommerce": {
                                        "currencyCode": config.currency_code,
                                        "add": {
                                            "products": [{
                                                "name": product.product_name,
                                                "id": product.identifier,
                                                "price": String(product.product_price_value),
                                                "brand": product.brand,
                                                "category": product.categories,
                                                "variant": self.getProductVariantsAsString(product.options),
                                                "quantity": product.qty
                                            }]
                                        }
                                    }
                                });
                            }
                        }

                        if(!loaded) {
                            loaded = true;
                        }
                        items = cart().items;
                    });

                    // remove from cart table on cart site
                    $('#shopping-cart-table').on('click', '.action-delete',function(e) {
                        let dataPost = JSON.parse($(this).attr('data-post'));
                        for(let i in cart().items) {
                            if(cart().items[i].item_id === dataPost.data.id) {
                                let product = cart().items[i];
                                dataLayer.push({
                                    "event": "removeFromCart",
                                    "ecommerce": {
                                        "currencyCode": config.currency_code,
                                        "remove": {
                                            "products": [{
                                                "name": product.product_name,
                                                "id": product.identifier,
                                                "price": product.product_price_value,
                                                "brand": product.brand,
                                                "category": product.categories,
                                                "variant": self.getProductVariantsAsString(product.options),
                                                "quantity": product.qty
                                            }]
                                        }
                                    }
                                });
                            }
                        }
                    });

                    // empty cart event
                    $('#empty_cart_button').click(function(e) {
                        let products = [];

                        for(let i in items) {
                            let product = items[i];
                            products.push({
                                "name": product.product_name,
                                "id": product.identifier,
                                "price": product.product_price_value,
                                "brand": product.brand,
                                "category": product.categories,
                                "variant": self.getProductVariantsAsString(product.options),
                                "quantity": product.qty
                            });
                        }

                        dataLayer.push({
                            "event": "removeFromCart",
                            "ecommerce": {
                                "currencyCode": config.currency_code,
                                "remove": {
                                    "products": products
                                }
                            }
                        });
                    });
                },

                /**
                 * @param {Array} options
                 * @returns {String}
                 */
                getProductVariantsAsString: function (options) {
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

                /**
                 * @param {Array} oldArray
                 * @param {Array} newArray
                 * @returns {null|Integer}
                 */
                getChangedProduct: function (oldArray, newArray) {
                    let diff = null,
                        firstArray = oldArray.length === newArray.length ? oldArray : (oldArray.length <= newArray.length ? newArray : oldArray),
                        secondArray = oldArray.length === newArray.length ? newArray : (newArray.length <= oldArray.length ? newArray : oldArray),
                        firstItemIds = [],
                        secondItemIds = [];

                    if(secondArray.length === 0) {
                        return firstArray[0];
                    }

                    for(let i in firstArray) {
                        firstItemIds.push(firstArray[i].item_id);
                    }

                    for(let i in secondArray) {
                        secondItemIds.push(secondArray[i].item_id);
                    }

                    let difference = firstItemIds.filter(x => !secondItemIds.includes(x));

                    for(let i in firstArray) {
                        if(firstArray[i].item_id === difference[0]) {
                            diff = firstArray[i];
                        }
                    }

                    return diff;
                }
            }
        );
    }
);
