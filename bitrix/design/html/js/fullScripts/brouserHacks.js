VVL.brouserHacks = Backbone.Model.extend(function() {

   var setHacks = function()
   {
       if (($.browser.msie) && ($.browser.version == '8.0')) {
           IE8();
       }

       if (($.browser.msie) && ($.browser.version == '7.0')) {
           IE7();
       }
   };

   var IE8 = function()
   {
      var init = {
        btnDown: '.mCSB_buttonDown',
        btnContainer: '.mCSB_draggerContainer'
      };

      $(init.btnDown).ready(function() {
        $(init.btnDown).css({
            'top': $(init.btnContainer).height() + 'px',
            'margin-top': '0px'
        });
      })
   };

    var IE7 = function()
    {
        var init = {
            btnDown: '.mCSB_buttonDown',
            btnUp: '.mCSB_buttonUp',
            btnContainer: '.mCSB_draggerContainer',
            mCSB_dragger: '.mCSB_dragger'
        };

        $(init.btnDown, init.btnUp).ready(function() {
            $(init.btnDown).css({
                                'top': $(init.btnContainer).height() + 'px',
                                'margin-top': '0px'
                            });
            $(init.mCSB_dragger).css('left', '0px');
        })
    };

    return {
      IE8: IE8,
      setHacks: setHacks
    };
}());