<?php

class WPi_DesignerButtonActivation{		
	public function myplugin_activate() {
		add_option('Activated_Plugin',__FILE__);
		if( ADVANCED_GOOGLE_ANALYTICS != get_option( 'designer_button' ) ) {	
			add_option( 'designer_button', WPI_DESIGNER_BUTTON_SHORTCODE );
		}
	}	
	public function my_admin_notice() {
		if(is_admin()&&get_option('Activated_Plugin')==__FILE__) {
		 delete_option('Activated_Plugin');
		 ?>
		<div class="updated">
			<p><?php  _e( 'The Designer Button is activated. To get started  <a href="admin.php?page=global_settings">click here</a>.', 'wpi_designer_button_shortcode' ); ?></p>
		</div>
		<?php
		}	   
	}	
}