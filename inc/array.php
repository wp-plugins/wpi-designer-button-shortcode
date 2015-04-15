<?php
class WPiArray{
	public static function get_icons_arr(){
		$icons_arr=array("no","activity","anchor","aside","attachment","audio","bold","book","bug","cart","category","chat","checkmark","close","close-alt","cloud","cloud-download","cloud-upload","code","codepen","cog","collapse","comment","day","digg","document","dot","downarrow","download","draggable","dribbble","dropbox","dropdown","dropdown-left","edit","ellipsis","expand","external","facebook","facebook-alt","fastforward","feed","flag","flickr","foursquare","fullscreen","gallery","github","googleplus","googleplus-alt","handset","heart","help","hide","hierarchy","home","image","info","instagram","italic","key","leftarrow","link","linkedin","linkedin-alt","location","lock","mail","maximize","menu","microphone","minimize","minus","month","move","next","notice","paintbrush","path","pause","phone","picture","pinned","pinterest","pinterest-alt","play","plugin","plus","pocket","polldaddy","portfolio","previous","print","quote","rating-empty","rating-full","rating-half","reddit","refresh","reply","reply-alt","reply-single","rewind","rightarrow","search","send-to-phone","send-to-tablet","share","show","shuffle","sitemap","skip-ahead","skip-back","skype","spam","spotify","standard","star","status","stop","stumbleupon","subscribe","subscribed","summary","tablet","tag","time","top","trash","tumblr","twitch","twitter","unapprove","unsubscribe","unzoom","uparrow","user","video","videocamera","vimeo","warning","website","week","wordpress","xpost","youtube","zoom ");
		return $icons_arr;
	}
	public static function get_google_fonts(){		
		$fonts=array(
			array("name"=>"Neucha", "var"=>"300,400,600,700"),
			array("name"=>"Rock Salt"),
			array("name"=>"Open Sans", "var"=>"300,400,600,700"),
			array("name"=>"Open Sans Condensed", "var"=>"300,700"),			
			array("name"=>"Pacifico"),
			array("name"=>"Oregano"),
			array("name"=>"Chewy"),
			array("name"=>"Courgette"),
			array("name"=>"Exo"),
			array("name"=>"Gruppo"),
			array("name"=>"Kite One"),
			array("name"=>"Knewave"),
			array("name"=>"Allura"),
			array("name"=>"Satisfy"),
			array("name"=>"Source Sans Pro", "var"=>"200,400,600,700"),
			array("name"=>"Crafty Girls"),
			array("name"=>"Great Vibes"),
			array("name"=>"Sacramento"),
			array("name"=>"Oswald"),
			array("name"=>"Ultra"),
			array("name"=>"Anton"),
			array("name"=>"Raleway"),
			array("name"=>"Droid Sans"),
			array("name"=>"Roboto", "var"=>"100,300,400,700"),
			array("name"=>"Exo 2", "var"=>"100,300,400,700"),
			array("name"=>"Capriola"),
			array("name"=>"Crimson Text"),
		);
		return $fonts;
	}
	public static function get_letter_spacing(){
		$out=array(0,-5,-4,-3,-2,-1,1,2,3,4,5,6,7,8,9,10,20,30,40,50,60,70,80,90,100);	
		return $out;
	}
	public static function get_margin(){
		$out=array(0,10,20,30,40,50,60,70,80,90,100,120,130,140,150,160,170,180,190,200);	
		return $out;
	}
	public static function get_border_width(){
		$out=array(0,1,2,3,4,5,6,7,8,9,10);	
		return $out;
	}	
	public static function get_preset_styles(){
		$out=array(814,808,799,791,763,763,754,752,748,745,721,643,641,636,376,366,348,247,245,243,242,240,239,238,237,234,217,215,213,206,191,190);
		return $out;
	}
	public static function get_font_weights(){		
		$font_weights=array(100,300,400,600,700);
		return $font_weights;
	}
	public static function get_fonts(){	
		$google_fonts=self::get_google_fonts();	
		$default_fonts=array(
			array("name"=>"Arial"),
			array("name"=>"sans-serif"),
		);
		$fonts=array_merge($default_fonts, $google_fonts);
		return $fonts;
	}
	public static function get_font_sizes(){
		$font_sizes=array(8,9,10,12,14,16,18,20,23,26,28,30,32,36,40,44,48,52,66,72,80,100,120,140,160,180,200,220,240,260,280,300,320,340);
		return $font_sizes;
	}	
}

?>