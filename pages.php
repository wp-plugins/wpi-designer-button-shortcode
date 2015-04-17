<?php
class WPiDesButSty{	
	public function __construct(){
		add_action("add_meta_boxes", array($this, "meta_box"));
	}
	public function meta_box(){		
		add_meta_box("settings", "Style Settings", array("WPiDesButSty", "meta_box_html"), "wpi_des_but_sty", "normal", "high");
	}
	public function meta_box_html($post){
		echo 123;
	}
}
$styles_page=new WPiDesButSty;

