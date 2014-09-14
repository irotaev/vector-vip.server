VVL.Scrolling_M = function()
{
  var init = {
      middleScrollPanel: '#middle-scrolling-section .mCSB_scrollTools'
  };

  var afterload = function()
  {
    $(init.middleScrollPanel).css({
       'height': $(window).height() - 100 - 50 + 'px',
       'margin-top': '30px'
    });
  };

  return {
      afterload: afterload
  };
}();
