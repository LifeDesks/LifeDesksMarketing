var LifeDesks = LifeDesks || { 'settings' : {} };

function validate_email()
{
  if((document.getElementById("my_email").value == "") || (document.getElementById("friends_email").value == ""))
  {
  	alert("Please complete all fields before submitting your request.");
	return false;
  }
  	
  else
  	return true;
}

$(function() {
  //override the ActiveX jQuery settings
  $.ajaxSetup({
	xhr:function() { return new XMLHttpRequest(); }
  });
  $('#friend_form').submit(function(){
		var action = $(this).attr('action');
		$('#submit')
			.before('<img src="/images/ajax-loader.gif" class="loader" />')
			.attr('disabled','disabled');
		$.post(action, { 
			my_name: $('#my_name').val(),
			my_email: $('#my_email').val(),
			friends_name: $('#friends_name').val(),
			friends_email: $('#friends_email').val(),
			include_me: $('#include_me').val()
		},
			function(data){
				$('#friend_form .submit').attr('disabled','');
				$('.response').remove();
				$('#friend_form .submit').before('<div class="response">'+data+'</div>');
			}
		);
 
		return false;
 
	});
});

$(window).load(function() {
  $('.explore').show();
  $(".sidegallery").jCarouselLite({
       visible: 1,
     	scroll: 1,
         btnNext: ".side-next",
         btnPrev: ".side-prev"
  });
});