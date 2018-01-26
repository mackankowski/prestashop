/**
 * NOTICE OF LICENSE
 *
 * @author    Ve Interactive <info@veinteractive.com>
 * @copyright 2017 Ve Interactive
 * @license   MIT License
 *
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

var veDataProcessor = veDataProcessor || {};

veDataProcessor = (function (window, document) {
    'use strict';

    /**
     * Add events on page element
     *
     * @param {type} event
     * @param {type} elem
     * @param {type} func
     */
    var addEvent = function (event, elem, func) {

        if (elem.addEventListener) { // W3C compatibility
            elem.addEventListener(event, func, false);
        } else if (elem.attachEvent) { // IE compatibility
            elem.attachEvent("on" + event, func);
        } else { // No much to do
            elem[event] = func;
        }
    };

    /**
     * Get elements from page by tag or regex
     *
     * @param {type} tag
     * @param {type} expr
     */
    var getElements = function (tag, expr) {

        var responseElems = [];
        var elems = [];
        var pattern = new RegExp(expr);
        tag.forEach(function (val) {
            elems.push(document.getElementsByTagName(val));
        });

        for (var i = 0; i < elems.length; i++) {
            for (var z = 0; z < elems[i].length; z++) {
                if (pattern.test(elems[i][z].name) ||
                    pattern.test(elems[i][z].className) ||
                    pattern.test(elems[i][z].id) ||
                    pattern.test(elems[i][z].type)) {
                    responseElems.push(elems[i][z]);
                }
            }
        }
        return responseElems;
    };

    /**
     * Add event on the input buttons to capture email, firstName and lastName values
     */
    var captureCustomerInfo = function () {

        var tag = ['input'];
        var elems = getElements(tag, /text|mail|email/igm);
        for (var i = 0; i < elems.length; i++) {
            addEvent('keyup', elems[i], function(currentEvent) {
                setNameEmail(this);
            });
            addEvent('click', elems[i], function(currentEvent) {
                setNameEmail(this);
            });
            addEvent('blur', elems[i], function(currentEvent) {
                setNameEmail(this);
            });
        }
        for (var j = 0; j < elems.length; j++) {
            setNameEmail(elems[j]);
        }
    };

    /**
     * Capture submit buttons and set the page type as register if the register button exists
     */
    var setRegisterPage = function () {
        var tag = ['button', 'input'];
        var elems = getElements(tag, /submit/);
        for (var i = 0; i < elems.length; i++) {
            if (typeof veData != 'undefined' && elems[i].name == 'submitAccount') {
                veData.currentPage.currentPageType = 'register';
            }
        }
    };

    /**
     * Set firstName, lastName and email in veData properties
     *
     * @param {type} textInput
     */
    var setNameEmail = function (textInput) {
        if (typeof veData != 'undefined') {
            if (checkEmailAddress(textInput.value)) {
                veData.user.email = textInput.value;
            }
            else if ((textInput.value).trim().length > 0) {
                var fnameFieldNames = ['customer_firstname', 'firstname'],
                    lnameFieldNames = ['customer_lastname', 'lastname'];
                if (fnameFieldNames.indexOf(textInput.name) != -1) {
                    if (textInput.name == 'customer_firstname' || textInput.name == 'firstname') {
                        var fName = document.getElementsByName("customer_firstname");
                        var fName2 = document.getElementsByName("firstname");
                        if (fName.length > 0) {
                            fName = fName[0].value;
                        }
                        else if (fName2.length > 0) {
                            fName = fName2[0].value;
                        }
                        veData.user.firstName = (fName).trim();
                    }
                }
                else if (lnameFieldNames.indexOf(textInput.name) != -1) {
                    veData.user.lastName = textInput.value.trim();
                }
            }
        }
    };

    /**
     * Check if input is an email
     * var reg = unicode_hack(/^[a-z\p{L}0-9!#$%&'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+[._a-z\p{L}0-9-]*\.[a-z\p{L}0-9]+$/i, false);
     * return reg.test(email);
     *
     * @param {type} email
     * @returns {Boolean}
     */
    var checkEmailAddress = function (email) {
        var pattern = new RegExp(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/);
        return pattern.test(email);
    };

    /**
     * Make an ajax call to update the veData.cart
     */
    var updateCart = function () {
        $.ajax({
            type: 'POST',
            url: baseDir + 'modules/veplatform/classes/ajax-response.php',
            data: 'method=updateCart',
            dataType: 'json',
            success: function (data) {
                if (data && veData.cart) {
                    veData.cart = data;
                }
            }
        });

    };

    /**
     * Add event on the cart update buttons
     */
    var captureCartUpdateButtons = function () {
        if (typeof ajaxCart == "undefined" || !ajaxCart instanceof Object) {
            var tag = ['button', 'a'];
            var elems = getElements(tag, /submit|remove_link|cart/igm);
            for (var i = 0; i < elems.length; i++) {
                addEvent('click', elems[i], function(currentEvent) {
                    updateCart();
                });
            }
        } else {
            var oldUpdateCart = ajaxCart.updateCart;
            ajaxCart.updateCart = function(jsonData) {
                oldUpdateCart(jsonData);
                updateCart();
            };
        }
    };

    return {
        captureCartUpdateButtons: captureCartUpdateButtons,
        captureCustomerInfo: captureCustomerInfo,
        updateCart: updateCart,
        setRegisterPage: setRegisterPage
    };


}(window, document));

window.onload = function () {
    veDataProcessor.captureCustomerInfo();
    veDataProcessor.captureCartUpdateButtons();
    veDataProcessor.setRegisterPage();

    // on Ajax calls reattach the events on the main buttons
    jQuery(document).on('ajaxComplete', function (event, xhr, settings) {
        if (settings.type.match(/post/i) && settings.url.indexOf('veplatform') == -1) {
            veDataProcessor.updateCart();
            setTimeout(function() {
                veDataProcessor.setRegisterPage();
                veDataProcessor.captureCustomerInfo();
                veDataProcessor.captureCartUpdateButtons();
            }, 4000);
        }
    });

};