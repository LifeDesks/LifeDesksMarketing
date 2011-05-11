function lifedeskcreate_expert() {
        this.counter = 0;
		this.get_response = function() {
			var _this = this;
			var hash = $('#md5').val();
			$.getJSON("/create/expert/callback/?q=" + hash, 
		      function(data) { _this.produce_display(data); }
		    );
		};
		this.produce_display = function(data) {
		  var _this = this;
          var search_timeout = undefined;
		
		  var message = $('#create_site_message');
		  var title = $('#create_site_title');
		  var url = $('#create_site_url');
		  var help_message = $('#create_site_help');
	      
	      switch(data.create_status) {
		    case -1:
		      var response = '<p>Sorry, that LifeDesk may already have been created.</p>';
		      message.html(response);
		    break;
		    case 0:
		      _this.counter++;
		      if(_this.counter < 5) {
		        title.html(data.title);
		        if(search_timeout != undefined) {
		          clearTimeout(search_timeout);
		        }
		        search_timeout = setTimeout(function() {
		          _this.get_response();
		        }, 2000);
		      }
		      else {
			    var response = '';
			    response = '<p>Your site should have been created by now. Something bad may have happened. Check your email again in case your site might actually have been created.</p>';
			    message.html(response);
		      }
		    break;
		    case 1:
		      _this.counter++;
		      if(_this.counter < 20) {
		        title.html(data.title);
		        if(search_timeout !== undefined) {
		          clearTimeout(search_timeout);
		        }
		        search_timeout = setTimeout(function() {
		          _this.get_response();
		        }, 3000);
		      }
		      else {
			    var response = '';
			    response = '<p>Your site should have been created by now. Something bad may have happened. Check your email again in case your site might actually have been created.</p>';
			    message.html(response);
		      }
		    break;
		    case 2:
		      title.html(data.title);
		      response = 'Thanks, ' + data.givenname + '. ';
		      response += 'Your LifeDesk is now ready.';
		      message.html(response);
		      url.html('<a href="http://' + data.url + '.' + LifeDesks.settings.hostName + '">http://' + data.url + '.' + LifeDesks.settings.hostName + '</a><div id="create_site_credentials"><ul><li>Username: ' + data.username + '</li><li>Password: <em>selected earlier</em></li></ul></div>');
		      var help = '';
		      help += '<p>Once you log in to your new site, be sure to visit the help pages where you may find getting started guides and screencasts to help you and your community make best use of your new LifeDesk.</p>';
		      help_message.html(help);
		    break;
		    default:
		      response = '<p>Sorry, there was an error with that request. You may have mistyped the URL to create your site.</p>';
		      message.html(response);
	        }
		};
}

$(function() {
	var create_expert = new lifedeskcreate_expert();
    //override the ActiveX jQuery settings
    $.ajaxSetup({
	  xhr:function() { return new XMLHttpRequest(); }
    });
	create_expert.get_response();
});


