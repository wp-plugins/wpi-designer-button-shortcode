;(function($){
	$(document).ready(function(){
							   
		$.fn.smartButtons=function(args){
			function SmartButtons(args){
				this.$el=args.el;
				this.init();
			}
			SmartButtons.prototype.init=function(){
				var self=this;
				this.MenuLinks=this.$el.find(".wpi_menu_links");	
				this.Button=this.$el.find(".wpi_designer_button");
				this.align();
				$(window).resize(function(){
					self.align();			  
				});
				this.Button.click(function(event){
					event.preventDefault();
					if(self.MenuLinks.hasClass("wpi_open")){
						self.MenuLinks.removeClass("wpi_open").addClass("wpi_close");	
					}else{
						self.MenuLinks.removeClass("wpi_close").addClass("wpi_open");	
					}
				});
			}
			SmartButtons.prototype.align=function(){
				var smb_w=this.$el.width();
				var ml_w=this.MenuLinks.width();
				var left=0;
				var right="auto";
				var align=this.$el.css("text-align");
				if(align=="right"){
					left="auto";
					right=0;
				}else if(align=="center"){
					left=(smb_w/2)-(ml_w/2);					
				}							
				this.MenuLinks.css({"left":left, "right":right});
			}
			return this.each(function(){
				var defaults={el:$(this)};
				var args=$.extend(defaults,args);     
				new SmartButtons(args);
			});
		}
		
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
		if ($(".wpi_smart_buttons").length) {
			$(".wpi_smart_buttons").smartButtons({});
		}
		
	});
})(jQuery)