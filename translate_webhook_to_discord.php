<?php

include 'secrets.php';

header("Content-type: application/json; charset=utf-8");

$headers = getallheaders();

$event_type = $headers["X-HacknPlan-Event"];

$json = file_get_contents('php://input');

$data = json_decode($json);


switch($event_type) {
	case "board.created":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

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
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

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
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		$users = $data->AssignedUsers;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.end($users)->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = $user_name." assigned to item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.user.unassigned":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "User removed from item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.comment.created":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.$data->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = $user_name." added a new comment on item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Text;

		break;

	case "workitem.comment.updated":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.$data->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = $user_name." updated comment #".$data->CommentId." on item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Text;

		break;

	case "workitem.comment.deleted":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Deleted comment #".$data->CommentId." on item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.subtask.created":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Created a new subtask on work item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Title;

		break;

	case "workitem.subtask.updated":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Updated subtask #".$data->SubTaskId." on work item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Title;

		break;

	case "workitem.subtask.deleted":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Deleted subtask #".$data->SubTaskId." on work item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.subtask.closed":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Closed subtask #".$data->SubTaskId." on work item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Title;

		break;

	case "workitem.subtask.reopened":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Reopened subtask #".$data->SubTaskId." on work item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Title;

		break;

	case "workitem.worklog.created":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.$data->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = $user_name." added a work log on item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Value." hours";

		break;

	case "workitem.worklog.updated":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Updated work log #".$data->WorkLogId." on item #".$data->WorkItemId." - ".$work_item_name.": ".PHP_EOL.$data->Value." hours";

		break;

	case "workitem.worklog.deleted":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

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
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/users/'.$data->User->Id);

		$user_name = json_decode(curl_exec($hnp_curl))->user->name;

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = $user_name." added a new attachment on work item #".$data->WorkItemId." - ".$work_item_name;

		break;

	case "workitem.attachment.removed":
		$hnp_curl = curl_init();

		curl_setopt($hnp_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($hnp_curl, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Authorization: ApiKey '.$hnp_api_key));

		curl_setopt($hnp_curl, CURLOPT_URL, 'https://api.hacknplan.com/v0/projects/'.$data->ProjectId.'/workitems/'.$data->WorkItemId);

		$work_item_name = json_decode(curl_exec($hnp_curl))->title;

		curl_close($hnp_curl);

		$message = "Removed attachment #".$data->AttachmentId." on work item #".$data->WorkItemId." - ".$work_item_name;

		break;

	default:
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