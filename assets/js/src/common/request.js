/* global Backbone, jQuery */
(function(win, $){
    'use strict';

    var View, Request;

    /**
    A simple notification message view that will be use when
    a request encounters a server error.
    **/
    View = Backbone.View.extend({
        className: 'notify-message',
        events: {
            'click': 'remove'
        },

        initialize: function() {
            this.render();
        },

        render: function() {
            var me;

            me = this;
            this.$el.html( win.codeandbeauty.messages.server_error ).appendTo('body');

            _.delay(function() {
                me.remove();
            }, 5000);
        }
    });

    /**
    Send or get server request/response.
    Required param:
        `action`    The request action or method name inside `CodeAndBeauty_Ajax` class that will be called
                    and executed.
    Useful hooks:
        `codeandbeauty:success_{ACTION_NAME}`   Fired whenever the request returns successfully.
            @param:
                (object) data       The response data in json format, if there's any.
        `codeandbeauty:error_{ACTION_NAME}`     Fired when the request is unsuccessful.
            @param:
                (object) data       Optional. The error data/message.
    **/
    Request = Backbone.Model.extend({
        url: win.codeandbeauty.ajaxurl + '?action=codeandbeauty_ajax_request',
        defaults: {
            _wpnonce: win.codeandbeauty._wpnonce
        },

        initialize: function () {
            this.on('error', this.serverError, this);

            Backbone.Model.prototype.initialize.apply(this, arguments);
        },

        parse: function ( response ) {
            var action = this.get('action');

            if ( response.success ) {
                this.trigger('codeandbeauty:success_' + action, response.data);
            } else {
                this.trigger('codeandbeauty:error_' + action, response.data);
            }
        },

        serverError: function () {
            var view = new View();
        }
    });

    // Make the request and view instance accessible anywhere
    win.codeandbeauty.Request = Request;
    win.codeandbeauty.ServerError = View;
})(window, jQuery);