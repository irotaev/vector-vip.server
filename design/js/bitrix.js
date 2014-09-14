$('#panel').ready(function () {    
    var initFixedElement = ['#header', '#top-block'];        
    
    if ($('#panel').html())
    {
//           $.each(initFixedElement, function(index, el) {
//              $(el).css('top', $(el).offset( ).top + 147);
//            });
          
        $('#panel').css({
                'position': 'fixed',
                'width': '100%',
                'z-index': '1002'
        });
    }
});

