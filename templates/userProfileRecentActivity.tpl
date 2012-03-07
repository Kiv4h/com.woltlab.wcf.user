<ol id="recentActivity" class="wcf-recentActivityList">
	{foreach from=$eventList item=event}
		<li class="wcf-container">
			<a href="{link controller='User' object=$event->getUserProfile()}{/link}" title="{$event->getUserProfile()->username}" class="wcf-containerIcon wcf-userAvatarFramed">{@$event->getUserProfile()->getAvatar()->getImageTag(48)}</a>
			
			<div class="wcf-recentActivityContent wcf-containerContent">
				<p class="wcf-username"><a href="{link controller='User' object=$event->getUserProfile()}{/link}">{$event->getUserProfile()->username}</a> - {@$event->time|time}</p>
				
				<div class="wcf-container">
					{if $event->getIcon()}
						<span class="wcf-userActivityIcon wcf-containerIcon"><img src="{@$event->getIcon()}" alt="" /></span>
					{/if}
					<div class="wcf-containerContent">
						<h1 class="wcf-userActivityShort">{@$event->getTitle()}</h1>
						<p class="wcf-userActivity">{@$event->getDescription()}</p>
					</div>
				</div>
			</div>
		</li>
	{/foreach}
</ol>
