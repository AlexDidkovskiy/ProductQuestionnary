define([
    'jquery',
    'mage/translate',
    'jquery/ui',
    'mage/validation/validation'
], function ($) {
    'use strict';
    
    $.widget('tsn.ajaxStyle', {
        options: {
            initConfig: {},
            popupWrapper: null,
            popup: null,
            popupForm: null,
            close: null,
            popupBlank: null,
            formKey: null,
            formKeyInputSelector: 'input[name="form_key"]',
            popupWrapperSelector: '#tsn-ajaxstyle-popup-wrapper',
            popupSelector: '#tsn-ajaxstyle-popup',
            popupBlankSelector: '#tsn-ajaxstyle-blank',
            closePopupButtonSelector: '#tsn-ajaxstyle-close',
            ajaxStyle: {
                processStart: 'processStart',
                processStop: 'processStop',
                enabled: true,
                popupTTL: null,
                animation: null,
                backgroundColor: '#ededed',
                headerBackgroundColor: '#4FC5C9',
                headerTextColor: '#fff',
                buttonTextColor: '#fff',
                buttonBackgroundColor: '#4FC5C9',
            }
        },

        _create: function () {
            this._bind();
        },

        _bind: function () {
            this.createElements();
            this.initEvents();
        },

        createElements: function () {
            this.options.popupWrapper = $(this.options.popupWrapperSelector);
            this.options.popupBlank = $(this.options.popupBlankSelector);
            this.options.close = $(this.options.closePopupButtonSelector);
            this.options.popup = $(this.options.popupSelector);
            this.createColorBG();
        },

        createColorBG: function () {
            var colorBackground = this.options.ajaxStyle.backgroundColor;
            this.options.popupWrapper.css('background-color', colorBackground);
        },

        initEvents: function () {
            var self = this;
            $(document).on('touchstart click', self.options.closePopupButtonSelector, function () {
                self.closePopup();
            }).on('keyup', function (e) {
                if (e.keyCode == 27) {
                    self.closePopup();
                }
            });

            $(window).on('resize', function () {
                self.center();
            });

            // Dragable
            self.options.popupWrapper.draggable();
        },

        animationSlide: function (section) {
            var self = this;
            var animation = this.options.ajaxLogin.slideAnimation;
            switch (animation) {
                case 'show':
                    section.show();
                    self.center();
                    break;
                case 'fade_fast':
                    section.fadeIn(1000);
                    self.center();
                    break;
                case 'fade_medium':
                    section.fadeIn(2000);
                    self.center();
                    break;
                case 'fade_slow':
                    section.fadeIn(3000);
                    self.center();
                    break;
                case 'slide_fast':
                    section.slideDown(1000);
                    setTimeout(function() {self.animateCenter(200);}, 1000);
                    break;
                case 'slide_medium':
                    section.slideDown(2000);
                    setTimeout(function() {self.animateCenter(500);}, 2000);
                    break;
                case 'slide_slow':
                    section.slideDown(3000);
                    setTimeout(function() {self.animateCenter(1000);}, 3000);
                    break;
                default:
                    section.show();
                    self.center();
                    break;
            }
        },

        animationPopup: function () {
            var topPos = Math.max(0, (($(window).height() - this.options.popupWrapper.outerHeight()) / 2));
            var leftPos = Math.max(0, (($(window).width() - this.options.popupWrapper.outerWidth()) / 2));
            
            var animation = this.options.ajaxStyle.animation;
            switch (animation) {
                case 'fade':
                    this.options.popupWrapper.css({
                        top: topPos + 'px',
                        left: leftPos + 'px',
                    }).fadeIn(2000);
                    break;
                case 'slide_top':
                    this.options.popupWrapper.css({
                        top: - this.options.popupWrapper.outerHeight() + 'px',
                        left: leftPos + 'px',
                    }).show();
                    this.animateCenter(1000);
                    break;
                case 'slide_bottom':
                    this.options.popupWrapper.css({
                        top: $(window).height() + 'px',
                        left: leftPos + 'px',
                    }).show();
                    this.animateCenter(1000);
                    break;
                case 'slide_left':
                    this.options.popupWrapper.css({
                        top: topPos + 'px',
                        left: - this.options.popupWrapper.outerWidth() + 'px',
                    }).show();
                    this.animateCenter(1000);
                    break;
                case 'slide_right':
                    this.options.popupWrapper.css({
                        top: topPos + 'px',
                        left: $(window).width() + 'px',
                    }).show();
                    this.animateCenter(1000);
                    break;
                default:
                    this.center();
                    this.options.popupWrapper.show();
                    break;
            }
            
            this.options.popupBlank.fadeIn('slow');
        },

        center: function () {
            var topPos = Math.max(0, (($(window).height() - this.options.popupWrapper.outerHeight()) / 2));
            var leftPos = Math.max(0, (($(window).width() - this.options.popupWrapper.outerWidth()) / 2));
            this.options.popupWrapper.css({
                'top': topPos + 'px',
                'left': leftPos + 'px'
            });
        },
        
        animateCenter: function (duration) {
            if (duration == null) {
                duration = 1000;
            }
            var topPos = Math.max(0, (($(window).height() - this.options.popupWrapper.outerHeight()) / 2));
            var leftPos = Math.max(0, (($(window).width() - this.options.popupWrapper.outerWidth()) / 2));
            this.options.popupWrapper.animate({
                'top': topPos + 'px',
                'left': leftPos + 'px'
            }, duration);
        },

        isLoaderEnabled: function () {
            return (this.options.ajaxStyle.processStart && this.options.ajaxStyle.processStop);
        },

        showElement: function (elmSelector) {
            this.options.popup.children().hide();
            this.options.popup.children(elmSelector).show();
            this.animationPopup();
        },
                
        makeColor: function() {
            this.options.popupWrapper.find('.tsn-login-popup-title').css('background-color', this.options.ajaxStyle.headerBackgroundColor);
            this.options.popupWrapper.find('.tsn-login-popup-title strong').css('color', this.options.ajaxStyle.headerTextColor);
            this.options.popupWrapper.find('button').css('color', this.options.ajaxStyle.buttonTextColor);
            this.options.popupWrapper.find('button').css('background-color', this.options.ajaxStyle.buttonBackgroundColor);
        },
        
        autoClosePopup: function(wrapper) {
            var self = this;
            if (self.options.ajaxStyle.popupTTL && wrapper.find('.ajaxstyle-autoclose-countdown').length > 0) {
                var ajaxstyle_autoclose_countdown = setInterval(function() {
                    var leftTimeNode = wrapper.find('.ajaxstyle-autoclose-countdown');
                    var leftTime = parseInt(leftTimeNode.text()) - 1;
                    leftTimeNode.text(leftTime);
                    if (leftTime <= 0) {
                        clearInterval(ajaxstyle_autoclose_countdown);
                        self.closePopup();
                    }
                }, 1000);
                wrapper.find('.tsn-ajaxstyle-close').click(function(event) {
                    clearInterval(ajaxstyle_autoclose_countdown);
                });
                $(self.options.closePopupButtonSelector).click(function() {
                    clearInterval(ajaxstyle_autoclose_countdown);
                });
            }
        },
        
        closePopup: function () {
            this.options.popupWrapper.fadeOut('slow');
            this.options.popupBlank.fadeOut('slow');
        }
    });

    return $.tsn.ajaxStyle;
});