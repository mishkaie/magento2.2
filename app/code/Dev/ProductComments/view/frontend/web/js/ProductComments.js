define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';

    $.widget('devall.popupManager', {
        _create: function () {
            let self = this,
                popupOptions = {
                    responsive: true,
                    title: 'Popup Title Test',
                    buttons: false,
                    modalClass: 'popup-devnewsletter',
                    autoOpen: true,

                    closed: function () {
                        self._setCookie();
                    }
            };

            if (!$.cookie('popup_cookie')) {
                modal(popupOptions, $('#popup-devnewsletter'));
            }
        },

        _setCookie: function () {
            let expDate = new Date();

            expDate.setTime(expDate.getTime() + 3650000 * 60 * 1000);
            $.cookie('popup_cookie', 1, {
                expires: expDate
            });
        }
    });

    return $.devall.popupManager;
});
