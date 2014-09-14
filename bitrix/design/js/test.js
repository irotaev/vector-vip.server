// Namespaces
var VectorVip = VectorVip || {};
VectorVip.Logic = {};
var VVL = VectorVip.Logic;

// Предзагрузка изображений
var imagePreload = function(imagesArray)
{
    $(imagesArray).each(function(ind, el) {
        $("<img>").attr("src", el);
    });
};

var asideMenuModel = function()
{
  var init = {
    el: '#sideLeft .left-menu-block',
    btnOpen: '.menu-open-btn',
    cloneClass: 'left-menu-block-clone'
  };
  this.state = 'close';
  this.locat = 'top';

  var initialisation = function()
  {
    new imagePreload(['img/aside/menu-open-btn-active.png',
                      'img/aside/menu-open-btn.png',
                      'img/aside/menu-close-btn-active.png',
                      'img/aside/menu-close-btn.png']);

    $(init.btnOpen).css({
        'display': 'block'
       //'top': $(window).height() - 210
    });

    $(init.btnOpen).toggle(function() {openMenu()}, function() {closeMenu()});
  }();

  var openMenu = function(locat)
  {
      locat = 'left';
      if (this.state == 'open') {
          return false;
      }

      switch(locat)
      {
          case 'left':
              $(init.el).clone(true)
                        .addClass(init.cloneClass)
                        .appendTo('#sideLeft .content')
                        .css({
                          'left': -($(init.el).width() + 20) + 'px',
                          'top': '-110px',
                          'margin-top': '0',
                          opacity: 1,
                          'z-index': 1001,
                          'visibility': 'visible'
                        });
              this.locat = 'left';
              this.state = 'open';

              SSpaceConfigurator.setWrapperLocation();
              break;
      }

      changeIcon();
  };

  var closeMenu = function()
  {
      if (this.state == 'closed') {
          return false;
      }

      $('.' + init.cloneClass).remove();
      this.state = 'closed';
      changeIcon();
  };

  var changeIcon = function()
  {
      if (this.state == 'closed') {
        $(init.btnOpen).css('background-image', 'url(img/aside/menu-open-btn.png)')
                       .hover(function() {$(this).css('background-image', 'url(img/aside/menu-open-btn-active.png)')},
                              function() {$(this).css('background-image', 'url(img/aside/menu-open-btn.png)')});
      }

      if (this.state == 'open') {
          $(init.btnOpen).css('background-image', 'url(img/aside/menu-close-btn.png)')
                         .hover(function() {$(this).css('background-image', 'url(img/aside/menu-close-btn-active.png)')},
                                function() {$(this).css('background-image', 'url(img/aside/menu-close-btn.png)')});
      }
  };

  return {
      openMenu: openMenu,
      closeMenu: closeMenu,
      initialisation: initialisation
  };
};

var checkScroll = function()
{
    var init = {
        scrollTop: '.scroll-top',
        scrollDown: '.scroll-down',
        wrapperElement: '#side-left-additional-content',
        contentLeftSideWrapper: '.inner-wrapper',
        oldHeight: 0,
        leftSideMenu: '#sideLeft #left-menu'
    };

    var initialise = function()
    {
        //$(window).on('resize', initialise);
        setLeftContentWrapperHeight();
        this.leftMenuState = 'top';
        //$(init.wrapperElement).height(400);

        //initScroll();
    };

    var getAllOtherHeight = function()
    {
        var allOtherHeight = $('#header').height() + $('.top-block').height()
                             + $('#left-menu').height() + $('#footer').height() + 56;

        return allOtherHeight;
    };

    var setLeftContentWrapperHeight = function()
    {
        init.oldHeight = $(init.wrapperElement).height();
        var leftSideFreeSpace = $(window).height() - getAllOtherHeight();
        var wrapperElHeight = leftSideFreeSpace >= 500 ? leftSideFreeSpace : 500;
        $(init.wrapperElement).height(leftSideFreeSpace);
    }

    var initScroll = function()
    {
        var element = '#side-left-additional-content';
        var wrapper = '#sideLeft';
        var contentHeight = $(init.contentLeftSideWrapper).height();

        if (contentHeight > $(element).height()) {
            $.each(init, function(index, el) {
                $(el).css('display', 'block');
            });

            SSpaceConfigurator.moveLeft(init.scrollTop);
            SSpaceConfigurator.moveLeft(init.scrollDown);

            // Закрываем меню
            if ($(init.wrapperElement).height() < 400 && this.leftMenuState != 'left')
            {
                var leftSideMenuHeight = $(init.leftSideMenu).height();
                $(init.leftSideMenu).animate({height: 0, opacity: 0},
                                              1500,
                                              function() {
                                                  // Специально для IE7-8
                                                  $(init.leftSideMenu).css('visibility', 'hidden');
                                                  initScroll(); setLeftContentWrapperHeight();});
                $(element).animate({height: $(element).height() + leftSideMenuHeight - 0},
                                    1500,
                                    function() {var asideMMObj = new asideMenuModel();});
                this.leftMenuState = 'left';
            }
        }

        if ($(element).scrollTop() <= 0)
        {
            $(wrapper).find('.scroll-top').css('display', 'none');
        }

        if (($(init.contentLeftSideWrapper).height() - ($(element).height() + $(element).scrollTop())) <= 0)
        {
            $(wrapper).find('.scroll-down').css('display', 'none');
        }
    };

    return {
        initScroll: initScroll,
        initialise: initialise
    }
};

// asideMenu Viewer
var asideMenu = Backbone.View.extend({
    el: "#head-menu-wrapper",

    flag: false,

    attributes: {
        menuWrapper: '#left-menu'
    },

    events: {
        'hover': 'openCloseMenu',
        'mouseover li > a': 'handleClick',
        'mouseleave .mainMenu': 'handleMouseout'
    },

    openCloseMenu: function(eventObj)
    {
        $(this.attributes.menuWrapper).toggle();
    },

    handleMouseout: function(eventObj)
    {
        $('.left-menu-block').find('.submenu')
                             .css('display', 'none');
    },

    handleClick: function(eventObj)
    {
        $(eventObj.target).closest('li')
                          .siblings('li')
                          .removeClass('active')
                          .children('.submenu')
                          .hide();


        $(eventObj.target).closest('li')
                          .addClass('active')
                          .children('.submenu')
                          .show();
    },

    render: function() {

    }
});

var sideLeft = Backbone.View.extend({
    el: "#sideLeft",
    _CSObj: new checkScroll(),

    checkScroll: function()
    {
        var _this = this;

        this._CSObj.initialise();
        this._CSObj.initScroll();
    },

    events: {
        'click .scroll-down': 'scrollTop',
        'click .scroll-top': 'scrollDown'
    },

    scrollDown: function()
    {
        var _this = this;

        $('#side-left-additional-content')
            .animate({scrollTop: $('#side-left-additional-content').scrollTop() - 200},
                      1000,
                      function() {_this._CSObj.initScroll()});
    },

    scrollTop: function()
    {
        var _this = this;

        $('#side-left-additional-content')
            .animate({scrollTop: $('#side-left-additional-content').scrollTop() + 200},
                      1000,
                      function() {_this._CSObj.initScroll()});
    }
});

var asideLeft = Backbone.View.extend({
   el: '#sideLeft',

   initialize: function()
   {

   }
});


// Bitrix panel viewer
var bitrixPanel = Backbone.View.extend({
    el: '#bitrix-panel-controll',            
    
    params: {
        panel: '#panel',
        closeOpenBtn: '#btn-close-open-bitrix-panel',
        panelHiderBX: '#bx-panel-hider',
        panelShowBX: '#bx-panel-expander-text'
    },
    
    initialize: function()
    {
        var _this = this;
        var panel = this.params.panel;        
        var btn = this.params.closeOpenBtn;               
       
       if ($(this.params.panel).find('*').not(btn).html())
       { 
            $(btn).show();
            
            $(btn).toggle(function() { 
                $(panel).hide()
                $('body').before($(btn).css('position', 'absolute'));                                                                           
            }, function() { 
                 $(panel).append($(btn).css('position', 'static'));
                 $(panel).show();
            });
       }
              
    }
});

var cl = function(data) {console.log(data)}

$(document).ready(function() {

    // Настраиваю расположение страницы
    // для определенного пользователя
    //SSpaceConfigurator.fetch({success: function()
    //{
       // if (SSpaceConfigurator.userOptionsInstall() != 'true')
        //{
            new asideMenu();
            new asideLeft();
            //new sideLeft().checkScroll();
        //}
    //}});


    // Подключаю хаки для браузера
    new VVL.brouserHacks().setHacks();

    // Включаю плагин для скроллинга контента
    $(".scrolling-block").mCustomScrollbar(
        {
            set_height: $(window).height() - 80,
            scrollButtons:{
                enable:true
            },
            updateOnBrowserResize: true
        }
    );
    VVL.Scrolling_M.afterload();

     //Включаю плагин подсказок
    $('.photo-gallery a').poshytip({
        className: 'tip-darkgray',
        bgImageFrameSize: 11,
        offsetX: -25
    });

    // Включаю плагин фотогалереи
    $('.fancybox').fancybox({
        scrollOutside: false
    });

    //Инициализурую битрикс панель
    new bitrixPanel();
});
