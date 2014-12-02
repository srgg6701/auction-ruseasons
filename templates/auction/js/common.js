// см. также динамически подключаемый components\com_auction2013\assets\js\auction.js
jQuery(function(){
    var $=jQuery;
    var leftPart = $('#left_part');
    $('.mobile_menu').on('click', function(){
		if(this.id=="mobile-menu-menu"){
			$(this).parent().find('ul.menu').slideToggle(200);
		}else if(this.id=="mobile-menu-products"){
			$(leftPart).toggleClass('visible');
		}
	});
	$('#main_menu li.deeper a').on('click', function(){
        if($(this).parents('ul').eq(0).css('display')=='block'){
			var pLi=$(this).parent(); //console.dir($(this).parent());
			$('>ul', pLi).slideToggle(200);
			$(pLi).toggleClass('expanded');
			if($(pLi).attr('class').indexOf('deeper')!=-1) return false;
		}
	});
    $('#content').css('min-height',$(leftPart).css('height'));
});