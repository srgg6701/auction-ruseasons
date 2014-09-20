$(function(){
	$('.mobile_menu').on('click', function(){
		console.log(this.id);
		if(this.id=="mobile-menu-menu"){
			$(this).parent().find('ul.menu').slideToggle(200);
		}else if(this.id=="mobile-menu-products"){
			$('#left_part').toggleClass('visible');
		}
	});
	$('#main_menu ul li.deeper').on('click', function(){
		$('>ul', this).slideToggle(200);
		$(this).toggleClass('expanded');
		return false;
	});
});