/**
 * Zendkofy
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Zendkofy
 * @package     Zendkofy_RecycleBinOrder
 * @copyright   Copyright © 2016 Zendkofy. All rights reserved.
 */
define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'mage/translate',
    'jquery/ui'
], function($, confirm, $t) {
    "use strict";

    $.widget('zendkofy.clickOpenWindow', {
        options: {
            URL: '',
            name: '_blank',
            specs: '',
            replace: false,
            allowConfirm: false,
            confirm: {
                title: $t('Delete items'),
                message: $t('Are you sure you want to delete this item ?'),
            }
        },

        _create: function() {
            var self = this, options = this.options;
            $(self.element).click(function(){
                if(options.allowConfirm) {
                    confirm({
                        title: options.confirm.title,
                        content: options.confirm.message,
                        actions: {
                            confirm: function () {
                                window.open(
                                    options.URL,
                                    options.name,
                                    options.specs,
                                    options.replace
                                );
                            }
                        }
                    });
                } else {
                    window.open(
                        options.URL,
                        options.name,
                        options.specs,
                        options.replace
                    );
                }
            });
        },
    });

    return $.zendkofy.clickOpenWindow;
});