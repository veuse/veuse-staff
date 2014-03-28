<?php

// Set-up Action and Filter Hooks
add_action('admin_init', 'veuse_staffdocumentation_init' );
add_action('admin_menu', 'veuse_staffdocumentation_add_options_page');


// Init plugin options to white list our options
function veuse_staffdocumentation_init(){
	register_setting( 'veuse_staffdocumentation_plugin_options', 'veuse_staffdocumentation_options', 'veuse_staffdocumentation_validate_options' );
}


// Add menu page
function veuse_staffdocumentation_add_options_page() {
	add_submenu_page( 'edit.php?post_type=staff', __('Portfolio documentation page'), __('Documentation'), 'edit_themes', 'staff_documentation', 'veuse_staffdocumentation_render_form');

}



function get_all_staff_tabs(){
	
	 $tabs = array( 
    	
    	'intro' 		=> 'Intro', 
    	'categories' 	=> __('Teams','veuse-staff'), 
    	'posts'			=> __('Staff members','veuse-staff'),
    	'display'		=> 'Showing teams on pages'
    	
    	);
    return $tabs;
}

// Render the Plugin options form
function veuse_staffdocs_admin_tabs( $current = 'intro' ) {

    
    $tabs = get_all_staff_tabs();  
     
    echo '<h3 class="nav-tab-wrapper" style="padding-left:0; border-bottom:0;">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' style='padding-top:6px; margin:0px -1px -1px 0; border:1px solid #ccc;' href='?post_type=staff&page=staff_documentation&tab=$tab'>$name</a>";

    }
    echo '</h3>';
}


function veuse_staffdocumentation_render_form(){


    $plugin_name = 'Veuse Staff';

	
	
	
	?>
	<style>
		#veuse-staffdocumentation-wrapper a { text-decoration: none;}
		#veuse-staffdocumentation-wrapper p {  }
		#veuse-staffdocumentation-wrapper ul { margin-bottom: 30px !important;}
		ul.inline-list { list-style: disc !important; list-style-position: inside;}
		ul.inline-list li { display: inline; margin-right: 10px; list-style: disc;}
		ul.inline-list li:after { content:'-'; margin-left: 10px; }
	</style>
	<div class="wrap">

		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php echo $plugin_name;?> <?php _e('documentation','veuse-staffdocumentation');?></h2>
		<p><?php
			
			echo sprintf( __( 'Here you find instructions on how to use the %s plugin. For more in-depth info, please check out http://veuse.com/support.', 'veuse-staffdocumentation' ), $plugin_name);?>
		</p>
		
		<?php
		
		$docpath = get_stylesheet_directory().'/includes/staffdocumentation';
		
		if ( isset ( $_GET['tab'] ) ) veuse_staffdocs_admin_tabs($_GET['tab']); else veuse_staffdocs_admin_tabs('intro'); ?>
		
		<div id="veuse-staffdocumentation-wrapper" style="padding:20px 0; max-width:800px;">	

		<?php
		
		if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; else $tab = 'intro';
		

			
			switch ($tab ) {	
				
				
				case $tab :
				
					echo '<div>';
					
					//$text = file_get_contents($docpath."/pages/$tab.php");		
					//echo nl2br($text);
					
					include("pages/$tab.php");
										
					echo '</div>';
					
					break;
				
			} // end switch			
			

	
		?>
		<div>
		<br>
		<hr>
		<br>
		<a href="http://veuse.com/support" class="button">Support forum</a>
		</div>
		</div>
		
	</div>
	<?php
}
?>