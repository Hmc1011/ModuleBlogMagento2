define([
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (Component,customerData) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            this.custom_comment_section = customerData.get('custom_comment_section');
            // customerData.reload('custom_comment_section');
            console.log(customerData.get('custom_comment_section'));
        }
    });

    

});