//for highlighting menue

$('li').each(function() {

    if(window.location.href.indexOf($(this).find('a:first').attr('href'))>-1)

    {

    $(this).addClass('active').siblings().removeClass('active');

    $(this).addClass('active');

    }

});



//for highlighting menue

$(document).ready(function () {

    var url = window.location;

    // Will only work if string in href matches with location

    $('ul.sub-menu a[href="' + url + '"]').parent().addClass('active');

    // Will also work for relative and absolute hrefs

    $('ul.sub-menu a').filter(function () {

        return this.href == url;

    }).parent().addClass('active').parent().parent().addClass('active');

});



//for success and error msg div hide

setTimeout(function() {

	$('.hide-it').fadeOut('slow');

}, 3000); // <-- time in milliseconds -->


//for success and error msg div hide on login, register, forgot page

setTimeout(function() {

	$('.statusMsg').fadeOut('slow');

}, 3000);