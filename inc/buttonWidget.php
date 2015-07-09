<?php
class WPi_DesignerButtonWidget extends WP_Widget {	
	public function __construct() {
		parent::__construct(		
			'wpi_designer_button_widget',		
			__('WPi Designer Button Widget', 'wpi-designer-button-shortcode'),		
			array( 'description' => __( 'Using this widget to add WPi Designer Button Shortcodes in sidebars', 'wpi-designer-button-shortcode' ), )		
		);
	}
	public function widget( $args, $instance ) {
		$out="";
		$title = apply_filters( 'widget_title', $instance['title'] );
		$shortcodes = $instance['shortcodes'];
		$description = $instance['description'];
		$out.= $args['before_widget'];		
			if ( ! empty( $title ) )		
				$out.= $args['before_title'] . $title . $args['after_title'];
			if ( ! empty( $description ) )		
				$out.= "<p>".$description."</p>";			
			$out.= do_shortcode( $shortcodes );	
		$out.= $args['after_widget'];
		echo $out;
	}
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( '', 'wpi-designer-button-shortcode' );
		}	
		if ( isset( $instance[ 'shortcodes' ] ) ) {
			$shortcodes = $instance[ 'shortcodes' ];
		} else {
			$shortcodes = "";
		}	
		if ( isset( $instance[ 'description' ] ) ) {
			$description = $instance[ 'description' ];
		} else {
			$description = "";
		}		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        <p>
		<label for="<?php echo $this->get_field_id( 'shortcodes' ); ?>"><?php _e( 'Enter Only ShortCodes:' ); ?></label> 
		<textarea class="widefat" rows="5" cols="20" autocomplete="off" id="<?php echo $this->get_field_id( 'shortcodes' ); ?>" name="<?php echo $this->get_field_name( 'shortcodes' ); ?>"><?php echo esc_attr( $shortcodes ); ?></textarea>       
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:' ); ?></label> 
        <textarea class="widefat" rows="10" cols="20" autocomplete="off" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_attr( $description ); ?></textarea>		
		</p>
		<?php 
	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['shortcodes'] = ( ! empty( $new_instance['shortcodes'] ) ) ? strip_tags( $new_instance['shortcodes'] ) : '';
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';
		return $instance;
	}
}


?>