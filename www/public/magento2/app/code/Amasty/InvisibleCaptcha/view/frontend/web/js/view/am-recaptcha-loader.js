/**
 * reCaptcha API loader
 */

define([
    'Amasty_InvisibleCaptcha/js/model/am-recaptcha'
], function (amReCaptchaModel) {
    'use strict';

    return {
        /**
         *  Add script tag.
         * @returns {void}
         */
        addReCaptchaScript: function () {
            var element,
                scriptTag;

            if (amReCaptchaModel.isScriptAdded) {
                return;
            }

            scriptTag = document.getElementsByTagName('head')[0];
            element = document.createElement('script');
            element.async = true;
            element.src = this.getUrl();
            scriptTag.appendChild(element);
            amReCaptchaModel.isScriptAdded = true;
        },

        /**
         * Build url for captcha loading
         * @returns {string}
         */
        getUrl: function () {
            return amReCaptchaModel.url + '?onload=' + amReCaptchaModel.onLoadCallback
                + '&render=explicit' + amReCaptchaModel.lang;
        }
    };
});
