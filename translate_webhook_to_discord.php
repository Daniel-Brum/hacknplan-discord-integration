<?php
include 'secrets.php';

header("Content-type: application/json; charset=utf-8");

if (!function_exists('getallheaders'))
{
	function getallheaders()
	{
		$headers = [];
		foreach ($_SERVER as $name => $value)
		{
			if (substr($name, 0, 5) == 'HTTP_')
			{
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}    
		return $headers;
	}
}

$headers = getallheaders();

foreach ($headers as $name => $value)
{
	if(strtolower($name) == 'x-hacknplan-event')
	{
		$event_type = $headers[$name];
	}
}

if($event_type == NULL)
{
	echo "Event not defined";
	die;
}

$json = file_get_contents('php://input');

$data = json_decode($json);

$hnp_curl = curl_init();
curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

switch($event_type) {
	case "project.updated":
		$message = "Updated project ".$data->Name;

		break;
	case "project.closed":
		$message = "Closed project ".$data->Name;

		break;
	case "project.reopened":
		$message = "Reopened project ".$data->Name;

		break;
	case "category.created":
		$message = "Category created ".$data->Name;

		break;
	case "category.updated":
		$message = "Category updated ".$data->Name;

		break;
	case "category.deleted":
		$message = "Category updated ".$data->Name;

		break;
	case "designelementtype.created":
		$message = "Design Element Type Created ".$data->Name;

		break;
	case "designelementtype.updated":
		$message = "Design Element Type Updated ".$data->Name;

		break;
	case "designelementtype.deleted":
		$message = "Design Element Type Deleted ".$data->Name;

		break;
	case "importancelevel.created":
		$message = "Project Importance Level Created ".$data->Name;

		break;
	case "importancelevel.updated":
		$message = "Project Importance Level Updated ".$data->Name;

		break;
	case "importancelevel.deleted":
		$message = "Project Importance Level Deleted ".$data->Name;

		break;
	case "role.created":
		$message = "Project Roles Created ".$data->Name;

		break;
	case "role.updated":
		$message = "Project Roles Updated ".$data->Name;

		break;
	case "role.deleted":
		$message = "Project Roles Deleted ".$data->Name;

		break;
	case "stage.created":
		$message = "Project Stage Created ".$data->Name;

		break;
	case "stage.updated":
		$message = "Project Stage Updated ".$data->Name;

		break;
	case "stage.deleted":
		$message = "Project Stage Deleted ".$data->Name;

		break;
	case "tag.created":
		$message = "Project Tag Created ".$data->Name;

		break;
	case "tag.updated":
		$message = "Project Tag Updated ".$data->Name;

		break;
	case "tag.deleted":
		$message = "Project Tag Deleted ".$data->Name;

		break;
	case "projectuser.added":
		$message = "Project User Added ".$data->User->Name." (".$data->User->Username.")";

		break;
	case "projectuser.updated":
		$message = "Project User Updated ".$data->User->Name." (".$data->User->Username.")";

		break;
	case "projectuser.deleted":
		$message = "Project User Deleted ".$data->User->Name." (".$data->User->Username.")";

		break;
	case "projectuser.left":
		$message = "Project User Left ".$data->User->Name." (".$data->User->Username.")";

		break;
	case "milestone.created":
		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/milsestones/'.$data->Creator->Id);
		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_close($hnp_curl);

		$message = $user_name." created a new milestone: ".$data->Name;

		break;
	case "milestone.updated":
		$message = "Milestone Updated ".$data->Name;

		break;
	case "milestone.deleted":
		$message = "Milestone Deleted ".$data->Name;

		break;
	case "milestone.closed":
		$message = "Milestone Closed ".$data->Name;

		break;
	case "milestone.reopened":
		$message = "Milestone Reopened ".$data->Name;

		break;
	case "designelement.created":
		$message = "Design Element Created ".$data->Name;

		break;
	case "designelement.updated":
		$message = "Design Element Updated ".$data->Name;

		break;
	case "designelement.deleted":
		$message = "Design Element Deleted ".$data->Name;

		break;
	case "designelement.comment.created":
		$message = "Design Element Comment Created ".$data->Name;

		break;
	case "designelement.comment.updated":
		$message = "Design Element Comment Updated ".$data->Name;

		break;
	case "designelement.comment.deleted":
		$message = "Design Element Comment Deleted ".$data->Name;

		break;
	case "designelement.attachment.added":
		$message = "Design Element Attachment Added ".$data->Name;

		break;
	case "designelement.attachment.removed":
		$message = "Design Element Attachment Removed ".$data->Name;

		break;
	case "board.created":	

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.$data->Creator->Id);
		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_close($hnp_curl);

		$message = $user_name." created a new board: ".$data->Name;

		break;

	case "board.updated":
		$message = "Updated board ".$data->Name;

		break;

	case "board.deleted":
		$message = "Deleted board ".$data->Name;

		break;

	case "board.closed":
		$message = "Closed board ".$data->Name;
		
		break;

	case "board.reopened":
		$message = "Reopened board ".$data->Name;

		break;

	case "workitem.created":
			
		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.$data->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_close($hnp_curl);

		$message = $user_name." created a new work item: #".$data->WorkItemId." - ".$data->Title;

		break;

	case "workitem.updated":
		$message = "Updated work item #".$data->WorkItemId." - ".$data->Title;

		break;

	case "workitem.deleted":
		$message = "Deleted work item #".$data->WorkItemId." - ".$data->Title;

		break;

	case "workitem.blocked":
		$message = "Blocked work item #".$data->WorkItemId." - ".$data->Title;

		break;

	case "workitem.unblocked":
		$message = "Unblocked work item #".$data->WorkItemId." - ".$data->Title;

		break;

	case "workitem.user.assigned":
		
		$users = $data->AssignedUsers;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.end($users)->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = $user_name." assigned to item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.user.unassigned":
	
		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "User removed from item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.comment.created":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.$data->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = $user_name." added a new comment on item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Text;

		break;

	case "workitem.comment.updated":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.$data->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = $user_name." updated comment #".$data->CommentId." on item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Text;

		break;

	case "workitem.comment.deleted":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Deleted comment #".$data->CommentId." on item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.subtask.created":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Created a new subtask on work item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Title;

		break;

	case "workitem.subtask.updated":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Updated subtask #".$data->SubTaskId." on work item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Title;

		break;

	case "workitem.subtask.deleted":		

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Deleted subtask #".$data->SubTaskId." on work item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.subtask.closed":		

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Closed subtask #".$data->SubTaskId." on work item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Title;

		break;

	case "workitem.subtask.reopened":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Reopened subtask #".$data->SubTaskId." on work item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Title;

		break;

	case "workitem.worklog.created":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.$data->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = $user_name." added a work log on item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Value." hours";

		break;

	case "workitem.worklog.updated":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Updated work log #".$data->WorkLogId." on item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Value." hours";

		break;

	case "workitem.worklog.deleted":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Deleted work log #".$data->WorkLogId." on item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.dependency.added":
		$message = "Added a dependency to work item #".$data->WorkItem->WorkItemId." - ".$data->WorkItem->Title;

		break;

	case "workitem.dependency.removed":
		$message = "Removed dependency from work item #".$data->WorkItem->WorkItemId." - ".$data->WorkItem->Title;

		break;

	case "workitem.attachment.added":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.$data->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = $user_name." added a new attachment on work item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.attachment.removed":

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Removed attachment #".$data->AttachmentId." on work item #".$data->WorkItemId." - ".$work_item_name;

		break;

	default:
  echo "$event_type";
		$message = "The event ".$event_type." is not supported. Please disable it in your HackNPlan webhook.";

		break;
}


if(!isset($message[0])) {
	$message = "An error has occurred when sending a webhook for the event ".$event_type.PHP_EOL;
} else {
	echo $message;

	$message .= PHP_EOL;
}

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, $discord_webhook_link);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
	'content' => $message
]));

$output = json_decode(curl_exec($curl), true);

curl_close($curl);
