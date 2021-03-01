# hacknplan-discord-integration
A web service in PHP that *translates* HackNPlan webhook messages to Discord webhook messages.

It includes a "translate_webhook_to_discord.php" file, that handles all currently supported *translations* from a service to the other, 
and a "secrets.php" file (it's where you should put your HackNPlan API Key and your Discord webhook link).

## Basic setup:
* Create a API Key in HackNPlan and paste it in the appropriate field ($hnp_api_key) in your secrets.php file;
* Create a Discord webhook in the channel you want it to be and paste its link in the appropriate field ($discord_webhook_link) in your secrets.php file;
* Host both .php files at your desired web server, and copy the link to the translate_webhook_to_discord.php page;
* Create a webhook in HackNPlan, enable all events you want it to react to, and set its URL to the link you just copied;
* Tada! Everything should be working now. If not, feel free to open an issue here.

## Supported events
For now, the web service can *translate* the following events:

* board.created
* board.updated
* board.deleted
* board.closed
* board.reopened
* workitem.created
* workitem.updated
* workitem.deleted
* workitem.blocked
* workitem.unblocked
* workitem.user.assigned
* workitem.user.unassigned
* workitem.comment.created
* workitem.comment.updated
* workitem.comment.deleted
* workitem.subtask.created
* workitem.subtask.updated
* workitem.subtask.deleted
* workitem.subtask.closed
* workitem.subtask.reopened
* workitem.worklog.created
* workitem.worklog.updated
* workitem.worklog.deleted
* workitem.dependency.added
* workitem.dependency.removed
* workitem.attachment.added
* workitem.attachment.removed

However, it will also show an informative message if it doesn't support one of the events enabled in the webhook.
If you wish me to add *translations* to other events, let me know and I'll do it as soon as possible.
