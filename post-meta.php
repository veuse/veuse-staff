<?php

/* Post meta
=========================================================== */

add_action( 'add_meta_boxes', 'veuse_staff_meta_box_add' );

function veuse_staff_meta_box_add()
{
	add_meta_box( 'veuse_staff_meta', __('Staff Member','veuse-staff'), 'veuse_staff_meta_box_cb', 'staff', 'normal', 'high' );
	
}

function veuse_staff_meta_box_cb( $post )
{
	$prefix = 'veuse_staff';

	$values = get_post_custom( $post->ID );
	//$name = isset( $values[$prefix.'_name'] ) ? esc_attr( $values[$prefix.'_name'][0] ) : '';
	$portrait = isset( $values[$prefix.'_portrait'] ) ?  $values[$prefix.'_portrait'][0]  : '';
	$image_src = wp_get_attachment_image_src($portrait, 'full');
	$position = isset( $values[$prefix.'_position'] ) ? esc_attr( $values[$prefix.'_position'][0] ) : '';
	$email 	  = isset( $values[$prefix.'_email'] ) ? esc_attr( $values[$prefix.'_email'][0] ) : '';
	$phone	  = isset( $values[$prefix.'_phone'] ) ? esc_attr( $values[$prefix.'_phone'][0] ) : '';
	$mobile	  = isset( $values[$prefix.'_mobile'] ) ? esc_attr( $values[$prefix.'_mobile'][0] ) : '';
	$twitter  = isset( $values[$prefix.'_twitter'] ) ? esc_attr( $values[$prefix.'_twitter'][0] ) : '';
	$facebook = isset( $values[$prefix.'_facebook'] ) ? esc_attr( $values[$prefix.'_facebook'][0] ) : '';
	$linkedin = isset( $values[$prefix.'_linkedin'] ) ? esc_attr( $values[$prefix.'_linkedin'][0] ) : '';
	$qualifications = isset( $values[$prefix.'_qualifications'] ) ? esc_attr( $values[$prefix.'_qualifications'][0] ) : '';
	$education = isset( $values[$prefix.'_education'] ) ? esc_attr( $values[$prefix.'_education'][0] ) : '';
	$experience = isset( $values[$prefix.'_experience'] ) ? esc_attr( $values[$prefix.'_experience'][0] ) : '';


	$editor_settings = array( 'editor_css' => '<style>.mceIframeContainer { background:#fff; }</style>' );


	wp_nonce_field( 'veuse_staff_nonce', 'meta_box_nonce' );?>

	<div class="inside">
		<table class="" width="100%">
			<tbody>
				<tr>
					<td colspan="2">
					
						<div class="box box-60">
							
							<div id="titlediv"><div id="titlewrap">
							<label for="post_title" id="title-prompt-text"><?php echo empty($post->post_title) ? __('Name of staff member','veuse-staff') :''; ?></label>
							<input type="text" name="post_title" id="title" value="<?php echo isset($post->post_title) ? $post->post_title :''; ?>" size="60"/></div></div>
							<h4 id="title-prompt-text"><?php _e('Designation','veuse-staff');?></h4>
							<input type="text" name="<?php echo $prefix;?>_position" id="<?php echo $prefix;?>_position" value="<?php echo $position; ?>" size="30"/>
							
							<h4><?php _e('Short Biography','veuse-staff');?></h4>
							<textarea type="text" name="post_excerpt" id="post_excerpt" style="width:100%;" rows="3"><?php echo $post->post_excerpt; ?></textarea>
							<div class="description"><?php _e('Enter a short bio, like an excerpt. Can be displayed in team-listings','veuse-staff');?></div>
							
						</div>
						<div id="portrait-uploader" class="box box-40">
							
							<div id="portrait-container">
								
								<?php 
								if(!empty($image_src)){
									
									echo '<img src="'.$image_src[0] .'">';
									
								}
								?>
								
							</div>
							<input type="hidden" name="<?php echo $prefix;?>_portrait" id="portrait" value="<?php echo $portrait;?>"/>
							<input type="button" name="staff-portrait" id="veuse-staff-image-upload" class="button button-primary" value="<?php _e('Add portrait','veuse-staff');?>"  style="<?php echo !empty($portrait) ? 'display:none;' : '';?>"/>
							<input type="button" name="" id="veuse-staff-image-remove" class="button button-primary" value="<?php _e('Remove portrait','veuse-staff');?>" style="<?php echo empty($portrait) ? 'display:none;' : '';?>"/>
						</div>
						
						
					</td>
				</tr>
				
				<tr>
					<td colspan="2"><h2><?php _e('Contact Info','veuse-staff');?></h2></td>
				</tr>
			
				<tr>
					<th scope="row" style="text-align:left"><label for="<?php echo $prefix;?>_email"><?php _e('Email','veuse-staff');?></label></th>
					<td><input type="text" name="<?php echo $prefix;?>_email" id="<?php echo $prefix;?>_email" value="<?php echo $email; ?>" size="30"/>
					</td>
				</tr>
			
				<tr>
					<th scope="row" style="text-align:left"><label for="<?php echo $prefix;?>_phone"><?php _e('Phone number','veuse-staff');?></label></th>
					<td><input type="text" name="<?php echo $prefix;?>_phone" id="<?php echo $prefix;?>_phone" value="<?php echo $phone; ?>" size="30"/>
					</span></td>
				</tr>
			
				<tr>
					<th scope="row" style="text-align:left"><label for="<?php echo $prefix;?>_mobile"><?php _e('Mobile number','veuse-staff');?></label></th>
					<td><input type="text" name="<?php echo $prefix;?>_mobile" id="<?php echo $prefix;?>_mobile" value="<?php echo $mobile; ?>" size="30"/>
					</span></td>
				</tr>
				
				<tr>
					<td colspan="2"><h2><?php _e('Social Media','veuse-staff');?></h2></td>
				</tr>
			
				<tr>
					<th scope="row" style="text-align:left"><label for="<?php echo $prefix;?>_twitter"><?php _e('Twitter','veuse-staff');?></label></th>
					<td><input type="text" name="<?php echo $prefix;?>_twitter" id="<?php echo $prefix;?>_twitter" value="<?php echo $twitter; ?>" size="30"/>
					<span class="description"><?php _e('Enter url to staff-members twitter profile page','veuse-staff');?></span></td>
				</tr>
			
				<tr>
					<th scope="row" style="text-align:left"><label for="<?php echo $prefix;?>_facebook"><?php _e('Facebook','veuse-staff');?></label></th>
					<td><input type="text" name="<?php echo $prefix;?>_facebook" id="<?php echo $prefix;?>_facebook" value="<?php echo $facebook; ?>" size="30"/>
					<span class="description"><?php _e('Enter url to staff-members facebook profile page','veuse-staff');?></span></td>
				</tr>
			
				<tr>
					<th scope="row" style="text-align:left"><label for="<?php echo $prefix;?>_linkedin"><?php _e('Linkedin','veuse-staff');?></label></th>
					<td><input type="text" name="<?php echo $prefix;?>_linkedin" id="<?php echo $prefix;?>_linkedin" value="<?php echo $linkedin; ?>" size="30"/>
					<span class="description"><?php _e('Enter url to staff-members linkedIn profile page','veuse-staff');?></span></td>
				</tr>
				<!--
				<tr>
					<td colspan="2"><h2><?php _e('Full Biography','veuse-staff');?></h2></td>
				</tr>
				
				<tr>
					<td colspan="2">
					<?php //wp_editor( $post->post_content, 'post_content', $editor_settings  );?>
				
					<div class="description"><br><?php //_e('Staff member biography. This is displayed on single-staff posts.','veuse-staff');?></div>
					
					</td>
					-->
				</tr>
			</tbody>
		</table>
	</div>
<?php }
	
	
add_action( 'save_post', 'veuse_staff_meta_box_save',2 );


function veuse_staff_meta_box_save( $post_id ){

	$prefix = 'veuse_staff';

	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'veuse_staff_nonce' ) ) return;

	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_posts' ) ) return;
	
	

	// now we can actually save the data
	/*
	
		$allowed = array(
		'a' => array( // on allow a tags
		'href' => array() // and those anchors can only have href attribute
		)
	);
	*/
	
	if( isset( $_POST[$prefix.'_portrait'] ) )
		update_post_meta( $post_id, $prefix.'_portrait',  esc_attr( $_POST[$prefix.'_portrait'] ));
	
	if( isset( $_POST[$prefix.'_position'] ) )
		update_post_meta( $post_id, $prefix.'_position',  esc_attr( $_POST[$prefix.'_position'] ));

	if( isset( $_POST[$prefix.'_email'] ) )
		update_post_meta( $post_id, $prefix.'_email',  esc_attr( $_POST[$prefix.'_email'] ));

	if( isset( $_POST[$prefix.'_phone'] ) )
		update_post_meta( $post_id, $prefix.'_phone',  esc_attr( $_POST[$prefix.'_phone'] ));

	if( isset( $_POST[$prefix.'_mobile'] ) )
		update_post_meta( $post_id, $prefix.'_mobile', esc_attr( $_POST[$prefix.'_mobile'] ));

	if( isset( $_POST[$prefix.'_twitter'] ) )
		update_post_meta( $post_id, $prefix.'_twitter',  esc_attr( $_POST[$prefix.'_twitter'] ));

	if( isset( $_POST[$prefix.'_facebook'] ) )
		update_post_meta( $post_id, $prefix.'_facebook',  esc_attr( $_POST[$prefix.'_facebook'] ));

	if( isset( $_POST[$prefix.'_linkedin'] ) )
		update_post_meta( $post_id, $prefix.'_linkedin',  esc_attr( $_POST[$prefix.'_linkedin'] ));

	if( isset( $_POST[$prefix.'_qualifications'] ) )
		update_post_meta( $post_id, $prefix.'_qualifications', esc_attr( $_POST[$prefix.'_qualifications'] ));

	if( isset( $_POST[$prefix.'_education'] ) )
		update_post_meta( $post_id, $prefix.'_education',  esc_attr( $_POST[$prefix.'_education'] ));

	if( isset( $_POST[$prefix.'_experience'] ) )
		update_post_meta( $post_id, $prefix.'_experience',  esc_attr( $_POST[$prefix.'_experience'] ));

}