<?php 
	// Replacement group_tools mail function

	gatekeeper();
	
	$group_guid = (int) get_input("group_guid", 0);
	$user_guids = get_input("user_guids");
	$subject = get_input("title");
	$body = get_input("description");
	
	$forward_url = REFERER;
	$user_guids = group_tools_verify_group_members($group_guid, $user_guids);
	
	if(!empty($group_guid) && !empty($body) && !empty($user_guids)){
		
		if(($group = get_entity($group_guid)) && ($group instanceof ElggGroup)){
			if($group->canEdit()){
				set_time_limit(0);
				
				$body .= "


" . elgg_echo("group_tools:mail:message:from") . ": <a href='" . $group->getURL() . "'>" . $group->name . "</a>"; 
				
				$group_owner = $group->getOwnerEntity();

				foreach($user_guids as $guid){
					$user = get_entity($guid);
					// Bypass the notify function and send email directly
					elgg_send_email($group_owner->email, $user->email, $subject, $body);
				}
				
				system_message(elgg_echo("group-extender:action:mail:success"));
				
				$forward_url = $group->getURL();
			} else {
				register_error(elgg_echo("group_tools:action:error:edit"));
			}
		} else {
			register_error(elgg_echo("group_tools:action:error:entity"));
		}
	} else {
		register_error(elgg_echo("group_tools:action:error:input"));
	}
	
	forward($forward_url);
