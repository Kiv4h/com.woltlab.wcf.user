{foreach from=$eventList item=event}
	<li>
		<div class="box48">
			<a href="{link controller='User' object=$event->getUserProfile()}{/link}" title="{$event->getUserProfile()->username}" class="framed">{@$event->getUserProfile()->getAvatar()->getImageTag(48)}</a>
			
			<div>
				<hgroup class="containerHeadline">
					<h1><a href="{link controller='User' object=$event->getUserProfile()}{/link}" class="userLink" data-user-id="{@$event->getUserProfile()->userID}">{$event->getUserProfile()->username}</a><small> - {@$event->time|time}</small></h1> 
					<h2><strong>{@$event->getTitle()}</strong></h2>
					<h3 class="containerContentType"><small>{lang}wcf.user.recentActivity.{@$event->getObjectTypeName()}{/lang}</small></h3>
				</hgroup>
				
				<div>{@$event->getDescription()}</div>
			</div>
		</div>
	</li>
{/foreach}