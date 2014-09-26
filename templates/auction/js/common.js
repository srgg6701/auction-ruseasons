jQuery(function(){
	console.dir(jQuery);
	jQuery('.mobile_menu').on('click', function(){
		console.log(this.id);
		if(this.id=="mobile-menu-menu"){
			jQuery(this).parent().find('ul.menu').slideToggle(200);
		}else if(this.id=="mobile-menu-products"){
			jQuery('#left_part').toggleClass('visible');
		}
	});
	jQuery('#main_menu ul li.deeper').on('click', function(){
		jQuery('>ul', this).slideToggle(200);
		jQuery(this).toggleClass('expanded');
		return false;
	});
});