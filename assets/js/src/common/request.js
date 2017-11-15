/* global Backbone */
(function(win){
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
            this.$el.html( win.precodeandbeauty.messages.server_error ).appendTo('body');

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
        `precodeandbeauty:success_{ACTION_NAME}`   Fired whenever the request returns successfully.
            @param:
                (object) data       The response data in json format, if there's any.
        `precodeandbeauty:error_{ACTION_NAME}`     Fired when the request is unsuccessful.
            @param:
                (object) data       Optional. The error data/message.
    **/
    Request = Backbone.Model.extend({
        url: win.precodeandbeauty.ajaxurl + '?action=precodeandbeauty_ajax_request',
        defaults: {
            _wpnonce: win.precodeandbeauty._wpnonce
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
            this.server_error = new View();
        }
    });

    // Make the request and view instance accessible anywhere
    win.precodeandbeauty.Request = Request;
    win.precodeandbeauty.ServerError = View;
})(window);