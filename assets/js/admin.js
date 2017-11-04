;(function($){
    'use strict';

    var View, Request;

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
            this.$el.html( codeandbeauty.messages.server_error ).appendTo('body');

            _.delay(function() {
                me.remove();
            }, 5000);
        }
    });

    Request = Backbone.Model.extend({
        url: codeandbeauty.ajaxurl + '?action=codeandbeauty_ajax_request',
        defaults: {
            _wpnonce: codeandbeauty._wpnonce
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
    codeandbeauty.Request = Request;
    codeandbeauty.ServerError = View;
})(jQuery);