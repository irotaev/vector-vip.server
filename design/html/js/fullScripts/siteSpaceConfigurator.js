// Конфигуратор расположения сайта на странице
VVL.siteSpaceConfigurator = Backbone.Model.extend(function() {
    var init = {
        leftMenu: '#left-menu',
        leftMenuClone: '.left-menu-block-clone',
        mainWrapper: '#wrapper'
    };

    var initialize = function()
    {
        this.url = '../ajax.php';
    };

    var defaults = {
        position: {
            diff: 0
        },
        location: 'center',
        autocheck: 'true'
    };

    var userOptionsInstall = function()
    {
        if (this.get('autocheck') == 'true')
        {
            return false;
        }

        switch(this.get('location'))
        {
            case 'left':
                $(init.leftMenu).css({'height': '0px', opacity: 0});
                new asideMenuModel();
                var CSObj = new checkScroll();
                CSObj.initialise();
                CSObj.leftMenuState = 'left';
                CSObj.initScroll();
                new sideLeft();
                break;
        }

        return true;
    };

    var setWrapperLocation = function(_location)
    {
        var _this = this;
        var menClone = init.leftMenuClone;
        var wrapper = init.mainWrapper;
        var totalWidth = 0;
        var totalWidthDouble = 0;

        if ($(menClone).length) {
            totalWidth = $(wrapper).width() + $(menClone).width();
            totalWidthDouble = totalWidth + $(menClone).width();

            if (totalWidthDouble > $(window).width()) {
                setWrapeerLocation('left');
            }
        }
    };

    var setWrapeerLocation = function(_location)
    {
        switch(_location)
        {
            case 'left':
                var diff = $(init.mainWrapper).offset().left  - $(init.leftMenuClone).width();
                defaults.position.diff = diff;

                $(init.mainWrapper).css({'margin-left': $(init.leftMenuClone).width() + 'px'})

                $('*').filter(function() {
                   if ($(this).css('position') == 'fixed')
                   {    console.log(this);
                    $(this).css({
                        'left': $(this).offset().left - diff,
                        'margin-left': '0px'
                    });
                   }
                });
                break;
        }
    };

    var moveLeft = function(el)
    {
        this.collection = this.collection || [];

        var diff = defaults.position.diff;

        if (!$.inArray(el, this.collection)) {
            $(el).css({'left': $(el).offset().left - diff, 'margin-left': '0px'});
            this.collection.push(el); console.log(this.collection);
        }
    };

    return {
        setWrapperLocation: setWrapperLocation,
        defaults: defaults,
        moveLeft: moveLeft,
        initialize: initialize,
        userOptionsInstall: userOptionsInstall
    };
}());
var SSpaceConfigurator = new VVL.siteSpaceConfigurator();