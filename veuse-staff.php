<?php
/*
Plugin Name: Veuse Staff
Plugin URI: http://veuse.com/veuse-staff
Description:  Fully localized. Templates included. This is an add-on for the Veuse Pagebuilder plugin.
Version: 1.0
Author: Andreas Wilthil
Author URI: http://veuse.com
License: GPL3
Text Domain: veuse-staff
Domain Path: /languages
Tags: team,staff,emplyees,contact
GitHub Plugin URI: https://github.com/veuse/veuse-staff
GitHub Branch: master


Copyright 2014  Andreas Wilthil  (email : andreas.wilthil@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


*/

__('Veuse Staff', 'veuse-staff' ); /* Dummy call for plugin name translation. */


class VeuseStaff {

	private $strVeuseStaffURI = '';
	private $strVeuseStaffPATH = '';
	

	function __construct() {
	
		
		
		$this->strVeuseStaffURI  = plugin_dir_url(__FILE__) ;
		$this->strVeuseStaffPATH = plugin_dir_path(__FILE__) ;
				
		add_action('init', array(&$this,'veuse_staff_enqueue_styles'));
	
		add_action('init', array(&$this,'register_staff'));
		
		add_shortcode('veuse_staff', array(&$this,'veuse_staff_shortcode'));
		add_filter( 'gettext', array(&$this,'wpse22764_gettext'), 10, 2 );
	
		add_action('plugins_loaded', array(&$this,'veuse_staff_action_init'));
		 
		add_filter('manage_staff_posts_columns',  array ( $this,'veuse_staff_columns'));
		add_action('manage_staff_posts_custom_column', array ( $this,'veuse_staff_custom_columns'), 10, 2 );

      
    }
	
	/* Enqueue scripts
	============================================= */
	
	function veuse_staff_enqueue_styles() {

		$plugin_options = get_option('veuse_staff_options');
		
		if(is_admin()){
			
			wp_register_style( 'staff-meta',  $this->strVeuseStaffURI . 'assets/css/staff-meta.css', array(), '', 'screen' );
			wp_enqueue_style ( 'staff-meta' );
			
		}

			wp_register_style( 'veuse-staff',  $this->strVeuseStaffURI . 'assets/css/veuse-staff.css', array(), '', 'screen' );
			wp_enqueue_style ( 'veuse-staff' );
	

	}
	
	/* Localization
	============================================= */
	function veuse_staff_action_init() {
	    load_plugin_textdomain('veuse-staff', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
	}
	
	
	/* Register post-type
	============================================= */
	
	public function register_staff() {

		$labels = array(
	        'name' => __( 'Staff', 'veuse-staff' ), // Tip: _x('') is used for localization
	        'singular_name' => __( 'Staff Member', 'veuse-staff' ),
	        'add_new' => __( 'New Staff Member', 'veuse-staff' ),
	        'add_new_item' => __( 'New Staff Member','veuse-staff' ),
	        'edit_item' => __( 'Edit Staff Member', 'veuse-staff' ),
	        'all_items' => __( 'All Staff Members','veuse-staff' ),
	        'new_item' => __( 'New Staff Member','veuse-staff' ),
	        'view_item' => __( 'View Staff Member','veuse-staff' ),
	        'search_items' => __( 'Search Staff Members','veuse-staff' ),
	        'not_found' =>  __( 'No Staff Members','veuse-staff' ),
	        'not_found_in_trash' => __( 'No Staff Members found in Trash','veuse-staff' ),
	        'parent_item_colon' => ''
	    );

		register_post_type('staff',
					array(
					'labels' => $labels,
					'public' => true,
					'show_ui' => true,
					'_builtin' => false, // It's a custom post type, not built in
					'_edit_link' => 'post.php?post=%d',
					'capability_type' => 'post',
					'hierarchical' => false,
					'rewrite' => array("slug" => "staff"), // Permalinks
					'query_var' => "staff", // This goes to the WP_Query schema
					'supports' => array('page-attributes'),
					'menu_icon' => 'dashicons-groups',
					'menu_position' => 30,
					'publicly_queryable' => true,
					'exclude_from_search' => false,
					'show_in_nav_menus' => false
					));

		$taxlabels = array(
		        'name' => __( 'Teams', 'veuse-staff' ), // Tip: _x('') is used for localization
		        'singular_label' => __( 'Team', 'veuse-staff' ),
		        'add_new' => __( 'Add New Team', 'veuse-staff' ),
		        'add_new_item' => __( 'Add New Team','veuse-staff' ),
		        'edit_item' => __( 'Edit Team', 'veuse-staff' ),
		        'all_items' => __( 'All Teams','veuse-staff' ),
		        'new_item' => __( 'New Team','veuse-staff' ),
		        'view_item' => __( 'View Team','veuse-staff' ),
		        'search_items' => __( 'Search Teams','veuse-staff' ),
		        'not_found' =>  __( 'No Teams found','veuse-staff' ),
		        'parent_item_colon' => ''
		    );

		register_taxonomy("team",
			array("staff"),
			array(
				"hierarchical" => true,
				"labels" => $taxlabels,
				"rewrite" => true,
				"show_ui" => true,
				'show_in_nav_menus' => false
				)
			);
		}
		
		
		
		
		function veuse_staff_custom_columns($column, $post_id) {
		
			global $post;
					
			switch ($column) {
			 	
			 		
			 	
			 		case 'title' :
			 	
						echo get_the_title();
						break;
				 	
				 	case 'thumbnail' :
				 		$portrait = get_post_meta($post_id,'veuse_staff_portrait', true);
				 		$image_src = wp_get_attachment_image_src($portrait, 'medium');
				 		
					 	echo '<img src="'.$image_src[0].'" style="max-width:200px; max-height:200px;"/>'; 
						break;
						
					case 'bio' :
				 	
					 	echo get_the_excerpt();
						break;
					
					case 'team' :
				 	
					 	$taxonomy = 'team';
						$post_type = get_post_type($post_id);
						$terms = get_the_terms($post_id, $taxonomy);
			
						if (!empty($terms) ) {
							foreach ( $terms as $term ){
						    	$post_terms[] ="<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " .esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
						    }
						       echo join('', $post_terms );
						}
						
						break;
					
			
					
			}			
					
		}
		
		function veuse_staff_columns($columns){
					
			$columns = array(
					"cb" => "<input type=\"checkbox\" />",
					"title" => __("Staff Member Name","veuse-staff"),
					"thumbnail" => __("Photo","veuse-staff"),
					"bio" => __("Short bio","veuse-staff"),
					"team" => __("Team","veuse-staff")
			);
			return $columns;
		}
		
		
		/* Shortcode
		============================================= */
		
		function veuse_staff_shortcode( $atts, $content = null ) {
		
				 extract(shortcode_atts(array(
							'teams' 		=> '',
							'columns'		=> '3',
							'mobile_columns'=> '2',
							'template'		=> 'page',
							'image'			=> 'true',
							'title'			=> 'true',
							'designation'	=> 'true',
							'biography'		=> 'false',
							'excerpt_limit'	=> '1000',
							'link'			=> 'true',
							'more_link'		=> 'false',
							'url'			=> '',
							'social_media' 	=> 'true',
							'contact_info'	=> 'true'
		
		
				    ), $atts));
				    		
		
				$content = '';
		
				$teams = explode(',', $teams);
		
				foreach ($teams as $team):
				
					
		
					if ($template == 'page'){
		
		
						$args = array(
					    'post_type' => 'staff',
					    'post_status' => 'publish',
					    'orderby'	=> 	'menu_order',
					    'posts_per_page' => -1,
					    'order' => 'ASC',
					    'tax_query' => array(
								        array(
								            'taxonomy' => 'team',
								            'field' => 'slug',
								            'terms' => $team
								            )
								         )
								      );

						$staff_query = get_posts( $args );
		
					}
		
					else{
						$args = array(
					    'post_type' => 'staff',
					    'post_status' => 'publish',
					    'orderby'	=> 	'menu_order',
					    'posts_per_page' => -1,
					    'order' => 'ASC',
					    'tax_query' => array(
								        array(
								            'taxonomy' => 'team',
								            'field' => 'slug',
								            'terms' => $team
								            )
								         )
								      );
		
						$staff_query = get_posts( $args );
					}
		
					$termname = get_term_by( 'slug', $team, 'team');
			
					if($title == 'yes')
					$content .= '<h3>' . $termname->name . '</h3>';
			
					
					
					ob_start();
					include($this->veuse_staff_locate_part('loop-staff','template-parts'));
					$content.= ob_get_contents();
					ob_end_clean();
			
		
				endforeach;
		
				return $content;
		
		
		}
		
		
		
		/* Find template part

		Makes it possible to override the loop with
		a custom theme loop-slider.php
		
		============================================ */
		
		public function veuse_staff_locate_part($file, $dir) {
		
			if ( file_exists( get_stylesheet_directory().'/'. $file .'.php'))
			   	$filepath = get_stylesheet_directory().'/'. $file .'.php';
			
			elseif ( file_exists(get_template_directory().'/'. $file .'.php'))
				$filepath = get_template_directory().'/'. $file .'.php';
			else
				$filepath = $this->strVeuseStaffPATH .$file.'.php';
			
			return $filepath;
		}
		
		
		
		
		function wpse22764_gettext( $translation, $original ){
		
			global $post_type;
		
			if('staff' == $post_type){
		
			    if ( 'Attributes' == $original) {
			        return __('Staff Member order','veuse-staff');
			    }
		
			    if ( 'Order' == $original) {
			        return __('Change the staff members order of appearance in staff-lists. Negative values are supported.','veuse-staff');
			    }
			 }
		    return $translation;
		}
		
		
		public function veuse_staff_excerpt($excerpt,$count){	
			$excerpt = strip_tags($excerpt);
			$excerpt = substr($excerpt, 0, $count);
			$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
			//$excerpt = $excerpt.'...';
			return $excerpt;
		}
		
		

}

$staff = new VeuseStaff;


/* Widget */
require_once(plugin_dir_path(__FILE__). 'widget.php');

/* Plugin options */
require_once(plugin_dir_path(__FILE__). 'options.php');

/* Post meta */
require_once(plugin_dir_path(__FILE__). 'post-meta.php');


/* Template redirect

 * Searches for the template-files in the theme folder.
 * If not found, locates template in plugin folder.
========================================================== */
/*

function veuse_staff_redirect() {

    global $wp, $post;

    $plugindir = dirname( __FILE__ );


    $templatefilename = 'template-staff.php';

    if (is_page_template($templatefilename)) {

        if (file_exists(get_stylesheet_directory() . '/' . $templatefilename))
        	$return_template = get_stylesheet_directory() . '/' . $templatefilename;

        elseif (file_exists(get_template_directory() . '/' . $templatefilename))
        	$return_template = get_template_directory() . '/' . $templatefilename;
        else
        	$return_template = $plugindir . '/' . $templatefilename;

        veuse_staff_theme_redirect($return_template);
     }


    $taxonomy = 'team';
    $templatefilename = 'taxonomy-team.php';

    if (is_tax($taxonomy)) {

        if (file_exists(get_stylesheet_directory() . '/' . $templatefilename))
        	$return_template = get_stylesheet_directory() . '/' . $templatefilename;

        elseif (file_exists(get_template_directory() . '/' . $templatefilename))
        	$return_template = get_template_directory() . '/' . $templatefilename;
        else
        	$return_template = $plugindir . '/' . $templatefilename;

        veuse_staff_theme_redirect($return_template);
     }


 }
 

function veuse_staff_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}

*/

//add_action('template_redirect', 'veuse_staff_redirect');


function add_admin_scripts( $hook ) {

    global $post;

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        if ( 'staff' === $post->post_type ) { 
        	wp_enqueue_script(  'media-upload');    
            wp_enqueue_script(  'veuse-staff', plugin_dir_url(__FILE__).'assets/js/script.js' );
             
        }
    }
}
add_action( 'admin_enqueue_scripts', 'add_admin_scripts', 10, 1 );











/* Filter the content to insert forms */
if(!function_exists('veuse_staff_filter_content')){

	function veuse_staff_filter_content($content) {

		global $post;

		if( is_singular( 'staff') /* && is_main_query()*/ ) {

			/* Get meta into variables */
			$veuse_staff_options = get_option('veuse_staff_options');
			
			$portrait_ratio = $veuse_staff_options['portrait_ratio'];
			
			if(!empty($portrait_ratio)){
				
				if		($portrait_ratio == 'portrait')	{ $ratio = 0.7;}
				elseif	($portrait_ratio == 'landscape'){ $ratio = 1.3;}
				elseif  ($portrait_ratio == 'square')	{ $ratio = 1;  }
				
			} else { $ratio = 1;}

			$portrait 		= get_post_meta($post->ID, 'veuse_staff_portrait', true);
			$facebook 		= get_post_meta($post->ID, 'veuse_staff_facebook', true);
			$twitter 		= get_post_meta($post->ID, 'veuse_staff_twitter', true);
			$linkedin		= get_post_meta($post->ID, 'veuse_staff_linkedin', true);
			$phone			= get_post_meta($post->ID, 'veuse_staff_phone', true);
			$mobile			= get_post_meta($post->ID, 'veuse_staff_mobile', true);
			$email			= get_post_meta($post->ID, 'veuse_staff_email', true);
			$qualifications	= get_post_meta($post->ID, 'veuse_staff_qualifications', true);
			$education		= get_post_meta($post->ID, 'veuse_staff_education', true);
			$experience		= get_post_meta($post->ID, 'veuse_staff_experience', true);

			$post = get_post($post->ID);
			$employee_portrait ='';
			
			if(!empty($portrait) && !isset($veuse_staff_options['disable_image']) ){
				$employee_portrait .= '<div class="veuse-employee-portrait">';
				$employee_portrait .=  veuse_retina_interchange_image( $portrait, 800, 800 * $ratio, true);
				$employee_portrait .= '</div>';
			}
			
			$content_start = '<div class="veuse-employee-content">';
			/* Contact info */
			$contact_info = '';		
			$contact_info .= '<h4>'. __('Contact info','veuse-staff') .'</h4>';
			$contact_info .= '<ul class="veuse-single-employee-data contact-info">';
			if(!empty($phone))		$contact_info .= '<li class="veuse-employee-phone"><span>'. __('Phone:','veuse-staff'). '</span> '. $phone .'</li>';
			if(!empty($mobile))		$contact_info .= '<li class="veuse-employee-mobile"><span>'. __('Mobile:','veuse-staff'). '</span> '. $mobile .'</li>';
			if(!empty($email))		$contact_info .= '<li class="veuse-employee-email"><span>'. __('Email:','veuse-staff'). '</span> <a href="mailto:'. $email .'">'. $email .'</a></li>';
			$contact_info .= '</ul>';
			
			/* Social media */
			$social_links  = '';
			$social_links .= '<h4>'. __('Social media','veuse-staff') .'</h4>';
			$social_links .= '<ul class="veuse-single-employee-data">';
			if(!empty($facebook)) 	$social_links .= '<li class="veuse-employee-facebook"><a href="'. $facebook .'">'. __('Follow on Facebook','veuse-staff') .'</a></li>';
			if(!empty($twitter))	$social_links .= '<li class="veuse-employee-twitter"><a href="'. $twitter .'" >'. __('Follow on Twitter','veuse-staff') .'</a></li>';
			if(!empty($linkedin))	$social_links .= '<li class="veuse-employee-linkedin"><a href="'. $linkedin.'" >'. __('Follow on LinkedIn','veuse-staff') .'</a></li>';
			$social_links .= '</ul>';
			
			$content_end = '</div>';

			$content = $employee_portrait.$content_start.$content.$contact_info.$social_links.$content_end;
			return $content;
		}

		else {

			return $content;
		}
	}

	add_filter('the_content', 'veuse_staff_filter_content');
}








/* Insert retina image */

if(!function_exists('veuse_retina_interchange_image')){

	function veuse_retina_interchange_image($img_url, $width, $height, $crop){

		$imagepath = '<img src="'. mr_image_resize($img_url, $width, $height, $crop, 'c', false) .'" data-interchange="['. mr_image_resize($img_url, $width, $height, $crop, 'c', true) .', (retina)]" alt=""/>';
	
		return $imagepath;
	
	}
}



/**
  *  Resizes an image and returns the resized URL. Uses native WordPress functionality.
  *
  *  The function supports GD Library and ImageMagick. WordPress will pick whichever is most appropriate.
  *  If none of the supported libraries are available, the function will return the original image url.
  *
  *  Images are saved to the WordPress uploads directory, just like images uploaded through the Media Library.
  * 
  *  Supports WordPress 3.5 and above.
  * 
  *  Based on resize.php by Matthew Ruddy (GPLv2 Licensed, Copyright (c) 2012, 2013)
  *  https://github.com/MatthewRuddy/Wordpress-Timthumb-alternative
  * 
  *  License: GPLv2
  *  http://www.gnu.org/licenses/gpl-2.0.html
  *
  *  @author Ernesto MŽndez (http://der-design.com)
  *  @author Matthew Ruddy (http://rivaslider.com)
  */

if(!function_exists('mr_image_resize')){

	add_action('delete_attachment', 'mr_delete_resized_images');
	
	function mr_image_resize($url, $width=null, $height=null, $crop=true, $align='c', $retina=false) {
	
	  global $wpdb;
	
	  // Get common vars
	  $args = func_get_args();
	  $common = mr_common_info($args);
	
	  // Unpack vars if got an array...
	  if (is_array($common)) extract($common);
	
	  // ... Otherwise, return error, null or image
	  else return $common;
	
	  if (!file_exists($dest_file_name)) {
	
	    // We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
	    $query = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE guid='%s'", $url);
	    $get_attachment = $wpdb->get_results($query);
	
	    // Load WordPress Image Editor
	    $editor = wp_get_image_editor($file_path);
	    
	    // Print possible wp error
	    if (is_wp_error($editor)) {
	      if (is_user_logged_in()) print_r($editor);
	      return null;
	    }
	
	    if ($crop) {
	
	      $src_x = $src_y = 0;
	      $src_w = $orig_width;
	      $src_h = $orig_height;
	
	      $cmp_x = $orig_width / $dest_width;
	      $cmp_y = $orig_height / $dest_height;
	
	      // Calculate x or y coordinate and width or height of source
	      if ($cmp_x > $cmp_y) {
	
	        $src_w = round ($orig_width / $cmp_x * $cmp_y);
	        $src_x = round (($orig_width - ($orig_width / $cmp_x * $cmp_y)) / 2);
	
	      } else if ($cmp_y > $cmp_x) {
	
	        $src_h = round ($orig_height / $cmp_y * $cmp_x);
	        $src_y = round (($orig_height - ($orig_height / $cmp_y * $cmp_x)) / 2);
	
	      }
	
	      // Positional cropping. Uses code from timthumb.php under the GPL
	      if ($align && $align != 'c') {
	        if (strpos ($align, 't') !== false) {
	          $src_y = 0;
	        }
	        if (strpos ($align, 'b') !== false) {
	          $src_y = $orig_height - $src_h;
	        }
	        if (strpos ($align, 'l') !== false) {
	          $src_x = 0;
	        }
	        if (strpos ($align, 'r') !== false) {
	          $src_x = $orig_width - $src_w;
	        }
	      }
	      
	      // Crop image
	      $editor->crop($src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height);
	
	    } else {
	     
	      // Just resize image
	      $editor->resize($dest_width, $dest_height);
	     
	    }
	
	    // Save image
	    $saved = $editor->save($dest_file_name);
	    
	    // Print possible out of memory error
	    if (is_wp_error($saved)) {
	      @unlink($dest_file_name);
	      if (is_user_logged_in()) print_r($saved);
	      return null;
	    }
	
	    // Add the resized dimensions and alignment to original image metadata, so the images
	    // can be deleted when the original image is delete from the Media Library.
	    if ($get_attachment) {
	      $metadata = wp_get_attachment_metadata($get_attachment[0]->ID);
	      if (isset($metadata['image_meta'])) {
	        $md = $saved['width'] . 'x' . $saved['height'];
	        if ($crop) $md .= ($align) ? "_${align}" : "_c";
	        $metadata['image_meta']['resized_images'][] = $md;
	        wp_update_attachment_metadata($get_attachment[0]->ID, $metadata);
	      }
	    }
	
	    // Resized image url
	    $resized_url = str_replace(basename($url), basename($saved['path']), $url);
	
	  } else {
	
	    // Resized image url
	    $resized_url = str_replace(basename($url), basename($dest_file_name), $url);
	
	  }
	
	  // Return resized url
	  return $resized_url;
	
	}
	
	// Returns common information shared by processing functions
	
	function mr_common_info($args) {
	
	  // Unpack arguments
	  list($url, $width, $height, $crop, $align, $retina) = $args;
	  
	  // Return null if url empty
	  if (empty($url)) {
	    return is_user_logged_in() ? "image_not_specified" : null;
	  }
	
	  // Return if nocrop is set on query string
	  if (preg_match('/(\?|&)nocrop/', $url)) {
	    return $url;
	  }
	  
	  // Get the image file path
	  $urlinfo = parse_url($url);
	  $wp_upload_dir = wp_upload_dir();
	  
	  if (preg_match('/\/[0-9]{4}\/[0-9]{2}\/.+$/', $urlinfo['path'], $matches)) {
	    $file_path = $wp_upload_dir['basedir'] . $matches[0];
	  } else {
	    return $url;
	  }
	  
	  // Don't process a file that doesn't exist
	  if (!file_exists($file_path)) {
	    return null; // Degrade gracefully
	  }
	  
	  // Get original image size
	  $size = @getimagesize($file_path);
	
	  // If no size data obtained, return error or null
	  if (!$size) {
	    return is_user_logged_in() ? "getimagesize_error_common" : null;
	  }
	
	  // Set original width and height
	  list($orig_width, $orig_height, $orig_type) = $size;
	
	  // Generate width or height if not provided
	  if ($width && !$height) {
	    $height = floor ($orig_height * ($width / $orig_width));
	  } else if ($height && !$width) {
	    $width = floor ($orig_width * ($height / $orig_height));
	  } else if (!$width && !$height) {
	    return $url; // Return original url if no width/height provided
	  }
	
	  // Allow for different retina sizes
	  $retina = $retina ? ($retina === true ? 2 : $retina) : 1;
	
	  // Destination width and height variables
	  $dest_width = $width * $retina;
	  $dest_height = $height * $retina;
	
	  // Some additional info about the image
	  $info = pathinfo($file_path);
	  $dir = $info['dirname'];
	  $ext = $info['extension'];
	  $name = wp_basename($file_path, ".$ext");
	
	  // Suffix applied to filename
	  $suffix = "${dest_width}x${dest_height}";
	
	  // Set align info on file
	  if ($crop) {
	    $suffix .= ($align) ? "_${align}" : "_c";
	  }
	
	  // Get the destination file name
	  $dest_file_name = "${dir}/${name}-${suffix}.${ext}";
	  
	  // Return info
	  return array(
	    'dir' => $dir,
	    'name' => $name,
	    'ext' => $ext,
	    'suffix' => $suffix,
	    'orig_width' => $orig_width,
	    'orig_height' => $orig_height,
	    'orig_type' => $orig_type,
	    'dest_width' => $dest_width,
	    'dest_height' => $dest_height,
	    'file_path' => $file_path,
	    'dest_file_name' => $dest_file_name,
	  );
	
	}
	
	// Deletes the resized images when the original image is deleted from the WordPress Media Library.
	
	function mr_delete_resized_images($post_id) {
	
	  // Get attachment image metadata
	  $metadata = wp_get_attachment_metadata($post_id);
	  
	  // Return if no metadata is found
	  if (!$metadata) return;
	
	  // Return if we don't have the proper metadata
	  if (!isset($metadata['file']) || !isset($metadata['image_meta']['resized_images'])) return;
	  
	  $wp_upload_dir = wp_upload_dir();
	  $pathinfo = pathinfo($metadata['file']);
	  $resized_images = $metadata['image_meta']['resized_images'];
	  
	  // Delete the resized images
	  foreach ($resized_images as $dims) {
	
	    // Get the resized images filename
	    $file = $wp_upload_dir['basedir'] . '/' . $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '-' . $dims . '.' . $pathinfo['extension'];
	
	    // Delete the resized image
	    @unlink($file);
	
		}
    }
}


?>