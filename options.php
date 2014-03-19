<?php

// ------------------------------------------------------------------------
// PLUGIN PREFIX:
// ------------------------------------------------------------------------
// A PREFIX IS USED TO AVOID CONFLICTS WITH EXISTING PLUGIN FUNCTION NAMES.
// WHEN CREATING A NEW PLUGIN, CHANGE THE PREFIX AND USE YOUR TEXT EDITORS
// SEARCH/REPLACE FUNCTION TO RENAME THEM ALL QUICKLY.
// ------------------------------------------------------------------------

// 'veuse_staff_' prefix is derived from [p]plugin [o]ptions [s]tarter [k]it

// ------------------------------------------------------------------------
// REGISTER HOOKS & CALLBACK FUNCTIONS:
// ------------------------------------------------------------------------
// HOOKS TO SETUP DEFAULT PLUGIN OPTIONS, HANDLE CLEAN-UP OF OPTIONS WHEN
// PLUGIN IS DEACTIVATED AND DELETED, INITIALISE PLUGIN, ADD OPTIONS PAGE.
// ------------------------------------------------------------------------

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'veuse_staff_add_defaults');
register_uninstall_hook(__FILE__, 'veuse_staff_delete_plugin_options');
add_action('admin_init', 'veuse_staff_init' );
add_action('admin_menu', 'veuse_staff_add_options_page');
add_filter( 'plugin_action_links', 'veuse_staff_plugin_action_links', 10, 2 );

// --------------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_uninstall_hook(__FILE__, 'veuse_staff_delete_plugin_options')
// --------------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE USER DEACTIVATES AND DELETES THE PLUGIN. IT SIMPLY DELETES
// THE PLUGIN OPTIONS DB ENTRY (WHICH IS AN ARRAY STORING ALL THE PLUGIN OPTIONS).
// --------------------------------------------------------------------------------------

// Delete options table entries ONLY when plugin deactivated AND deleted
function veuse_staff_delete_plugin_options() {
	delete_option('veuse_staff_options');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_activation_hook(__FILE__, 'veuse_staff_add_defaults')
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE PLUGIN IS ACTIVATED. IF THERE ARE NO THEME OPTIONS
// CURRENTLY SET, OR THE USER HAS SELECTED THE CHECKBOX TO RESET OPTIONS TO THEIR
// DEFAULTS THEN THE OPTIONS ARE SET/RESET.
//
// OTHERWISE, THE PLUGIN OPTIONS REMAIN UNCHANGED.
// ------------------------------------------------------------------------------

// Define default option settings
function veuse_staff_add_defaults() {
	$tmp = get_option('veuse_staff_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('veuse_staff_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(	"css" => "1",
						"lightbox" => "1"

		);
		update_option('veuse_staff_options', $arr);
	}
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_init', 'veuse_staff_init' )
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_init' HOOK FIRES, AND REGISTERS YOUR PLUGIN
// SETTING WITH THE WORDPRESS SETTINGS API. YOU WON'T BE ABLE TO USE THE SETTINGS
// API UNTIL YOU DO.
// ------------------------------------------------------------------------------

// Init plugin options to white list our options
function veuse_staff_init(){
	register_setting( 'veuse_staff_plugin_options', 'veuse_staff_options', 'veuse_staff_validate_options' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_menu', 'veuse_staff_add_options_page');
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_menu' HOOK FIRES, AND ADDS A NEW OPTIONS
// PAGE FOR YOUR PLUGIN TO THE SETTINGS MENU.
// ------------------------------------------------------------------------------

// Add menu page
function veuse_staff_add_options_page() {
	add_options_page('Veuse Staff Options Page', 'Veuse Staff', 'manage_options', __FILE__, 'veuse_staff_render_form');
	//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	add_submenu_page( 'edit.php?post_type=staff', __('Usage','veuse-staff'), __('Usage','veuse-staff'), 'manage_options', __FILE__, 'veuse_staff_render_form' );
}



// ------------------------------------------------------------------------------
// CALLBACK FUNCTION SPECIFIED IN: add_options_page()
// ------------------------------------------------------------------------------
// THIS FUNCTION IS SPECIFIED IN add_options_page() AS THE CALLBACK FUNCTION THAT
// ACTUALLY RENDER THE PLUGIN OPTIONS FORM AS A SUB-MENU UNDER THE EXISTING
// SETTINGS ADMIN MENU.
// ------------------------------------------------------------------------------

// Render the Plugin options form

function ilc_admin_tabs( $current = 'shortcodes' ) {
    $tabs = array( 'shortcodes' => 'Shortcodes', 'settings' => 'Settings', 'advanced' => 'Advanced' );
   
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?post_type=staff&page=veuse-staff/options.php&tab=$tab'>$name</a>";

    }
    echo '</h2>';
}



function veuse_staff_render_form() {
	?>
	<div class="wrap">

		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php _e('Usage and settings','veuse-staff');?></h2>
		<p><?php _e('Instructions on how to display your teams.','veuse-staff');?></p>

		<?php
		
		wp_nonce_field( "ilc-settings-page" ); 
		
		if ( isset ( $_GET['tab'] ) ) ilc_admin_tabs($_GET['tab']); else ilc_admin_tabs('shortcodes'); 
		?>
		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('veuse_staff_plugin_options'); ?>
			<?php $options = get_option('veuse_staff_options'); ?>

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			
			<?php
			if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
			else $tab = 'shortcodes';
			?>
			
			<table class="form-table">
			
				<?php 
				    switch ( $tab ){
					 case 'shortcodes' :
					 ?>
				<tr>
					<th scope="row"><strong><?php _e('Shortcode','veuse-staff');?></strong></th>
					<td>
						<code>
						[veuse_staff teams=" { team-slugs, separated by commas } " columns=" { 2,3 or 4 } " mobile_columns=" { 1 or 2 } " title=" { true or false }" bio=" { true or false } " team-description=" { true or false } " excerpt_limit=" { maximum number of characters } " link=" { true or false} " more_link=" { true or false} "]
						</code>
					</td>
				
				</tr>
				
				<tr>
					<th scope="row"><strong><?php _e('Parameters','veuse-staff');?></strong></th>
					<td>
											
						<p><strong>Teams</strong><br>
						Enter the slug of the team you want to display. If you want to display several teams, enter more slugs separated by comma.<br> 
						Example: <code>team="management, marketing, support"</code> Required. Default is <em>none</em></p>
						<p></p>
						
						<p><strong>Columns</strong><br>
						Enter how many columns you want in the grid in the team-listing.<br>
						Example: <code>columns="3"</code> Optional. Default is 3 columns</p>
						<p></p>
						
						<p><strong>Mobile columns</strong><br>
						Enter how many columns you want in the grid when viewed on small screens (768px width and lower).<br>
						Example: <code>mobile_columns="3"</code> Optional. Default is 1 column</p>
						<p></p>
						
						<p><strong>Bio</strong><br>
						Set to true if you want to display the biography in the staff-list<br>
						Example: <code>bio="true"</code> Optional. Default is false</p>
						<p></p>
						
						<p><strong>Excerpt limit</strong><br>
						Set to true if you want to display the number of characters displayed for biography in staff-list<br>
						Example: <code>excerpt_limit="true"</code> Optional. Default is 1000</p>
						<p></p>
						
						<p><strong>Link</strong><br>
						Set to true if you want the entries to link to a single-post for the staff-member<br>
						Example: <code>link="false"</code> Optional. Default is true</p>
						<p></p>
						
						<p><strong>More-link</strong><br>
						Set to true if you want to add a visible text-link in the entries to link to a single-post for the staff-member<br>
						Example: <code>more_link="true"</code> Optional. Default is false</p>
						<p></p>
						
					</td>
				
				</tr>
				
				<?php
			      break;
			      case 'settings' :
				  ?>
				<tr>
					<th scope="row"><strong><?php _e('Enable plugin stylesheet','veuse-staff');?></strong></th>
					<td>
						<input name="veuse_staff_options[css]" type="checkbox" <?php if(isset($options['css'])) echo 'checked="checked"'; ?>/>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><strong><?php _e('Portrait settings','veuse-staff');?></strong></th>
					<td>
						<select name="veuse_staff_options[portrait_ratio]">
							<option value="portrait" <?php if(isset($options['portrait_ratio']) && $options['portrait_ratio'] == 'portrait' ) echo 'selected="selected"'; ?>><?php _e('Portrait','veuse-staff');?></option>
							<option value="landscape" <?php if(isset($options['portrait_ratio']) && $options['portrait_ratio'] == 'landscape' ) echo 'selected="selected"'; ?>><?php _e('Landscape','veuse-staff');?></option>
							<option value="square" <?php if(isset($options['portrait_ratio']) && $options['portrait_ratio'] == 'square' ) echo 'selected="selected"'; ?>><?php _e('Square','veuse-staff');?></option>
						</select>
						<span class="description"><?php _e('Select the aspect ratio of portrait images.','veuse-staff');?></span>
					</td>
				</tr>
				
								
				<tr>
					<td colspan="2">
						<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes','veuse-staff') ?>" /></p>
					</td>
				</tr>
				
				<?php
			      break;
			      case 'advanced' :
				  ?>
				  
				  <tr>
					<th scope="row"><strong><?php _e('Advanced','veuse-staff');?></strong></th>
					<td>
						<p><strong>If you want to customize how the staff-member is output on single staff posts, you can do the following:</strong></p>
						
						<p>Locate the function <code>veuse_staff_filter_content()</code> in the veuse-staff.php in your plugin folder. Copy this function to your themes functions.php  and give the function a new name, ie. <code>my_theme_filter_staff_content()</code></p>
						
						<p>Add this code to your functions.php:</p>
						
						<code>remove_filter('the_content', 'veuse_staff_filter_content');</code> and
						<code>add_filter('the_content', 'my_theme_filter_staff_content');</code>
						
						<p>Now you can edit the new function in functions.php to suit your needs</p>
					</td>
				</tr>
				
				<?php
			      break;
			   } ?>

			</table>
		</form>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function veuse_staff_validate_options($input) {
	 // strip html from textboxes
	//$input['css'] =  	$options['css']; // Sanitize textarea input (strip html tags, and escape characters)
	//$input['lightbox'] =$options['lightbox']; // Sanitize textarea input (strip html tags, and escape characters)

	//$input['txt_one'] =  wp_filter_nohtml_kses($input['txt_one']); // Sanitize textbox input (strip html tags, and escape characters)
	return $input;
}

// Display a Settings link on the main Plugins page
function veuse_staff_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$veuse_staff_links = '<a href="'.get_admin_url().'options-general.php?page=WP-Staff-members/veuse-staff.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $veuse_staff_links );
	}

	return $links;
}

?>