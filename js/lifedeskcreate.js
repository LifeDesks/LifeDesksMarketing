function isValidSubdomain(subdomain){
  var subdpattern = new RegExp(/^[a-z]+$/g);
  return subdpattern.test(subdomain);
}

function isValidEmailAddress(emailAddress) {
   var emailPattern = new RegExp(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/);
   return emailPattern.test(emailAddress);
}

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

$(function(){
    //override the ActiveX jQuery settings
    $.ajaxSetup({
	  xhr:function() { return new XMLHttpRequest(); }
    });

 	$('body').append('<div id="lifedesk_response_overlay"></div><div id="lifedesk_response_message"></div>');
    $('#recaptcha_table tr').find('th:eq(2), td:eq(2)').remove();
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
 var search_timeout = undefined;
 var message = $("#key_validation");
 $("#your_pass_again").attr("disabled",true);
 $("#ld_title").keyup(function() {
	var message = $("#title_validation");
	var title = $(this).val();
    search_timeout = setTimeout(function() {
	  search_timeout = undefined;
	  if(title.length < 40){
        message.html('');	
	  }
	  else {
	    message.html('<span class="fail">must be less than 40 characters long</span>');
	  }
    },50);
 });

 $("#ld_taxa").blur(function() {
   var taxa = $(this).val();
   if(taxa.length >= 4) {
	   $('#throbber').show();
       $.getJSON("/apis/discover/?taxa=" + taxa,
         function(data) {
	         if(data.total == 0) {
		       $('#ld_taxa_found').hide();
		       $('#throbber').hide();
	         }
	         if(data.total > 0) {
		       html = '<ul>';
		       for(i=0; i<data.total; i++) {
			     html += '<li>';
			     var title = (data.sites[i].stats.site_title.length > 40) ? data.sites[i].stats.site_title.substr(0,40) + "..." : data.sites[i].stats.site_title;
			     html += '<h3><a href="' + data.sites[i].url + '" target="_blank">' + title + '</a></h3>';
			     html += '<span><strong>Coordinator:</strong> <a href="' + data.sites[i].url + '/user/' + data.sites[i].stats.site_owner.uid + '" target="_blank">' + data.sites[i].stats.site_owner.givenname + ' ' + data.sites[i].stats.site_owner.surname + '</a></span>';
			     html += '<span class="ld_taxa_latestimage">';
			     var image_exists = false;
			     if(data.sites[i].stats.media.length > 0) {
				   for(j=0;j<data.sites[i].stats.media.length;j++) {
				     if(data.sites[i].stats.media[j].type == "Image") {
					   image_exists = true;
					   html += '<a href="' + data.sites[i].url + '" target="_blank"><img src="' + data.sites[i].url + '/' + data.sites[i].stats.media[j].latest.path + '" class="thumb" alt="' + data.sites[i].stats.media[j].latest.title + '" /></a>';
				     }	
				   }
			     }
			     if(!image_exists) {
				   html += '<a href="' + data.sites[i].url + '" target="_blank"><img src="/images/spider.png" class="thumb" alt="Image coming soon..." /></a>';
			     }
			     html += '</span>';
			     html += '</li>';
		       }
		       html += '</ul>';
		       $("#ld_sites").html(html);
		       $('#throbber').hide();
		       $('#ld_taxa_found').show();
		       $("#ld_sites").jCarouselLite({
			       visible: 1,
			       scroll: 1,
			       btnNext: ".side-next",
			       btnPrev: ".side-prev",
			    });
	         }   
         });
   }
 });

 $("#url_req").keyup(function() {
   var subd = $(this).val();
   subd = subd.toLowerCase();
   subd = subd.replace(" ","");
   $(this).val(subd);
   if(search_timeout != undefined) {
     clearTimeout(search_timeout);
   }
   search_timeout = setTimeout(function() {
     search_timeout = undefined;
     if(subd.length <= 3) {
	     message.html('<span class="fail">must be more than 3 characters long</span>');
     }
     else if (subd.length > 20) {
	     message.html('<span class="fail">must be less than 20 characters long</span>');
	 }
	 else {
       if(isValidSubdomain(subd)) {
         $.getJSON("checksite/?url=" + subd,
           function(data) {
	         if(data.status==true) {
               message.html('<span class="pass">available</span>');
		     }
		     else {
		       if(data.link) {
	             message.html('<span class="fail">taken,  <a href="' + data.link + '"> visit site <img src="/images/application_go.gif" height="14px" alt="Visit ' + subd + '.lifedesks.org"  title="Visit ' + subd + '.lifedesks.org"></a></span>');
		       }
		       else {
		         message.html('<span class="fail">invalid</span>');
		       }
		     }  
           });
       }
       else {
	     message.html('<span class="fail">only letters a-z accepted</span>');
       }
    }
   }, 500);
 });

 $("#email_addy").blur(function() {
	var message = $("#email_validation");
	var email = $(this).val();
	if(isValidEmailAddress(email)){
      message.html('');	
	}
	else {
	  message.html('<span class="fail">invalid email address</span>');
	}
 });
 $("#person_givenname").keyup(function() {
	var givenname = $(this).val();
	var message = $("#person_givenname_validation");
	if(search_timeout != undefined) {
      clearTimeout(search_timeout);
    }
    search_timeout = setTimeout(function() {
	  search_timeout = undefined;
	  if(givenname.length < 32){
        message.html('');	
	  }
	  else {
	    message.html('<span class="fail">name too long</span>');
	  }
    },50);
 });
 $("#person_name").keyup(function() {
	var name = $(this).val();
	var message = $("#person_name_validation");
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
 $("#username").keyup(function() {
	var message = $("#username_validation");
	var user = $(this).val();
	if(search_timeout != undefined) {
      clearTimeout(search_timeout);
    }
    search_timeout = setTimeout(function(){
	  search_timeout = undefined;
	  if(user.length <= 4) {
		message.html('<span class="fail">must be more than 4 characters long</span>');
	  }
	  else {
	    message.html('');	
	  }
    },200);
 });
 $("#your_pass").keyup(function() {
	var message = $("#pass_validation");
	var pass = $(this).val();
	if(search_timeout != undefined) {
      clearTimeout(search_timeout);
    }
    search_timeout = setTimeout(function(){
	  search_timeout = undefined;
	  if(pass.length <= 4) {
		message.html('<span class="fail">must be more than 4 characters long</span>');
	  }
	  else {
		message.html('');
		$('#your_pass_again').attr("disabled",false);
	  }
    },200);
 });
 $("#your_pass_again").keyup(function() {
	var message = $("#pass_again_validation");
	var pass = $("#your_pass").val();
	var pass_again = $(this).val();
	if(search_timeout != undefined) {
      clearTimeout(search_timeout);
    }
    search_timeout = setTimeout(function() {
	  search_timeout = undefined;
	  if(pass != pass_again) {
		message.html('<span class="fail">passwords do not match</span>');
	  }
	  else {
		message.html('<span class="pass">passwords match</span>');
	  }
    },700);
 });
 $(".createinput").keyup(function() {
   $(this).css({"background-color":"#FFF"});	
 });
 $("#recaptcha_response_field").keyup(function() {
   $(this).css({"background-color":"#FFF"});	
 });

//citizen science form
 $("#cit_email_addy").blur(function() {
	var message = $("#cit_email_validation");
	var email = $(this).val();
	if(isValidEmailAddress(email)){
     message.html('');	
	}
	else {
	  message.html('<span class="fail">invalid email address</span>');
	}
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