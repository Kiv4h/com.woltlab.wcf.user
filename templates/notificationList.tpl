{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.notificationList.title{/lang}</title>
	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			new WCF.Notification.List();
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='userMenuSidebar' sandbox=false}

{include file='header' sandbox=false sidebarOrientation='left'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.notificationList.title{/lang} <span class="badge jsNotificationsBadge">{#$notifications[count]}</span></h1>
	</hgroup>
</header>

{*TODO: pagination?*}
{hascontent}
	<div class="container marginTop shadow">
		<ul class="containerList">
			{content}
				{foreach from=$notifications[notifications] item=$notification}
					<li class="jsNotificationItem" data-notification-id="{@$notification[notificationID]}">
						<div class="box48">
							<a href="{link controller='User' object=$notification[author]}{/link}" title="{$notification[author]->username}" class="framed jsTooltip">{@$notification[author]->getAvatar()->getImageTag(48)}</a>
							<hgroup>
								<h1><a href="{link controller='User' object=$notification[author]}{/link}">{$notification[author]->username}</a><small> - {@$notification[time]|time}</small></h1>
							</hgroup>
							
							<p>{@$notification[message]}</p>
							
							<ul class="jsNotificationAction" data-notification-id="{@$notification[notificationID]}">
								{foreach from=$notification[buttons] item=button}
									<li class="button" data-action-name="{$button[actionName]}" data-class-name="{$button[className]}" data-object-id="{@$button[objectID]}">{$button[label]}</li>
								{/foreach}
							</ul>
						</div>
					</li>
				{/foreach}
			{/content}
		</ul>
	</div>
{hascontentelse}
	<!-- TODO: What should we display here? -->
	<p class="info">There are no notifications for you yet</p>
{/hascontent}

{include file='footer' sandbox=false}

</body>
</html>
