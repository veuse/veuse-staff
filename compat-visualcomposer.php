<?php 


function veuse_get_teams(){

	$teams = get_terms( 'team', array('hide_empty' => 1 ));
	
	foreach ($teams as $team){
	
		$teams_list[$team->name] = $team->name; 
	
	}
	   
	return $teams_list;	
	
}


add_action( 'init', 'veuse_staff_integrateWithVC' );


function veuse_staff_integrateWithVC() {
	
	if(function_exists('vc_map')){	
	   	    
	   vc_map( array(
	      "name" => __("Team"),
	      "base" => "veuse_staff",
	      "class" => "",
	      "category" => __('Siteman'),
	      //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
	      //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
	      "params" => array(
	         array(
	            "type" => "textfield",
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("Title", "veuse-elements"),
	            "param_name" => "title",
	            "value" => __("", "veuse-elements"),
	            "description" => __("", "veuse-elements")
	         ),
	         
	         array(
	            "type" => "checkbox",
	            "value" => veuse_get_teams(),
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("Select team(s)", "veuse-elements"),
	            "param_name" => "teams",
	            //"value" => __("Default params value"),
	            //"description" => __("Description for foo param.", "veuse-elements")
	         ),
	         
	         array(
	            "type" => "dropdown",
	            "value" => array('1','2','3','4'),
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("Columns desktop)", "veuse-elements"),
	            "param_name" => "columns",
	            "description" => __("Number of columns on large screens", "veuse-elements")
	         ),
	         
	         array(
	            "type" => "dropdown",
	            "value" => array('1','2','3','4'),
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("Columns mobile", "veuse-elements"),
	            "param_name" => "mobile_columns",
	            "description" => __("Number of columns on small screens", "veuse-elements")
	         ),
	         
	         array(
	            "type" => "dropdown",
	            "value" => array('Yes' => 'true', 'No' => 'false'),
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("Show portrait", "veuse-elements"),
	            "param_name" => "image",
	            //"description" => __("Show portrait", "veuse-elements")
	         ),
	         
	         array(
	            "type" => "dropdown",
	            "value" => array('Yes' => 'true', 'No' => 'false'),
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("Show biography", "veuse-elements"),
	            "param_name" => "biography",
	            //"description" => __("Show portrait", "veuse-elements")
	         ),
	         
	         array(
	            "type" => "dropdown",
	            "value" => array('Yes' => 'true', 'No' => 'false'),
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("Show designation", "veuse-elements"),
	            "param_name" => "designation",
	            //"description" => __("Show portrait", "veuse-elements")
	         ),
	         
	       
	         
	         array(
	            "type" => "dropdown",
	            "value" => array('Do nothing' => 'false', 'Link to single employee page' => 'true'),
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("On click", "veuse-elements"),
	            "param_name" => "link",
	            //"description" => __("Show portrait", "veuse-elements")
	         )
	      )
	   ) );
	 
	   
	}
}



/*
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
							'contact_info'	=> 'true',
							'perpage'		=> '-1'

*/
?>