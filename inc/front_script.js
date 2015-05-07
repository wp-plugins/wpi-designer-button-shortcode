;(function($){
	$(document).ready(function(){
		if ($(".wpi_slide_heading").length) {
			var headingTicker=$(".wpi_slide_heading").wpiTicker({animate:"play"});			
		}
		if ($(".wpi_share_buttons").length) {
			$(".wpi_share_buttons").each(function(){
				$(this).find("a").each(function(){
					$(this).click(function(){
						var href=$(this).attr("href");	
						window.open(href, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=200, left=200, width=400, height=400");
					});
				});				
			});		 
		}
	});
})(jQuery)