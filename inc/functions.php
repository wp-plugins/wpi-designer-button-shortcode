<?php
class WPiControls{
	public static function select($args=array()){
		$defaults=array("name"=>"", "class"=>" ", "value"=>"", "list"=>array(0));
		extract(wp_parse_args($args,$defaults));
		$output="<select name='{$name}' id='{$name}'>";
		foreach($list as $key=>$val){
			if($value!=""){
				if($key==$value) $selected="selected"; else $selected="";
			}else{
				$selected="";
			}
			$output.="<option value='{$key}' $selected>{$val}</option>";
		}
		$output.="</select>";
		return $output;
	}
	public static function text($args=array()){
		$defaults=array("name"=>"", "class"=>" ", "value"=>"");
		extract(wp_parse_args($args,$defaults));
		$output="<input type='text' name='{$name}' id='{$name}' value='{$value}' />";
		return $output;	
	}
	public static function hidden($args=array()){
		$defaults=array("name"=>"", "class"=>" ", "value"=>"");
		extract(wp_parse_args($args,$defaults));
		$output="<input type='hidden' name='{$name}' id='{$name}' value='{$value}' />";
		return $output;	
	}
	public static function button($args=array()){
		$defaults=array("name"=>"", "class"=>" ", "value"=>"");
		extract(wp_parse_args($args,$defaults));
		$output="<input type='button' name='{$name}' id='{$name}' value='{$value}' />";
		return $output;	
	}
	public static function wp_image($args=array()){
		$defaults=array("name"=>"", "class"=>" ", "value"=>"");
		extract(wp_parse_args($args,$defaults));
		if($value!=""){$upload_button_text="Change Image";}else{$upload_button_text="Select Image";}
		$output="<input type='hidden' name='{$name}' id='{$name}' value='{$value}' class='wpi_wp_image'/>";
		if($value!=""){$no_preview_style="display:none";}else{$no_preview_style="";}
		$output.="<div class='wpi_wp_image_no_preview' style='{$no_preview_style}'>No image selected</div>";
		$output.="<img  src='{$value}' id='{$name}_preview' class='wpi_wp_image_preview'/>";
		$output.="<button type='button' id='{$name}_remove_button' class='wpi_wp_image_remove_button button remove-button''>Remove</button>";
		$output.="<button type='button' name='{$name}_button' id='{$name}_button' class='wpi_wp_image_button button upload-button'>Select Image</button>";
		return $output;	
	}
	public static function textarea($args=array()){
		$defaults=array("name"=>"", "class"=>" ", "value"=>"");
		extract(wp_parse_args($args,$defaults));
		$output="<textarea type='text' name='{$name}' id='{$name}'>{$value}</textarea>";
		return $output;	
	}
}
class WPiData{
	public static function get_post_meta($post_id, $vars=array()){
		if(is_array($vars)){
			foreach($vars as $var){
				$data[$var]=get_post_meta($post_id, $var,true);
			}
		}else{
			$data=get_post_meta($post_id, $vars,true);
		}
		return $data;
	}
	public static function update_post_meta($post_id, $fields){		
		foreach($fields as $field){
			if(isset($field['name']) && $field['name']!=""){
				$var=$field['name'];
				if(isset($_POST[$var]) && $_POST[$var]!=""){
					$val=$_POST[$var];
				}else{
					$val="";
				}
				//$val=sanitize_text_field($var); 
				//$val=esc_html($var);
				if(!isset($field['type'])){
					$field['type']="";
				}
				if($field['type']=="wp_image"){
					$val=$val;
				}else{
					$val=wptexturize( $val );
				}			
				update_post_meta($post_id, $var, $val);
			}
		}
		
	}
	public static function register_post_type($data=array()){
		$args=$data['args'];
		$labels=$args['labels'];
		$singular=$labels['singular'];
		$plural=$labels['name'];
		$labels["new_item"]="New ".$singular;
		$labels["add_new_item"]="Add New ".$singular;
		$args["public"]=true;
		$args["exclude_from_search"]=true;	
		$args["show_in_nav_menus"]=false;
		$args["publicly_queryable"]=false;		
		register_post_type($data['post_type'], $args);
		foreach($data['remove_support'] as $rs){
			remove_post_type_support($data['post_type'],$rs);
		}
	}
}
class WPiTools{
	public static function get_field_names($fields){
		//$fields=$this->fields();
		$field_names=array();
		foreach($fields as $field){
			if(isset($field['name']) && $field['name']!=""){
				$field_names[]=$field['name'];
			}
		}
		return $field_names;	
	}
	public static function array2object($array){
		$object=array();
		//$array=array("facebook","foursquare");
		foreach($array as $val){		
			$object[$val]=$val;
		}		
		return $object;
	}	
	public static function extend( $defaults, $args ) {          
		foreach ( $args as $k => $v ):
		   if ( !array_key_exists( $k, $defaults ))
			   unset( $args[$k] );
		endforeach;
		$new_args = $args + $defaults;          
		return $new_args;
	}
	public static function get_list($args){
		$list=array();
		foreach ($args['list'] as $k){
			if(isset($args['suffix'])&& $args['suffix']!=""){
				$v=$k.$args['suffix'];
			}else{
				$v=$k;
			}
			if(isset($args['keys'])&& $args['keys']==false){
				$list[]=$v;
			}else{
				$list[$v]=$v;
			}
		}	
		return $list;
	}
	public static function hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);	   
	   return $rgb; 
	}
}
class WPiCss{
	public static function get_glow($arg){	
		$class="";
		if($arg['glow_size']!="" && $arg['glow_color']!="" ){
			$class.="box-shadow:0 0 ".$arg['glow_size']." ".$arg['glow_color']." !important; ";
		}else if($arg['glow_size']!=""){
			$class.="box-shadow:0 0 ".$arg['glow_size']." !important; ";
		}
		return $class;
	}	
	public static function get_css($args=array()){
		$class="";		
		foreach($args as $key => $val){
			if($val!=""){
				$class.="{$key}:{$val} !important; ";
			}
		}		
		return $class;
	}
	public static function get_style_id_css($post_id, $section, $args=array()){
		$glow=array();
		$output="";
		foreach($args as $arg){
			if($arg['section']==$section && isset($arg['css_property']) && $arg['css_property']!=""){
			
				$value=WPiData::get_post_meta($post_id, $arg['name']);
				
				if($arg['css_property']=="glow"){
					$glow[$arg['name']]=$value;					
				}else{					
					$output.=self::get_css(array($arg['css_property']=> $value));
				}
				//$output.= $arg['section']." ".$arg['name']."\n";	
			}else if($section=="all"){
				if(isset($arg['css_property'])){
					$value=WPiData::get_post_meta($post_id, $arg['name']);
					$output.=self::get_css(array($arg['css_property']=> $value));
				}
			}	
		}
		if(count($glow)){
			$output.=self::get_glow($glow);
		}		
		return $output;
	}
	public static function build_css($classes){
		$out="";
		foreach ( $classes as $class ) :
			$class['styles']=self::set_class($class['styles']);
			$out.= $class['element']."{";
				foreach ( $class['styles'] as $key => $val ) :
					if($val!="") $out.= $key. ":" .$val. ";";					
				endforeach;	
			$out.= "}";
		endforeach;		
		return $out;
	}
	public static function set_class($styles){
		$styles=self::set_opacity($styles);
		$styles=self::set_text_shadow($styles);
		$styles=self::set_box_shadow($styles);
		$styles=self::set_blur($styles);
		return $styles;
	}
	public static function set_border_radius($styles){
		$radius=false;
		if(isset($styles['border-radius'])) {$style=$styles['border-radius']; $radius=true;}
		if($radius==true){			
			self::set_cross_browser("border-radius",$style, $styles);				
		}	
		return $styles;
	}
	public static function set_opacity($styles){
		$opacity=false;
		if(isset($styles['opacity'])) {$style=$styles['opacity']; $opacity=true;}
		if($opacity==true){
			$styles['-ms-filter']="progid:DXImageTransform.Microsoft.Alpha(Opacity=".($style*100).")";		
			$styles['filter']="alpha(opacity=".($style*100).")";
			self::set_cross_browser("opacity",$style, $styles);				
		}	
		return $styles;
	}
	public static function set_text_shadow($styles){
		$shadow=false;
		$default=array("text-shadow-color"=>"#000000", "text-shadow-opacity"=>0.3, "text-shadow-x"=>"1px", "text-shadow-y"=>"1px", "text-shadow-blur"=>"1px");
		foreach($default as $key => $val){
			if(isset($styles[$key]) && $styles[$key]!=""){
				$shadow=true;
			};
		};		
		$o=WPiTools::extend($default, $styles);	
		$rgb=WPiTools::hex2rgb($o["text-shadow-color"]);
		$color="rgba(". $rgb[0] .",". $rgb[1] .",". $rgb[2] .",". $o['text-shadow-opacity'] .")";
		$style=$o["text-shadow-x"] ." ". $o["text-shadow-y"] ." ". $o["text-shadow-blur"] ." ". $color;
		if($shadow==true){	
			$styles=self::set_cross_browser("text-shadow", $style, $styles);
			foreach($default as $key => $val){
				unset($styles[$key]);
			}
		}		
			
		return $styles;
	}
	public static function set_box_shadow($styles){
		$shadow=false;
		$default=array("box-shadow-color"=>"#000000", "box-shadow-opacity"=>0.3, "box-shadow-x"=>"1px", "box-shadow-y"=>"1px", "box-shadow-blur"=>"1px", "box-shadow-inset"=>"");
		foreach($default as $key => $val){
			if(isset($styles[$key]) && $styles[$key]!=""){
				$shadow=true;
			};
		};		
		$o=WPiTools::extend($default, $styles);	
		$rgb=WPiTools::hex2rgb($o["box-shadow-color"]);
		$color="rgba(". $rgb[0] .",". $rgb[1] .",". $rgb[2] .",". $o['box-shadow-opacity'] .")";
		$style=$o["box-shadow-inset"] ." ". $o["box-shadow-x"] ." ". $o["box-shadow-y"] ." ". $o["box-shadow-blur"] ." ". $color;
		if($shadow==true){	
			$styles=self::set_cross_browser("box-shadow", $style, $styles);
			foreach($default as $key => $val){
				unset($styles[$key]);
			}
		}		
			
		return $styles;
	}
	public static function set_blur($styles){
		$blur=false;
		$default=array("blur"=>"0px");
		foreach($default as $key => $val){
			if(isset($styles[$key]) && $styles[$key]!=""){
				$blur=true;
			};
		};		
		$o=WPiTools::extend($default, $styles);			
		$style="blur(".$o["blur"] .")";
		if($blur==true){
			if(isset($styles['-ms-filter'])) {
				$ms_filter=$styles['-ms-filter'];
			}else{
				$ms_filter="";
			}
			$styles=self::set_cross_browser("filter", $style, $styles);		
			foreach($default as $key => $val){
				unset($styles[$key]);
			}
		}		
			
		return $styles;
	}
	public static function set_cross_browser($type, $style, $styles){
		if(in_array($type, array("filter", "box-shadow", "text-shadow")) && $style!=""){
			$styles=self::set_multi_style($type, $style, $styles);		
		}else if($type!=""){				
			$styles['-webkit-'.$type]=$style;
			$styles['-moz-'.$type]=$style;			
			$styles['-o-'.$type]=$style;
			$styles[$type]=$style;			
		};
		return $styles;
	}
	public static function set_multi_style($type, $style, $styles){
		if(in_array($type,$styles) && $styles[$type]!="") {$style=$styles[$type].", ".$style;}else{$style=$style;}			
		if($style!=""){		
			$styles['-webkit-'.$type]=$style;
			$styles['-moz-'.$type]=$style;
			$styles['-o-'.$type]=$style;
			$styles[$type]=$style;				
		};				
		return $styles;	
	}
}
class WPiTemplate{
	public static function html($post_id, $args=array()){
		$sec_alt=0;	
		$c=0;
		$out="";
		$section="";
		$html="";
		$ss=array();		
		foreach($args as $arg){	
			if(isset($arg['section']) && $arg['section']!="" && isset($arg['name']) && $arg['name']!=""){	
				$section_name=$arg['section'];	
			}else{
				$section_name='settings';	
			}			
			$sections[$section_name]['backup'][]=$arg;
		}
		foreach($sections as $key => $section){	
			foreach($section['backup'] as $data){	
				if(isset($data['group']) && $data['group']!=""){	
					$group_name=$data['group'];	
				}else{
					$group_name='no_group';	
				}
				$sections[$key][$group_name][]=$data;
			}			
			unset($sections[$key]['backup']);		
		}
				
		$header='
		<div class="wpi_header">
			<span class="wpi_menu genericon genericon-menu">
			  <div class="wpi_back genericon genericon-previous"></div>      
			</span>
			<span class="wpi_heading">Settings</span>
		  </div><!-- header--> ';
		  		
		$sec='<div class="wpi_sections">';		
		foreach($sections as $key => $section){	
			$sec.='<div class="wpi_section" data-target_id="'.sanitize_title($key).'" data-target="'.$key.'">'.$key.'<div class="wpi_open genericon genericon-next"></div></div>';			
		}
		$sec.='</div><!-- sections-->';		
		
		$c=0;
		$sections_content='<div class="wpi_sections_content">';
		foreach($sections as $key => $section){	
			if($c==0){$visible="";}else{$visible="wpi_none";}
			$c++;
			if(count($section)<=1){
				$disable_accordion="wpi_disable_accordion";
			}else{
				$disable_accordion="";
			}
			$sections_content.='<div class="wpi_section_content '.$visible.' " id="'.sanitize_title($key).'">';
			$sections_content.='<div class="wpiAccordion '.$disable_accordion.'">';				
			foreach($section as $group_key => $group){
				$sections_content.='<li>';
				if(	$group_key=="no_group"){
					$sections_content.='<h3>All<i class="genericon genericon-next"></i></h3>';
				}else{					
					$sections_content.='<h3>'.$group_key.' <i class="genericon genericon-next"></i></h3>';
				}				
				$sections_content.='<div class="wpi_accordion_content">';		
				foreach($group as $data){	
					$data['value']=WPiData::get_post_meta($post_id, $data['name']);	
					if($data['type']	== "text" ){
						$input=WPiControls::text(array("name"=>$data['name'], "value"=>$data['value']));
					}else if($data['type'] == "textarea" ){
						$input=WPiControls::textarea(array("name"=>$data['name'], "value"=>$data['value']));
					}else if($data['type'] == "hidden" ){
						$input=WPiControls::hidden(array("name"=>$data['name'], "value"=>$data['value']));
					}else if($data['type'] == "button" ){
						$input=WPiControls::button(array("name"=>$data['name'], "value"=>$data['value']));
					}else if($data['type'] == "wp_image" ){
						$input=WPiControls::wp_image(array("name"=>$data['name'], "value"=>$data['value']));
					}else if($data['type'] == "select" ){
						$input=WPiControls::select(array("name"=>$data['name'], "value"=>$data['value'], "list"=>$data['list'] ));
					}
					if($data['type'] != "hidden" ){
						$sections_content.='<div class="label">'.$data['label'].'</div>';
					}
					$sections_content.='<div class="input">'.$input.'</div>';
				};
				$sections_content.='</div><!-- accordion_content-->';
				$sections_content.='</li>';
			};				
			$sections_content.='</div><!-- wpiAccordion-->';
			$sections_content.='</div><!-- section content-->';	
		}
		$sections_content.='</div><!-- sections content-->';
		  
		$html.='<div  class="wpiHolder">';
		$html.=$header;
		$html.='<div class="wpi_content"><div class="wpi_content_holder">';
		$html.=$sec;
		$html.=$sections_content;		
		$html.="</div><!-- content holder--></div><!-- content-->";
		$html.="</div><!-- wpiHolder-->";
		
		$sample=
		'<div class="wpiHolder">
		  <div class="wpi_header">
			<span class="wpi_menu genericon genericon-menu">
			  <div class="wpi_back genericon genericon-previous"></div>      
			</span>
			<span class="wpi_heading">Settings</span>
		  </div><!-- header-->  
		  <div class="wpi_content">
			<div class="wpi_content_holder">
			  <div class="wpi_sections">
				<div class="wpi_section" data-target_id="heading_settings" data-target="Heading settings">Slide Heading </div>
				<div class="wpi_section" data-target_id="text_settings" data-target="Text settings">Slide Text</div>
			  </div><!-- sections-->
			  <div class="wpi_sections_content">
				<div class="wpi_section_content" id="heading_settings">
				  <div class="label">Heading</div>
				  <div class="input"><input type="text" value="heading"/></div>
				</div><!-- section content-->
				<div class="wpi_section_content" id="text_settings">
				  <div class="label">Text</div>
				  <div class="input"><input type="text" value="text"/></div>
				</div><!-- section content-->
			  </div><!-- sections content-->
			</div><!-- content holder-->
		  </div><!-- content-->
		</div><!-- wpiHolder-->';
		return $html;
		//$out=print_r($sections,true);
		//return $out;
	}	
	public static function create_tabs($args){
		
		$content="<div class='wpi_tabs'>";	
		foreach($args as $tab){
			if(isset($tab['active']) && $tab['active']==true){$active="active";}else{$active="";};
			$content.="<a href='#".$tab['id']."' class='wpi_tab {$active}'>".$tab['text']."</a>";
		}
		$content.="</div>";
		$content.="<div class='wpi_tabs_content'>";	
		foreach($args as $tab){
			if(isset($tab['active']) && $tab['active']==true){$none="";}else{$none="wpi_none";};
			$content.="<div id='".$tab['id']."' class='wpi_tab_content {$none}'>".$tab['content']."</div>";
		}
		$content.="</div>";
		return $content;
	}
}
?>