window.addEvent('domready', function() {
    var A=$$('#main_menu li a');
	var width_=0;
	var i=0;	
	var A_=$$('#main_menu li ul li a');
	var kol=A.length-A_.length;
	var width_parent=$$('#main_menu')[0].getStyle('width').toInt();
	
	for(i=0;i<A.length;i=i+1)
	{	
		if(A[i].getStyle('float')=='left')
			width_=width_+A[i].getStyle('width').toInt();
	}
	
	var padding=parseInt((width_parent-width_)/(kol*2)-0.5);
	$$('#main_menu li a').setStyle('paddingLeft',padding+'px');
	$$('#main_menu li a').setStyle('paddingRight',padding+'px');
	$$('#main_menu li ul li a').setStyle('padding','5px');
	
	
	$$('#bottom_menu ul').getFirst('li').addClass('first');
	var LI=$$('#bottom_menu ul li');
	var width_ul=0;
	var i=0;	
	for(i=0;i<LI.length;i=i+1)
	{		
		width_ul=width_ul+LI[i].getStyle('width').toInt()+1;
	}
	$$('#bottom_menu').setStyle('width',width_ul+'px');
});