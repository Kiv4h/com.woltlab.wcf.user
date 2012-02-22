<ol id="recentActivity" class="wcf-recentActivity">
	{foreach from=$eventList item=event}
		<li>
			<a href="{link controller='User' object=$event->getUserProfile()}{/link}" title="{$event->getUserProfile()->username}" class="userAvatar">{@$event->userProfile()->getAvatar()->getImageTag(48)}</a>
			
			<div class="wcf-recentActivityContent">
				<p class="username"><a href="{link controller='User' object=$event->getUserProfile()}{/link}">{$event->getUserProfile()->username}</a> - {@$event->time|time}</p>
				
				{if $event->getIcon()}
					<p class="userActivityIcon"><img src="{@$event->getIcon()}" alt="" /></p>
				{/if}
				
				<p class="userActivityShort">{@$event->getShortOutput()}</p>
				
				<p class="userActivity">{@$event->getOutput()}</p>
			<div>
		</li>
	{/foreach}
</ol>