(function( $ ){
    var methods = {
        init  : function(options) {},
        show  : function() {},
        hide  : function() {},
        update: function() {}
    }

    $.fn.OmekaTimeline = function() {

        if(methods[method]){
            return methods[method].apply(this, Array.prototype.slice(arguments, 1));
        } else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.OmekaTimeline');
        }
    };

})( jQuery );


