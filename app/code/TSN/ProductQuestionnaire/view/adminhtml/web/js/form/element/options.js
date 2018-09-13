define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
], function (_, uiRegistry, select, modal) {
    'use strict';

    return select.extend({

        /**
         * On value change handler.
         *
         * @param {String} value
         */
        onUpdate: function (value) {
            console.log('Selected Value: ' + value);

            var typeQuestion= uiRegistry.get('index = answers_variants');
            if (typeQuestion.visibleValue1 == value || typeQuestion.visibleValue2 == value ) {
                typeQuestion.show();
            } else {
                typeQuestion.hide();
            }

            return this._super();
        },
    });
});