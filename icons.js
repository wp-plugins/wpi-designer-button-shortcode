(function($){
	$(document).ready(function(){			   
 var icons=[
"no","activity","anchor","aside","attachment","audio","bold","book","bug","cart","category","chat","checkmark","close","close-alt","cloud","cloud-download","cloud-upload","code","codepen","cog","collapse","comment","day","digg","document","dot","downarrow","download","draggable","dribbble","dropbox","dropdown","dropdown-left","edit","ellipsis","expand","external","facebook","facebook-alt","fastforward","feed","flag","flickr","foursquare","fullscreen","gallery","github","googleplus","googleplus-alt","handset","heart","help","hide","hierarchy","home","image","info","instagram","italic","key","leftarrow","link","linkedin","linkedin-alt","location","lock","mail","maximize","menu","microphone","minimize","minus","month","move","next","notice","paintbrush","path","pause","phone","picture","pinned","pinterest","pinterest-alt","play","plugin","plus","pocket","polldaddy","portfolio","previous","print","quote","rating-empty","rating-full","rating-half","reddit","refresh","reply","reply-alt","reply-single","rewind","rightarrow","search","send-to-phone","send-to-tablet","share","show","shuffle","sitemap","skip-ahead","skip-back","skype","spam","spotify","standard","star","status","stop","stumbleupon","subscribe","subscribed","summary","tablet","tag","time","top","trash","tumblr","twitch","twitter","unapprove","unsubscribe","unzoom","uparrow","user","video","videocamera","vimeo","warning","website","week","wordpress","xpost","youtube","zoom ", 
];

			$(document).set_icons_arr(icons);		
			
			$.each(icons, function(key,val){
				$(document).create_icons(val);		
			});	
		
	});
})(jQuery);