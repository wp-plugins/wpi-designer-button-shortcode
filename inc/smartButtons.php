<?php
class WPiDesButSmB{	
	public function __construct(){		
		add_action("add_meta_boxes", array($this, "meta_box"));
		add_action("save_post",array($this,"save_post"));
		add_action("wp_enqueue_scripts", array($this, 'inline_styles'),21 );
	}	 
	public function fields(){
		$icons_arr=WPiArray::get_icons_arr();
		$icons=WPiTools::array2object($icons_arr);
		$target=array("self"=>"Self","_blank"=>"New Window");
		$align=array("left"=>"Left","right"=>"Right","center"=>"Center");
		$switch=array("0"=>"Off","1"=>"On");
				
		$style_ids=WPiDesButCommon::get_style_ids();
		$style_ids_2=WPiDesButCommon::add_empty_option(array("array"=>$style_ids));
		$menu_links=array();
		$dummy_text=array(
			array("heading"=>"Top 10 responsive websites", "subheading"=>"Awesome Responsive Design Website Designs for Inspiration."),
			array("heading"=>"2014 Winners - 2014 User Experience Awards", "subheading"=>"The 2014 User Experience Awards was a smashing success. Check out the details below!"),
			array("heading"=>"40+ Helpful Resources On User Interface Design Patterns", "subheading"=>"UI-patterns.com is a large collection of design patterns for UI designers "),
			array("heading"=>"The 30 Best Free Google Web Fonts for 2015", "subheading"=>"A collection of the absolute best fonts available on Google Fonts in 2015 - open-source and 100% free for commercial use."),
			array("heading"=>"What is ergonomics? - Ergonomics & Human Factors", "subheading"=>"Ergonomics is about designing for people, wherever they interact with products, systems or processes."),			
		);
		for($i=1;$i<=5;$i++){
			if($dummy_text[$i]){ 
				$heading=$dummy_text[$i]['heading']; $subheading=$dummy_text[$i]['subheading'];
			}else{
				$heading=$dummy_text[0]['heading']; $subheading=$dummy_text[0]['subheading'];
			}
			$menu_links[]=array("label"=>"Menu item ".$i, "name"=>"menu_item".$i, "type"=>"boolean",  "section"=>"Menu links",  "group"=>"Menu item ".$i, "default"=>"1", "value"=> "","list"=>$switch);
			$menu_links[]=array("label"=>"Heading", "name"=>'heading'.$i, "type"=>"text",  "section"=>"Menu links",  "group"=>"Menu item ".$i, "value"=> "", "default"=>$heading,);
			$menu_links[]=array("label"=>"Sub Heading", "name"=>'subheading'.$i, "type"=>"text",  "section"=>"Menu links",  "group"=>"Menu item ".$i, "value"=> "", "default"=>$subheading,);
			$menu_links[]=array("label"=>"Link", "name"=>'link'.$i, "type"=>"text",  "section"=>"Menu links",  "group"=>"Menu item ".$i, "value"=> "", "default"=>"",);		
			$menu_links[]=array("label"=>"Image", "name"=>'image'.$i, "type"=>"wp_image", "section"=>"Menu links",  "group"=>"Menu item ".$i, "value"=> "",);	
			$menu_links[]=array("label"=>"Target", "name"=>'target'.$i, "type"=>"select", "section"=>"Menu links",  "group"=>"Menu item ".$i, "value"=> "", "list"=> $target );
		}
		$fields=array(	
			array("label"=>"Button Text", "name"=>'button_text', "type"=>"text",  "section"=>"Button",  "group"=>"Button", "value"=> "", "default"=>"",),		
			array("label"=>"Button Align", "name"=>'button_align', "type"=>"select",  "section"=>"Button",  "group"=>"Button", "value"=> "", "default"=>"center", "list"=> $align),	
			array("label"=>"Button Icon", "name"=>'button_icon', "type"=>"select",  "section"=>"Button", "group"=>"Button", "value"=> "", "list"=> $icons),							
			array("label"=>"All Buttons Style Id", "name"=>'style_id', "type"=>"select",  "section"=>"Style Section",  "group"=>"Style","value"=> "", "list"=> $style_ids),	
				
		);
		$fields=array_merge($menu_links, $fields);
		return $fields;
	}
	public function meta_box(){		
		add_meta_box("settings", "Style Settings", array($this, "meta_box_html"), "wpi_des_but_smb", "normal", "high");
	}
	
	public function meta_box_html($post){
		wp_nonce_field("wpi_db_meta_box","wpi_db_meta_box_nonce");	
		$output= WPiTemplate::html($post->ID, $this->fields());	
		
		$classes=WPiDesButCommon::get_button_style_class($post->style_id);		
		if($post->button_icon!=""){$button_icon_class="wpi_icon wpi_icon_".$post->button_icon;}else{$button_icon_class="";}		
		if(trim($post->button_text)==""){$no_text_class="wpi_no_text";}else{$no_text_class="";}
		
		$styles_list=WPiDesButCommon::get_styles();	
		$modal= WPiTemplate::create_modal();	
		
		$button_id= "<div class='wpi_id wpi_icon wpi_icon_videocamera'><i></i><div class='wpi_info'><div class='wpi_text'>".$post->ID."</div><div>Smart ID: </div></div></div>";		
		$button_id=WPiDesButCommon::get_id(array("id"=>$post->ID, "label"=>"Smart ID"));
		$shortcode= "<div id='wpi_shortcode' class='wpi_icon wpi_icon_star'><i></i><div class='wpi_text'>[wpi_designer_button smart_id=".$post->ID."]</div></div>";
		$links="<div id='wpi_links'><a href='post-new.php?post_type=wpi_des_but_sty'>Create New Style</a></div>";
		$dummy_text="<div class='wpi_dummy_text'>
<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ullamcorper fermentum massa.</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In dictum gravida tellus at varius. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ac mauris magna. Aenean porttitor nisl in enim ornare, eu pretium lacus lobortis. Vestibulum tincidunt dignissim volutpat. Cras mollis urna vitae erat pulvinar, quis finibus turpis viverra. Maecenas iaculis nisl sapien, ut efficitur dolor vestibulum id.</p><p>Pellentesque blandit sed velit id viverra. Fusce molestie consequat laoreet. Nulla sagittis dui ut consectetur sollicitudin. Integer lobortis scelerisque elit, a fermentum felis. Donec a tortor eget tortor tincidunt suscipit. Donec ipsum tortor, dignissim non sapien eu, rhoncus eleifend ligula. Cras sed velit euismod lacus rutrum pellentesque ac a libero. Vivamus posuere velit ac convallis interdum. Integer enim orci, volutpat tempor massa sed, rhoncus fermentum odio. Phasellus at porttitor lectus, ac lobortis ipsum. Quisque metus lorem, condimentum euismod augue eget, tempus semper sapien. Donec lacinia turpis vel nunc vestibulum tempor. Etiam ut pharetra quam. Fusce sed sem sed est dictum luctus non in nibh.</p></div>";
		$preview="<div id='wpi_preview' class=' button_wrap'>".$dummy_text."<div id='wpi_smb' class='wpi_smart_buttons  wpi_smart_buttons_".$post->ID."''>
		<ul id='wpi_menu_links' class='wpi_menu_links'>
		<li><div><span class='wpi_heading'>".$post->heading1."</span> <span class='wpi_subheading'>".$post->subheading1."</span></div><a href=''></a></li>
		<li><div><span class='wpi_heading'>".$post->heading2."</span> <span class='wpi_subheading'>".$post->subheading2."</span></div><a href=''></a></li>
		<li><div><span class='wpi_heading'>".$post->heading3."</span> <span class='wpi_subheading'>".$post->subheading3."</span></div><a href=''></a></li>
		<li><div><span class='wpi_heading'>".$post->heading4."</span> <span class='wpi_subheading'>".$post->subheading4."</span></div><a href=''></a></li>
		<li><div><span class='wpi_heading'>".$post->heading5."</span> <span class='wpi_subheading'>".$post->subheading5."</span></div><a href=''></a></li>
		</ul>
		<a id='wpi_smart_button' class='wpi_designer_button {$classes} {$button_icon_class} {$no_text_class}' href='#'><i class='wpi_icon'></i><span class='wpi_text'>".$post->button_text."</span></a> <a class='wpi_designer_button {$classes} wpi_icon wpi_icon_facebook href='#'><i class='wpi_icon'></i><span class='wpi_text'>Facebook</span></a> <a class='wpi_designer_button {$classes} wpi_icon wpi_icon_twitter href='#'><i class='wpi_icon'></i><span class='wpi_text'>Twitter</span></a>		
		</div></div>";
		
		
		$print="";
		$info="<div class='wpi_info'>".$print."</div>";		
		
		$help=self::help();	
		
		$args=array(
			array("id"=>"wpi_styles", "text"=>"Styles", "content"=>$styles_list), 
			array("id"=>"wpi_icons", "text"=>"icons", "content"=>""), 
			array("id"=>"wpi_help", "text"=>"Help", "content"=>$help, "active"=>true),
			array("type"=>"toggle"), 
		);	
		$tabs=WPiTemplate::create_tabs($args);
		
		$visual_header="<div id='wpi_visual_header'>".$button_id."</div>";
		$action="";	
		
		$content="<div class='wpi_db wpi_des_but_smb'>";			
			$content.="<div id='wpi_designer_button_box' class='wpi_row'>";		
			$content.="<div class='wpi_13 wpi_input'>".$output.$shortcode.$links."</div>";
			$content.="<div class='wpi_23 wpi_visual'><div class='wpi_visual_holder'>";
				$content.=$visual_header;				
				$content.=$preview;							
				$content.=$tabs;	
				$content.=$modal;					
			$content.="</div></div>";	
		$content.="</div>";	
		
		echo $content;
	}
	
	public function save_post($post_id){
		if(!isset($_POST['wpi_db_meta_box_nonce'])) return;
		if(!wp_verify_nonce($_POST["wpi_db_meta_box_nonce"],"wpi_db_meta_box"))return;
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if(!current_user_can("edit_post", $post_id))return;		
		//$field_names=WPiTools::get_field_names($this->fields());		
		WPiData::update_post_meta($post_id, $this->fields());				
	}
	public function get_smart() {	
		
		$custom_css = "";
		$args = array(			
			'post_type' => 'wpi_des_but_smb', 'post_status'=>array('publish'), 'numberposts'  => -1
		); 
		$smart_button_styles = get_posts($args); 
					
		foreach ( $smart_button_styles as $s ) :				
			$element=".wpi_smart_button_".$s->ID;					
			$classes=array(
				array(
					"element"=> $element,
					"styles"=> array(
						"text-align"=> $s->button_align,
					),				
				),
			);
			
			$custom_css.=WPiCss::build_css($classes);		
        endforeach;
		return $custom_css;		
	}	
	public function inline_styles() {		
		wp_enqueue_style(
			'custom-style',
			WPIDB_URL . 'custom_script.css'
		);		
		$custom_css.=$this->get_smart();		
		wp_add_inline_style( 'custom-style', $custom_css );
	}			
	public function help(){
		$help_args=array("notes"=>array(
		"To add icon to the button select icon from above 'Icons' tab", 
		"To apply style to button select your created style from 'Styles' tab.",
		"Copy generated shortcode from left panel and paste it in any post/page.",
		));
		$help=WPiDesButCommon::get_help_tab($help_args);
		return $help;
	}
}
$smart_buttons_page=new WPiDesButSmB;

