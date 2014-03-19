<?php

class VeuseStaffWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'veuse_staff_widget', // Base ID
			__('Team Members (Veuse)','veuse-staff'), // Name
			array( 'description' => __( 'Add team members to your page', 'veuse-staff' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$team = $instance['team'];
		$columns = $instance['columns'];
				
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			?>
			
			<?php
			
			echo do_shortcode('[veuse_staff columns="'.$columns.'" teams="'.$team.'"]');
			
		
		echo $after_widget;
	}


	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
				
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['team'] = strip_tags( $new_instance['team'] );
		$instance['columns'] = strip_tags( $new_instance['columns'] );
		
		
		return $instance;
	}

	 
	public function form( $instance ) {
	
		global $widget, $wp_widget_factory, $wp_query;
		
		isset( $instance[ 'title' ] ) ? $title = $instance[ 'title' ] : $title = '';		
		isset($instance['team']) ? $team = $instance['team'] : $team = '';
		isset($instance['columns']) ? $columns = $instance['columns'] : $columns = '';
		
		?>

		<p>
		<label style="min-width:80px;" for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label style="min-width:80px;" for="<?php echo $this->get_field_id('team');?>"><?php _e('Team:','veuse-staff');?></label>
			<select name="<?php echo $this->get_field_name('team');?>">
		<?php
				
		$terms = get_terms( 'team', array('hide_empty' => 1 ));
        
        if( $terms ){
                            
            foreach( $terms as $term ){ ?>

            	<option value="<?php echo $term->slug;?>" <?php selected($term->slug, $team);?>> <?php echo $term->name;?></option>
            	<?php
     
            }
            
        }

		?>
		</select>
		</p>
		
		<p>
			<label style="min-width:80px;" for="<?php echo $this->get_field_id('columns');?>"><?php _e('Columns:','veuse-staff');?></label>
			<select name="<?php echo $this->get_field_name('columns');?>">
		  		<option value="1" <?php selected( $columns, '1' , true); ?>><?php _e('1 column','veuse-staff');?></option>
		  		<option value="2" <?php selected( $columns, '2' , true); ?>><?php _e('2 columns','veuse-staff');?></option>	
		  		<option value="3" <?php selected( $columns, '3' , true); ?>><?php _e('3 columns','veuse-staff');?></option>	
		  		<option value="4" <?php selected( $columns, '4' , true); ?>><?php _e('4 columns','veuse-staff');?></option>		  
		  	</select>
		</p>
		 

		<?php

	}

} 

add_action('widgets_init',create_function('','return register_widget("VeuseStaffWidget");'));
 
?>