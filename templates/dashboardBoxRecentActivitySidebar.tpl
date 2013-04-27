<ul class="sidebarBoxList">
	{foreach from=$eventList item=event}
		<li class="box24">
			<a href="{link controller='User' object=$event->getUserProfile()}{/link}" title="{$event->getUserProfile()->username}" class="framed">{@$event->getUserProfile()->getAvatar()->getImageTag(24)}</a>
			
			<div class="sidebarBoxHeadline">
				<h3><a href="{link controller='User' object=$event->getUserProfile()}{/link}" class="userLink" data-user-id="{@$event->getUserProfile()->userID}">{$event->getUserProfile()->username}</a><small> - {@$event->time|time}</small></h3> 
				<small>{@$event->getTitle()}</small>
			</div>
		</li>
	{/foreach}
</ul>
