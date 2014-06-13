<?php
/*
Plugin Name: Asana Teamtrope Revenue Allocation Task
Plugin URI: http://booktrope.com
Description: Create an Asana task from a Gravity Form submit.
Version: 1.0.1
Author: Andy Roberts
Author URI: http://andylroberts.com
License: GPL2
*/

require_once("asana.php");
require ( dirname( __FILE__ ) . '/asana-revenue-admin.php' );
add_action( 'admin_menu', 'teamtrope_asana_menu' );

function add_task_to_asana($the_form_id, $entry)
{
	$asana = new Asana("e9zs4it.6VpYE4sxwb566PtfhIE6Ing9"); // Your API Key, you can get it in Asana
	
	$api_name = 'asana_api_key';
	$wkspace_name = 'asana_selected_workspace';
	$revenue_project_name = 'asana_revenue_project';
	$api_val = get_option( $api_name );
	$wkspace_val = get_option( $wkspace_name );
	$workspaceId = substr($wkspace_val, 0, strpos($wkspace_val, "&"));
	$projectId = get_option( $revenue_project_name );
	
	// First we create the task
	$task_detail = array(
	"workspace" => $workspaceId, // Workspace ID
	"name" => "Team Allocations for: " . $entry["4"],
	"assignee" => "834920479329", // Ken Shear 834920479329 ; Andy 803972355129
	"notes" => "Percentages: \n\rAuthor: " . $entry["24"] . "\n\rPct: " . $entry["10"] . 
	"\n\rBk Mgr: " . $entry["25"] . "\n\rPct: " . $entry["11"] . 
	"\n\rEditor: " . $entry["26"] . "\n\rPct: " . $entry["18"] . 
	"\n\rDesigner: " . $entry["27"] . "\n\rPct: " . $entry["13"] . 
	"\n\rProofreader: " . $entry["28"] . "\n\rPct: " . $entry["16"] .  
	"\n\rOther: " . $entry["20"] . "\n\rPct: " . $entry["12"] . 
	"\n\rDetails: " . "http://teamtrope.com/wp-admin/admin.php?page=gf_entries&view=entry&id=". $the_form_id  ."&lid=" . $entry["id"] . "&filter=&paged=1&pos=0"
	);
	$result = $asana->createTask($task_detail);
	
	// a task is created, 201 response code is sent back so...
	if($asana->responseCode == "201" && !is_null($result))
	{
		$resultJson = json_decode($result);
		$taskId = $resultJson->data->id; // Here we have the id of the task that have been created
		
		error_log("the json result:");
		error_log(print_r($resultJson->data,true));
		error_log($taskId);
		error_log($projectId);
		
		// Now we do another request to add the task to the project
		$result = $asana->addProjectToTask($taskId, $projectId);
		
		if($asana->responseCode != "200")
		{
			echo "Error while assigning project to task: {$asana->responseCode}";
			error_log("Error while assigning project to task: {$asana->responseCode}");
		}
	}
	else
	{
		echo "Error while trying to connect to Asana, response code: {$asana->responseCode}";
		error_log("Error while trying to connect to Asana, response code: {$asana->responseCode}");
	}
        
}