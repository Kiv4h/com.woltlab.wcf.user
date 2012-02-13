<ol id="recentActivity" class="wcf-recentActivity">
	{foreach from=$eventList item=event}
		<li>
			<a href="{link controller='User' object=$event->userProfile}{/link}" title="{$event->getUserProfile()->username}" class="userAvatar">{@$event->userProfile->getAvatar()->getImageTag(48)}</a>
			
			<div class="wcf-recentActivityContent">
				<p class="username"><a href="{link controller='User' id=$event->userID}{/link}">{$event->getUserProfile()->username}</a> - {@$event->time|time}</p>
				<p class="userActivity">{@$event->text}</p>
			<div>
		</li>
	{/foreach}
</ol>