//Main javascript used on most pages of the software.
// $Rev: 19070 $

//NOTE:  you don't have to customize this JS file to change vars, instead add some
//JS to your own custom JS file (or in script tags on a template) that works like this:

/*
//change default duration for how long effects take
Event.observe(window,'load',function() {
	//change duration to last 1.5 seconds instead default of .3 seconds
	geoEffect.defaultDuration = 1.5;
});


 */

var geoUtil = {
	defaultParams : {duration: .8},
	inAdmin : false,
	runHeartbeat : false,
	
	init : function () {
		//A function that starts up all the common stuff.
		//This will be loaded after the page is done loading, 
		//no need for Event.observer(window,'load',...)
		
		//Load lightUpBox
		lightUpBox.init();
		
		if ($('extraQuestionName') && $('extraQuestionValue')) {
			//make sure extra question labels height match up with values height
			var values = $('extraQuestionValue').select('li');
			$('extraQuestionName').select('li').each(function(element, index) {
				if (element.getHeight() > this[index].getHeight()) {
					this[index].setStyle({ height : element.getHeight()+'px' });
				} else if (this[index].getHeight() > element.getHeight()) {
					element.setStyle({ height : this[index].getHeight()+'px' });
				}
			}, values);
		}
		
		if (geoUtil.runHeartbeat && !geoUtil.inAdmin) {
			//ping cron.php
			new Ajax.Request ('cron.php?action=cron');
		}
	},
	
	pageDimensions : function () {
		//gets the page dimensions
		//first figure out main width and height:
		var scrollDim = {width: 0, height: 0};
		
		var elem = $$('body')[0];
		
		if (window.innerHeight && window.scrollMaxY) {
			scrollDim.width = window.innerWidth + window.scrollMaxX;
			scrollDim.height = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight) {
			//works in all but explorer mac
			scrollDim.width = document.body.scrollWidth;
			scrollDim.height = document.body.scrollHeight;
		} else {
			scrollDim.width = document.body.offsetWidth;
			scrollDim.height = document.body.offsetHeight;
		}
		
		var windowDim = document.viewport.getDimensions();
		//return which ever calculation came up with larger dimension
		scrollDim.width = Math.max(scrollDim.width, windowDim.width);
		scrollDim.height = Math.max(scrollDim.height, windowDim.height);
		
		return scrollDim;
	},
	
	text : {
		//text used, usually over-written by admin text set in in-line JS
		messageClose : '[close]',
		messageMove : '[move]'
	},
	
	addError: function (errorMsg) {
		geoUtil._highlightColor = '#ff9999';
		geoUtil._autoHideMessage = false;
		if (geoUtil.inAdmin) {
			errorMsg = '<span style="color: red;">Error:</span> '+errorMsg;
		}
		geoUtil.addMessage(errorMsg);
	},
	
	_messageTimeout : null,
	_messageMadeDragable : false,
	_highlightColor : '#ffff99',
	_autoHideMessage : true,
	_messageBoxInit : false,
	_initMessageBox : function () {
		if (geoUtil._messageBoxInit) {
			return;
		}
		geoUtil._messageBoxInit = true;
		var messageBox = new Element('div', {
			'id' : 'messageBox',
			'style' : 'display: none;'
		});
		
		var closeButton = new Element ('div');
		closeButton.addClassName('messageBoxButtons')
			.addClassName('closeMessage')
			.update(geoUtil.text.messageClose)
			.observe('click', geoUtil.closeMessage);
		
		var moveButton = new Element ('div', {'id' : 'moveMessageButton'});
		moveButton.addClassName('messageBoxButtons')
			.addClassName('moveMessage')
			.update(geoUtil.text.messageMove);
		if (!geoUtil.inAdmin) {
			moveButton.setOpacity(0);//make it hard to see
		}
		
		var messageText = new Element ('div', {'id' : 'messageTxt'});
		
		//add it all into messageBox
		messageBox.insert(closeButton)
			.insert(moveButton)
			.insert(messageText);
		//insert it into the body
		geoUtil.insertInBody(messageBox);
	},
	
	addMessage : function (msgText) {
		geoUtil._initMessageBox();
		var messageBox = $('messageBox');
		var messageText = $('messageTxt');
		if (!messageBox || !messageText) {
			//can't insert the message if no message box
			alert(msgText);
			return;
		}
		messageText.update(msgText);
		if (!geoUtil._messageMadeDragable) {
			geoUtil._messageMadeDragable = true;
			new Draggable(messageBox, {
				zindex: 1002,
				handle: 'moveMessageButton',
				onStart: function () {
					if (geoUtil._messageTimeout) {
						clearTimeout(geoUtil._messageTimeout);
						geoUtil._messageTimeout = null;
					}
				}
			});
			messageBox.makePositioned();
		}
		
		if (!messageBox.visible()) {
			messageBox.show();
		}
		//move it to middle
		geoEffect.moveToMiddle(messageBox);
		
		//highlight it
		new Effect.Highlight(messageBox, {
			startcolor: geoUtil._highlightColor,
			restorecolor: '#ffffff'
		});
		geoUtil._highlightColor = '#ffff99';//restore to default in case it was changed
		
		if (geoUtil._messageTimeout) {
			//stop it from happening, as we're re-doing it
			clearTimeout(geoUtil._messageTimeout);
		}
		//make it fade out after 10 seconds
		if (geoUtil._autoHideMessage) {
			geoUtil._messageTimeout = setTimeout("new Effect.Fade('messageBox', geoUtil.defaultParams);geoUtil._messageTimeout = null;", 10000);
		}
		//reset auto hide setting for next message
		geoUtil._autoHideMessage = true;
	},
	
	closeMessage : function () {
		if (geoUtil._messageTimeout) {
			//stop it from happening, as we're re-doing it
			clearTimeout(geoUtil._messageTimeout);
			geoUtil._messageTimeout = null;
		}
		new Effect.Fade('messageBox', geoUtil.defaultParams);
	},
	
	insertInBody : function (element) {
		$$('body')[0].insert(element);
	},
	
	clickImageBlockLargeLink : function (action) {
		//smoothly scrolls to the large image block
		geoEffect.scrollTo('largeImageBlock');
		action.stop();
		return false;
	},
	
	getCookie : function (sName) {
		var aCookie = document.cookie.split('; ');
		for (var i=0; i < aCookie.length; i++) {
			var aCrumb = aCookie[i].split('=');
			if (sName == aCrumb[0]) {
				return unescape(aCrumb[1]);
			}
		}
		return null;
	},
	
	/*
	 * Simple function to re-load the page, it should work regardless of whether
	 * there is a hash or not, and will not prompt the user if the current page
	 * is the result of a POST.
	 */
	refreshPage : function () {
		//add refresh=# to the query params, to "force" a refresh of the page even
		//when there is a hash on the page
		var params = location.href.toQueryParams();
		params.refresh = (params.refresh)? params.refresh*1+1 : 1;
		
		//now re-construct the URL with the refresh=# added in the URL
		var href = location.protocol+'//'+location.hostname+location.pathname+'?'+Object.toQueryString(params);
		var hash = location.hash.replace(/^#/,'');
		if (hash) {
			//add the hash back
			href += '#'+hash;
		}
		//alert('href: '+href);
		//use replace so it doesn't result in history entry, it acts like a refresh
		location.replace(href);
	},
	
	/**
	 * Handles taking user to next page automatically when logging in or
	 * registering
	 * 
	 * Note: Uses prototype!
	 * 
	 * @param string form ID of form to submit
	 * @param string replaceTxt
	 */
	autoSubmitForm : function (form, replaceTxt) {
		var delay = 2000; //time to wait after the page is done loading
		
		Event.observe(window, 'load', function () {
			setTimeout(function () {
				//2 seconds after page is done loading, auto submit the form.
				myForm = $(form);
				if (myForm){
					if (replaceTxt) {
						window.location.replace(replaceTxt);
					}
					myForm.submit();
				}
			}, delay);
		});
	}
};


var geoEffect = {
	defaultDuration : .5,
	
	useEffect : function () {
		if (typeof Scriptaculous == 'undefined') {
			return false;
		}
		return true;
	},
	
	/**
	 * Use just like you would Effect.move() except that if scriptaculous is
	 * not present, it doesn't animate, or if element is currently hidden, it
	 * just moves it there by setting attribute.
	 */
	move : function (element, params) {
		element = $(element);
		if (element.visible() && geoEffect.useEffect()) {
			if (!params.duration) params.duration = geoEffect.defaultDuration;
			
			new Effect.Move(element, params);
		} else {
			element.setStyle({left: params.x+'px', top: params.y+'px'});
		}
	},
	
	show : function (element, effect, params) {
		element = $(element);
		if (element.visible() && element.getStyle('opacity') != 0.1) {
			//either the element needs to be hidden, or opacity set to 0.1
			return;
		}
		if (typeof effect == 'undefined') {
			effect = 'appear';
		}
		if (geoEffect.useEffect()) {
			if (typeof params == 'undefined') {
				params = {};
			}
			if (!params.duration) {
				params.duration = geoEffect.defaultDuration;
			}
			switch (effect) {
				case 'appear' :
					//break ommited on purpose
				default: 
					new Effect.Appear (element, params);
					break;
			}
		} else {
			if (element.visible()) {
				//if element is visible, then it must just be opaque
				element.setOpacity(1.0);
			} else {
				element.show();
			}
		}
	},
	
	hide : function (element, effect, params) {
		element = $(element);
		if (!element.visible()) {
			//already hidden
			return;
		}
		if (typeof effect == 'undefined') {
			effect = 'fade';
		}
		if (typeof params == 'undefined') {
			params = {};
		}
		if (geoEffect.useEffect()) {
			if (!params.duration) {
				params.duration = geoEffect.defaultDuration;
			}
			switch (effect) {
				case 'fade' :
					//break ommited on purpose
				default: 
					new Effect.Fade (element, params);
					break;
			}
		} else {
			element.hide();
			if (typeof params.afterFinish != 'undefined') {
				//it's finished, so call whatever is meant to be called
				params.afterFinish();
			}
		}
	},
	
	morphSize : function (element, width, height, params) {
		element = $(element);
		if (!element) return;
		
		if (geoEffect.useEffect()) {
			if (typeof params == 'undefined') {
				params = {};
			}
			if (!params.duration) {
				params.duration = geoEffect.defaultDuration;
			}
			params.style = 'width: '+width+'px; height: '+height+'px;';
			
			new Effect.Morph(element, params);
		} else {
			//use prototype to go to that element
			element.setStyle({
				width: width+'px',
				height: height+'px'
			});
		}
	},
	
	/**
	 * Ensures that given element is positioned vertically around the middle of
	 * the current viewport even if scrolled down some.
	 * 
	 * @param element
	 */
	moveToMiddle : function (element) {
		element = $(element);
		if (!element) {
			//not valid element
			return;
		}
		//make sure it's absolutized
		element.absolutize();
		
		//figure out mid point taking into account scrolled down amount
		var offset = document.viewport.getScrollOffsets();
		
		//figure out different dimensions we're working with
		var elemDim = element.getDimensions();
		var viewDim = document.viewport.getDimensions();
		
		if (viewDim.width == 0 && viewDim.height == 0) {
			//viewport dimensions were not able to be retrieved, so pretend it is
			//800x600 just so it's not off in the corner
			viewDim.width = 800;
			viewDim.height = 600;
		}
		
		//if the difference is > 0 use it, otherwise just start out at scrolled offset
		if ((viewDim.width-elemDim.width) > 0)
			offset.left += Math.floor((viewDim.width-elemDim.width)/2);
		
		if ((viewDim.height-elemDim.height) > 0)
			offset.top += Math.floor((viewDim.height-elemDim.height)/2);
		
		//move into place
		geoEffect.move(element, {x: offset.left, y: offset.top, mode: 'absolute'});
	},
	
	scrollTo : function (element, params) {
		element = $(element);
		if (!element) return;
		
		if (geoEffect.useEffect()) {
			if (typeof params == 'undefined') {
				params = {};
			}
			if (!params.duration) {
				params.duration = geoEffect.defaultDuration;
			}
			new Effect.ScrollTo(element, params);
			
		} else {
			//use prototype to go to that element
			element.scrollTo();
		}
	}
};


/**
 * Function to...  load a popup?  Don't use this, it will be removed in a future
 * release, replaced by new lightbox or you could just use window.open(this.href)
 * 
 * @param string fileName
 * @deprecated Will be removed in future release.
 */
var win = function (fileName) 
{
	var myFloater = window.open('','myWindow','scrollbars=yes,resizable=yes,status=no,width=300,height=300');
	myFloater.location.href = fileName;
	if (window.focus) myFloater.focus();
};

/**
 * Opens a popup, this one is actually still used a lot of places, so can't be
 * removed quite yet.  Do NOT use for new stuff though.
 * 
 * @param string fileName
 * @param int width
 * @param int height
 */
var winimage = function (fileName,width,height) 
{
	var myFloater = window.open('','myWindow','scrollbars=yes,resizable=yes,status=no,width=' + width + ',height=' + height);
	myFloater.location.href = fileName;
};

var lightUpBox = {
	box : null,
	overlay : null,
	//vars used by "slideshow"
	nextImageId : 0,
	slideshowDelay : 5,
	overlayOpacity : 0.6,
	slideshowPlaying : false,
	_hideCallbacks : new Array(),
	_showCallbacks : new Array(),
	_slideshow : null,
	
	startSlideshow : function () {
		if (lightUpBox.slideshowPlaying) {
			//nothin to do, it's already started.
			return;
		}
		lightUpBox.slideshowPlaying = true;
		lightUpBox._slideshow = setTimeout("lightUpBox._nextImage();",1000*lightUpBox.slideshowDelay);
	},
	stopSlideshow : function () {
		clearTimeout(lightUpBox._slideshow);
		
		lightUpBox.slideshowPlaying = false;
		lightUpBox._startUpSlideshow = false;
	},
	_startUpSlideshow : false,
	_nextImage : function () {
		//do stuff
		if (!lightUpBox.nextImageId || !lightUpBox.slideshowPlaying) {
			//nothing to do
			return;
		}
		//generate URL
		var url = 'get_image.php?id='+lightUpBox.nextImageId+'&playing=1';
		lightUpBox.nextImageId = 0;//so it doesn't keep just refreshing itself
		lightUpBox._startUpSlideshow = true;
		
		if (lightUpBox.navBar) {
			//hide the navigation so it can't be clicked during transition, since
			//clicks will have no effect during that time.
			lightUpBox.navBar.hide();
		}
		lightUpBox.lightUpLinkManual(url);
	},
	
	registerHideCallback : function (hideCallback) {
		if (typeof hideCallback == 'function') {
			var index = lightUpBox._hideCallbacks.size();
			lightUpBox._hideCallbacks[index] = hideCallback;
		}
	},
	
	registerShowCallback : function (showCallback) {
		if (typeof showCallback == 'function') {
			var index = lightUpBox._showCallbacks.size();
			lightUpBox._showCallbacks[index] = showCallback;
		}
	},
	_hiddenElems : new Array(),
	
	handleResponse : function (transport) {
		lightUpBox.openBox(transport.responseText);
		if (lightUpBox._startUpSlideshow) {
			lightUpBox._startUpSlideshow = false;
			lightUpBox._slideshow = setTimeout("lightUpBox._nextImage();",1000*lightUpBox.slideshowDelay);
		}
	},
	
	openBox : function (contents) {
		//push the response into a double-deep div
		var newBox = new Element('div')
			.update(contents);
		
		if (!lightUpBox.box.visible()) {
			//call any callbacks to hide stuff
			
			lightUpBox._hideCallbacks.each(function(f) {f();});
			
			//hide objects, selects, and embeds because they are stupid and
			// don't do well trying to hide them in certain browsers
			$$('object', 'select', 'embed').each(function (element) {
				if (element.style.visibility != 'hidden') {
					//do it "smart" so we don't mess up things already hidden on the page,
					//do that by remembering what we are hiding, to be un-hidden later
					element.style.visibility = 'hidden';
					var index = lightUpBox._hiddenElems.size();
					lightUpBox._hiddenElems[index] = element;
				}
			});
			
			//Show div over everything making it dark.
			//get the page size to figure out how large things are:
			var pDim = geoUtil.pageDimensions();
			
			lightUpBox.overlay.setStyle({width: pDim.width+'px', height: pDim.height+'px'});
			geoEffect.show(lightUpBox.overlay, 'appear', {to: lightUpBox.overlayOpacity});
			
			//just shove it in there
			lightUpBox.box.update(newBox);
			//move to center of screen
			geoEffect.moveToMiddle(lightUpBox.box);
			
			//just in case other version was run, un-do any width/height settings on it
			lightUpBox.box.setStyle({
				width: '',
				height: ''
			});
			
			//watch for "escape" character
			Event.observe(document, 'keydown', lightUpBox.boxKeyPressed);
			lightUpBox.finishOpenBox(true);
		} else {
			//OK get the current width and "stick" it
			var startingD = lightUpBox.box.down().getDimensions();
			
			//make sure starting width and height are not too small...
			if (startingD.width < 150) {
				startingD.width = 150;
			}
			if (startingD.height < 150) {
				startingD.height = 150;
			}
			
			//"stick" the width before we get rid of the innards
			lightUpBox.box.setStyle({
				width: startingD.width+'px',
				height: startingD.height+'px'
			});
			
			geoEffect.hide(lightUpBox.box.down(), 'fade', {
				afterFinish : function () {
					//OK hide the outer box
					newBox.setOpacity(0.1);
					//shove it in the page
					lightUpBox.box.update(newBox);
					
					//add the observer
					var newImg = newBox.select('.lightUpBox_imageBox img')[0];
					
					var morphingTime = function () {
						//NOTE: This can double-morph sometimes, which might be
						//a good thing for power rangers, but doesn't seem to do much here.
						//IF it turns out double-morphing messes things up, then code to avoid it.
						
						//get new dimensions
						var newD = newBox.getDimensions();
						//make sure overflow isn't shown
						lightUpBox.box.setStyle({overflow: 'hidden'});
						//make sure it's not a super skinny small box
						if (newD.width < 150) {
							newD.width = 150;
						}
						if (newD.height < 150) {
							newD.height = 150;
						}
						
						geoEffect.morphSize(lightUpBox.box, newD.width, newD.height, {});						
					};

					newImg.observe('load', morphingTime);
					if (newImg.getWidth() > 0) {
						//it's already loaded
						morphingTime();
					}
					lightUpBox.finishOpenBox(false);
					geoEffect.show(newBox, 'appear');
				}
			});
		}
	},
	
	boxDragable : null,
	
	finishOpenBox : function (isNew) {
		//parse for new links
		var alreadyOpen = !lightUpBox.navBarHidden;
		lightUpBox.box.select('.lightUpImg').each(lightUpBox.addImageObserver);
		
		lightUpBox.box.select('.lightUpLink').each(lightUpBox.addLinkObserver);
		
		lightUpBox.box.select('.lightUpDisable').each(lightUpBox.addDisabledObserver);
		
		//make click on image close box
		lightUpBox.box.select('.lightUpBox_imageBox img', 'img.lightUpBigImage', '.closeLightUpBox', '.closeBoxX').each (function (element){
			if (element.up().hasClassName('lightUpBox_link')) {
				//this is something that links somewhere, instead of causing it
				//to close, instead open the link in new window
				element.up().observe('click', function (action) {
					action.stop();
					window.open(this.href);
				});
			} else {
				//close it
				element.observe('click',lightUpBox.closeBox);
			}
		});
		
		//special stuff for nav
		lightUpBox.box.select('.lightUpBox_navigation').each(function (element) {
			lightUpBox.addNavObserver(element);
			element.setOpacity((alreadyOpen)? 0.9: 0.08);
		});
		//make any disabled links grayed out
		lightUpBox.box.select('.disabledLink').each(function (element) { element.setOpacity(0.4);});
		
		//if there is child with class of lightUpMoveAnchor it will use that as an anchor to move it
		if (geoEffect.useEffect()) {
			var anchor = lightUpBox.box.select('.lightUpMover');
			
			if (anchor.length) {
				lightUpBox.boxDragable = new Draggable (lightUpBox.box, {handle : 'lightUpMover'});
			} else {
				anchor = lightUpBox.box.select('.lightUpTitle');
				if (anchor.length) {
					//the lightUpTitle exists, use that
					lightUpBox.boxDragable = new Draggable (lightUpBox.box, {handle : 'lightUpTitle'});
				}
			}
		}
		
		if (isNew) {
			geoEffect.show('lightUpBox', 'appear');
		}
	},
	
	closeBox : function (event) {
		//close the box
		geoEffect.hide(lightUpBox.box, 'fade', {afterFinish : lightUpBox._afterCloseFinished});
		
		//hide the black overcast thingy, fade it out or something
		geoEffect.hide(lightUpBox.overlay);
		
		//stop watching for keys pressed
		Event.stopObserving(document, 'keydown');
		
		//make sure to stop the slide show
		clearTimeout(lightUpBox._slideshow);
		
		if (lightUpBox.boxDragable) {
			lightUpBox.boxDragable.destroy();
		}
		
		//stop slideshow if it is going
		if (lightUpBox._slideshow) {
			clearTimeout(lightUpBox._slideshow);
		}
		lightUpBox.slideshowPlaying = false;
		lightUpBox._startUpSlideshow = false;
		
		if (event) {
			//stop the click event
			event.stop();
		}
	},
	
	/**
	 * handles when escape character is pressed, to close the box
	 */
	boxKeyPressed : function (event) {
		//alert ('key pressed: '+event.keyCode);
		if (event.keyCode == 27) {
			//escape character presssed, close the box
			lightUpBox.closeBox();
			event.stop();
		}
	},
	
	_afterCloseFinished : function () {
		//call any callbacks to show stuff
		lightUpBox._showCallbacks.each(function(f) {f();});
		
		//show any hidden objects, selects, and whatever else
		lightUpBox._hiddenElems.each(function (element) {
			element.style.visibility = 'visible';
		});
		//reset it for next time, in case things change between now and then
		lightUpBox._hiddenElems = new Array();
	},
	
	/**
	 * This one is used on images, all it displays is the image, pure and simple.
	 * 
	 */
	lightUpImage : function (event) {
		event.stop();
		lightUpBox.initBox();
		
		
		//create a new image tag
		var biggerImage = new Element('img',{
			'src':this.href,
			'alt' : 'big image',
			'class' : 'lightUpBigImage'
		});
		lightUpBox.openBox(biggerImage);
	},
	
	lightUpLink : function (event) {
		event.stop();
		var extra = '';
		if (lightUpBox.nextImageId) {
			if (lightUpBox.slideshowPlaying) {
				//must stop the timer
				clearTimeout(lightUpBox._slideshow);
				//and tell it to start timer again once next thing is loaded.
				lightUpBox._startUpSlideshow = true;
				//let get image know if it is in middle of playing a slideshow or not
				extra = '&play=1';
			} else {
				extra = '&play=0';
			}
		}
		lightUpBox.lightUpLinkManual(this.href+extra);
	},
	
	lightUpLinkManual : function (url) {
		lightUpBox.initBox();
		//alert('starting request now.');
		//make ajax call to get contents of the linked page, to be shoved into
		//the existing page somewhere.
		new Ajax.Request (url, {
			method: 'get',
			onSuccess: lightUpBox.handleResponse
		});
	},
	
	initBox : function () {
		if (!$('lightUpBox')) {
			//create the box
			lightUpBox.box = new Element('div',{'id' : 'lightUpBox', 'class': 'lightUpBox'});
			lightUpBox.box.hide();
			
			//put it on the page somewhere
			geoUtil.insertInBody(lightUpBox.box);
			//lightUpBox.box.observe('click',lightUpBox.closeBox);
		}
		if (!$('lightUpBoxOverlay')) {
			//create the box
			lightUpBox.overlay = new Element('div',{'id' : 'lightUpBoxOverlay', 'class': 'lightUpBoxOverlay'})
				.setOpacity(lightUpBox.overlayOpacity).hide();
			
			//put it on the page somewhere
			geoUtil.insertInBody(lightUpBox.overlay);
			lightUpBox.overlay.observe('click', lightUpBox.closeBox);
		}
	},
	init : function () {
		$$('.lightUpImg').each(lightUpBox.addImageObserver);
		
		$$('.lightUpLink').each(lightUpBox.addLinkObserver);
		
		$$('.lightUpDisable').each(lightUpBox.addDisabledObserver);
	},
	addImageObserver : function (element) {
		element = $(element);
		if (!element) return;
		element.stopObserving('click');
		element.observe('click',lightUpBox.lightUpImage);
	},
	addLinkObserver : function (element) {
		element = $(element);
		if (!element) return;
		element.stopObserving('click');
		element.observe('click',lightUpBox.lightUpLink);
	},
	
	addDisabledObserver : function (element) {
		element = $(element);
		if (!element) return;
		
		if (element.hasClassName('lightUpDisableProcessed')) {
			//already processed
			return;
		}
		
		element.stopObserving('click')
			.observe('click', function (action) {
				//stop anything from happening
				action.stop();
			})
			.setOpacity(0.3)
			.setStyle({cursor : 'default'})
			.addClassName('lightUpDisableProcessed');
	},
	
	navBar : null,
	navBarHidden : false,
	
	addNavObserver : function (element) {
		element = $(element);
		if (!element) {
			return;
		}
		//so we can reference it later easily
		lightUpBox.navBar = element;
		
		//when hover over image
		if (lightUpBox.box) {
			lightUpBox.box.observe('mouseover', function () {
				if (lightUpBox.navBar) {
					lightUpBox.navBarHidden = false;
					lightUpBox.navBar.setOpacity(0.9);
				}
			});
			lightUpBox.box.observe('mouseout', function () {
				if (lightUpBox.navBar){
					lightUpBox.navBarHidden = true;
					lightUpBox.navBar.setOpacity(0.08);
				}
			});
		}
		
		//play and pause observers
		var play = lightUpBox.navBar.select('a.playLink')[0];
		var pause = lightUpBox.navBar.select('a.pauseLink')[0];
		var disabledPlay = lightUpBox.navBar.select('span.noplayLink')[0];
		
		if (!play || !pause || !disabledPlay) {
			//couldn't find play button?  can't do anything beyond this point.
			//alert('no can do anything.'+lightUpBox.navBar);
			return;
		}
		
		//clear any observers, just in case they are already being observed
		play.stopObserving('click');
		pause.stopObserving('click');
		//watch them for clicks
		play.observe('click', function () {
			this.hide();
			//relies on play being first button
			this.next().show();
			lightUpBox.startSlideshow();
		});
		pause.observe('click', function () {
			this.hide();
			//relies on play being first button
			this.previous().show();
			lightUpBox.stopSlideshow();
		});
		//init the play pause buttons
		lightUpBox.initPlayPause();
	},
	initPlayPause : function () {
		var play = lightUpBox.navBar.select('a.playLink')[0];
		var pause = lightUpBox.navBar.select('a.pauseLink')[0];
		var disabledPlay = lightUpBox.navBar.select('span.noplayLink')[0];
		
		if (!play || !pause || !disabledPlay) {
			//couldn't find play button?  can't do anything beyond this point.
			//alert('no can do anything.'+lightUpBox.navBar);
			return;
		}
		
		if (lightUpBox.nextImageId) {
			//we can play!
			disabledPlay.hide();
			if (lightUpBox.slideshowPlaying) {
				//show pause
				pause.show();
			} else {
				play.show();
			}
		}
	}
};

//For older scripts that still do things old way
var getCookie = geoUtil.getCookie;

/* Mini-object for handling loading/unloading wysiwyg's */
var geoWysiwyg = {
	editors : [],
	toggleTinyEditors : function () {
		//alert('toggle editor, length: '+editors.length);
		for (var i = 0; i < geoWysiwyg.editors.length; i++) {
			var id = geoWysiwyg.editors[i].identify();
			if (!tinyMCE.getInstanceById(id)) {
				tinyMCE.execCommand('mceAddControl',false,id);
				document.cookie = 'tinyMCE=on';
			} else {
				tinyMCE.execCommand('mceRemoveControl',false,id);
				document.cookie = 'tinyMCE=off';
			}
		}
	}
};

/* As the name implies, this is the "old" way of doing ajax.  New stuff uses
 * prototype's Ajax object directly to do anything.  This is scheduled to
 * eventually be re-coded so do not use for new code!
 * 
 * @deprecated Do not use this for new code, it is old and being phased out.
 */
var geoOldAjax = {
	sendReq : function (action, b) {
		if (b) {
			b = '&b='+b;
		} else {
			var b = '';
		}
		var url = '';
		if (action=='close'){
			//use different url for close/cron routines
			url = 'cron.php?action=cron';
		} else {
			//find the filename
			url = 'ajaxBackend.php?action='+action+b;
		}
		new Ajax.Request (url, {
			onSuccess : geoOldAjax.handleResponse
		});
	},
	handleResponse : function (transport) {
		var response = transport.responseText;
		var update = new Array();
		var sep = '|';
		if (response.indexOf('~~|~~') != -1){
			sep = '~~|~~';
		}
		if(response.indexOf(sep) != -1) {
			update = response.split(sep);
			
			for (var i=1; i<update.length; i++) {
				if ($(update[i])) {
					//replace contents of container with first part
					$(update[i]).update(update[0]);
				}
			}
		}
	}
};

//For customizations that still use sendReq straight up..  this will be removed eventually
var sendReq = geoOldAjax.sendReq;
