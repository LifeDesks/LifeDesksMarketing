function ___getPageSize() {
	var xScroll, yScroll;
	if (window.innerHeight && window.scrollMaxY) {	
		xScroll = window.innerWidth + window.scrollMaxX;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}
	var windowWidth, windowHeight;
	if (self.innerHeight) {	// all except Explorer
		if(document.documentElement.clientWidth){
			windowWidth = document.documentElement.clientWidth; 
		} else {
			windowWidth = self.innerWidth;
		}
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}
	// for small pages with total height less then height of the viewport
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	} else { 
		pageHeight = yScroll;
	}
	// for small pages with total width less then width of the viewport
	if(xScroll < windowWidth){	
		pageWidth = xScroll;		
	} else {
		pageWidth = windowWidth;
	}
	arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight);
	return arrayPageSize;
};

function isValidEmailAddress(emailAddress) {
   var emailPattern = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/);
   return emailPattern.test(emailAddress);
}

function ___getPageScroll() {
	var xScroll, yScroll;
	if (self.pageYOffset) {
		yScroll = self.pageYOffset;
		xScroll = self.pageXOffset;
	} else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
		yScroll = document.documentElement.scrollTop;
		xScroll = document.documentElement.scrollLeft;
	} else if (document.body) {// all other Explorers
		yScroll = document.body.scrollTop;
		xScroll = document.body.scrollLeft;	
	}
	arrayPageScroll = new Array(xScroll,yScroll);
	return arrayPageScroll;
};

function close_message() {
	  $('#lifedesk_response_message').fadeOut();
	  $('#lifedesk_response_overlay').fadeOut();
	  window.location = LifeDesks.settings.baseUrl;
}

$(function() {
  	$('body').append('<div id="lifedesk_response_overlay"></div><div id="lifedesk_response_message"></div>');
	var arrPageSizes = ___getPageSize();
	$('#lifedesk_response_overlay').css({
		backgroundColor: 'black',
		opacity: 0.66,
		width: arrPageSizes[0],
		height: arrPageSizes[1]
	});
	var arrPageScroll = ___getPageScroll();
	$('#lifedesk_response_message').css({
		top: arrPageScroll[1] + (arrPageSizes[3] / 3),
		left: arrPageScroll[0],
		position: 'absolute',
		zIndex: 1001,
		margin: '0px auto',
		width: '100%'
	});
  $('#recaptcha_response_field').append('<span id="recaptcha_validation" class="real-time-validation2"> </span>');

  var search_timeout = undefined;

  $("#contact_email_addy").blur(function() {
	var message = $("#email_validation");
	var email = $(this).val();
	if(isValidEmailAddress(email)){
      message.html('');	
	}
	else {
	  message.html('<span class="fail">invalid email address</span>');
	}
  });
  $("#contact_name").keyup(function() {
	var name = $(this).val();
	var message = $("#name_validation");
	if(search_timeout != undefined) {
      clearTimeout(search_timeout);
    }
    search_timeout = setTimeout(function() {
	  search_timeout = undefined;
	  if(name.length < 32){
        message.html('');	
	  }
	  else {
	    message.html('<span class="fail">name too long</span>');
	  }
    },50);
  });
  $("#contact_message").keyup(function() {
	var name = $(this).val();
	var message = $("#message_validation");
	if(search_timeout != undefined) {
      clearTimeout(search_timeout);
    }
    search_timeout = setTimeout(function() {
	  search_timeout = undefined;
	  if(name.length > 0){
        message.html('');	
	  }
	  else {
	    message.html('<span class="fail">a message is required</span>');
	  }
    },50);
  });
  $(".contactinput").keyup(function() {
    $(this).css({"background-color":"#FFF"});	
  });
});

$(window).resize(function() {
	// Get page sizes
	var arrPageSizes = ___getPageSize();
	// Style overlay and show it
	$('#lifedesk_response_overlay').css({
		width: arrPageSizes[0],
		height: arrPageSizes[1]
	});
	var arrPageScroll = ___getPageScroll();
	$('#lifedesk_response_message').css({
		top: arrPageScroll[1] + (arrPageSizes[3] / 3),
		left: arrPageScroll[0],
		position: 'absolute',
		zIndex: 1001,
		margin: '0px auto',
		width: '100%'
	});
});