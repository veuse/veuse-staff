<?php

/**
	The file that displays the list of staff-members
	
	If you want to customize the loop, copy this file to your themes folder. 
	The themes loop-staff.php will overwrite the plugins loop.
	
*/

global $post;

/* Init the class */

$staff = new VeuseStaff;

/** 
	Shortcode params 
	
	These are the variables available from the shortcode:
		
	teams, columns, mobile_columns, image, title, position,  image, bio, excerpt_limit, link, more_link, url, contact_info
	
	
*/


/* Get the plugin settings */
$veuse_staff_options = get_option('veuse_staff_options');

/* Get portrait aspect ratio from plugin settings */
$portrait_ratio = $veuse_staff_options['portrait_ratio'];

/* Define portrait ratio based on selected aspect ratio */			
if(isset($portrait_ratio)){
				
	if		($portrait_ratio == 'portrait')	{ $ratio = 1.3;}
	elseif	($portrait_ratio == 'landscape'){ $ratio = 0.7;}
	elseif  ($portrait_ratio == 'square')	{ $ratio = 1;  }
				
}

else { $ratio = 1;}


/* Image sizes */

if($columns == '1') { $imagesize = array( 'width' => 1000, 'height' => 1000 * $ratio ); }
if($columns == '2') { $imagesize = array( 'width' => 1000, 'height' => 1000 * $ratio ); }
if($columns == '3') { $imagesize = array( 'width' => 666, 'height' => 666 * $ratio ); }
if($columns == '4') { $imagesize = array( 'width' => 500, 'height' => 500 * $ratio ); }


$i = 0;

?>

<ul class="small-block-staff-grid-<?php echo $mobile_columns;?> large-block-staff-grid-<?php echo $columns;?>">

<?php

/* The loop */

$tmp_post = $post;

foreach( $staff_query as $employee ) :	setup_postdata($employee);
	
	$post = $employee;
	
	$name = $employee->post_title;
	$excerpt = $employee->post_excerpt;
	
	
	/* Post meta */
	$position = get_post_meta($employee->ID, 'veuse_staff_position', true);
	$portrait = wp_get_attachment_url( get_post_thumbnail_id());
	$image_src = wp_get_attachment_image_src($portrait, 'full');
	$phone 	  = get_post_meta($employee->ID, 'veuse_staff_phone', true);
	$mobile   = get_post_meta($employee->ID, 'veuse_staff_mobile', true);
	$email    =	get_post_meta($employee->ID, 'veuse_staff_email', true);
	$facebook =	get_post_meta($employee->ID, 'veuse_staff_facebook', true);	
	$linkedin =	get_post_meta($employee->ID, 'veuse_staff_linkedin', true);
	$twitter  = get_post_meta($employee->ID, 'veuse_staff_twitter', true);
	
	?>
	
	<li <?php post_class();?>>
		<article>
			<?php if(isset($portrait) && $image == true):?>
			<div class="veuse-staff-entry-thumbnail">
					<?php if($link == 'true'){?>	<a href="<?php the_permalink();?>"> <?php } ?>
					<?php echo veuse_retina_interchange_image( $portrait, $imagesize['width'], $imagesize['height'], true);	?>
					<?php if($link == 'true'){?>	</a> <?php } ?>
			</div>
			<?php endif;?>

			<div class="veuse-staff-entry-content">

				<?php if($link == 'true'){?>	<a href="<?php the_permalink();?>"> <?php } ?>
				<?php if($title == 'true'){?>	<h4><?php echo $name;?></h4> <?php } ?>
				<?php if($link == 'true'){?>	</a> <?php } ?>
				
				<?php if($designation == 'true'){?><div class="veuse-staff-entry-meta"><?php echo $position;?></div><?php } ?>
					
				<?php if($biography == 'true'){?><p><?php echo $staff->veuse_staff_excerpt($excerpt,$excerpt_limit);?></p><?php } ?>
				
				<p>
					<?php if($contact_info == 'true'){?>
					<?php if(!empty($phone)):?><?php _e('Phone:','veuse-staff');?> <?php echo $phone;?><br><?php endif;?>
					<?php if(!empty($mobile)):?><?php _e('Mobile:','veuse-staff');?> <?php echo $mobile;?><br><?php endif;?>
					<?php if(!empty($email)):?><?php _e('Email:','veuse-staff');?> <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a><br><br><?php endif;?>
					<?php } ?>
					
					<?php if($social_media == 'true'){?>
					<?php if(!empty($facebook)):?><a title="<?php _e('Facebook','veuse-staff');?>" href="<?php echo $facebook;?>" target="_blank"><?php _e('Facebook','veuse-staff');?></a><br><?php endif;?>
					<?php if(!empty($linkedin)):?><a title="<?php _e('LinkedIn','veuse-staff');?>" href="<?php echo $linkedin;?>" target="_blank"><?php _e('LinkedIn','veuse-staff');?></a><br><?php endif;?>
					<?php if(!empty($twitter)):?><a title="<?php _e('Twitter','veuse-staff');?>"  href="<?php echo $twitter;?>" target="_blank"><?php _e('Twitter','veuse-staff');?></a><?php endif;?>
					<?php } ?>
				</p>
	

				<?php if(!empty($employee->post_content) && $link == 'true'):?><a href="<?php echo get_permalink( $employee->ID );?>" class="button primary tiny centered"><?php _e('Get to know','veuse-staff');?> <?php echo $name;?></a><?php endif;?>

			</div>
		</article>
	</li>
	
<?php 
	endforeach;
	wp_reset_query();
	$post = $tmp_post;
?>
</ul>