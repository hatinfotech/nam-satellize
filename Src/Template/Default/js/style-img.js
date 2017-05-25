
$( window ).load(function() {
    render_size();
    var url = window.location.href;
    $('.menu-item  a[href="' + url + '"]').parent().addClass('active');
});

$( window ).resize(function() {
    render_size();
});



function render_size(){


    var h_663 = $('.h_663 img').width();
    $('.h_663 img').height( 0.650 * parseInt(h_663))

    var h_677 = $('.h_677 img').width();
    $('.h_677 img').height( 0.677 * parseInt(h_677))

}


/*=======================Search=================================*/
$(document).ready(function(e){
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
        e.preventDefault();
        var param = $(this).attr("href").replace("#","");
        var concept = $(this).text();
        $('.search-panel span#search_concept').text(concept);
        $('.input-group #search_param').val(param);
    });
});
/*=======================Search=================================*/

