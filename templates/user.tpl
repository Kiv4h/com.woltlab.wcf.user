{include file='documentHeader'}

<head>
	<title>User profile page</title>
	{include file='headInclude' sandbox=false}

	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/WCF.User.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			{if $__wcf->getUser()->userID != $user->userID}
				WCF.Language.addObject({
					'wcf.user.profile.followUser': 'follow',
					'wcf.user.profile.unfollowUser': 'unfollow',
					'wcf.user.profile.ignoreUser': 'ignore user',
					'wcf.user.profile.unignoreUser': 'unignore user'
				});

				new WCF.User.Profile.Follow({$user->userID}, {if $__wcf->getUserProfileHandler()->isFollowing($user->userID)}true{else}false{/if});
				new WCF.User.Profile.IgnoreUser({@$user->userID}, {if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}true{else}false{/if});
			{/if}

			new WCF.User.Profile.TabMenu({@$user->userID});

			WCF.TabMenu.init();

			{* TODO: Handle admin permissions *}
			{if $__wcf->getUser()->userID == $user->userID}
				WCF.User.Profile.Editor.Handler.init({$user->userID});
				new WCF.User.Profile.Editor.Overview({@$overviewObjectType->objectTypeID});
			{/if}
		});
		//]]>
	</script>
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{capture assign='sidebar'}

<nav id="sidebarContent" class="sidebarContent">
	{* user *}
	<div class="menuContainer">
		<h1 class="menuHeader">{$user->username}</h1>
		<div class="sidebarContentGroup">
			<ul>
				<li>
					{if $user->getAvatar()}
						<div class="userAvatarOriginal" title="{$user->username}">{@$user->getAvatar()}</div>
					{/if}
				</li>
			</ul>
		</div>
	</div>
	
	{* following *}
	{if $followingCount}
	<div class="menuContainer userFollowing collapsible">
		<h1>Following <span class="badge">{#$followingCount}</span></h1>
		<div class="sidebarContentGroup">
			<ul>
				{foreach from=$following item=followingUser}
					{assign var=__dummy value=$followingUser->getAvatar()->setMaxSize(32, 32)}
					<li class="userAvatar balloonTooltip" title="{$followingUser->username}"><a href="{link controller='User' object=$followingUser}{/link}">{@$followingUser->getAvatar()}</a></li>
				{/foreach}
			</ul>
			{if $followingCount > 1}
				<p><a id="followingAll" class="badge badgeButton javascriptOnly">Show all following</a></p>
			{/if}
		</div>
	</div>
	{/if}
	
	{* followers *}
	{if $followerCount}
	<div class="menuContainer userFollowers collapsible">
		<h1>Followers <span class="badge">{#$followerCount}</span></h1>
		<ul>
			{foreach from=$followers item=follower}
				{assign var=__dummy value=$follower->getAvatar()->setMaxSize(32, 32)}
				<li class="userAvatar balloonTooltip" title="{$follower->username}"><a href="{link controller='User' object=$follower}{/link}">{@$follower->getAvatar()}</a></li>
			{/foreach}
		</ul>
		{if $followerCount > 1}
			<p><a id="followerAll" class="badge badgeButton javascriptOnly">Show all followers</a></p>
		{/if}
	</div>
	{/if}
				
	{* profile visitors *}
	
	{* placeholder *}
	
	{* collapse sidebar *}			
	<span class="collapsibleSidebarButton" title="{lang}wcf.global.button.collapsible{/lang}"><span></span></span>
</nav>

{/capture}

{include file='header' sandbox=false sidebarOrientation='left'}

<div class="contentHeader">
	<!-- ToDo: Wouldn't it be better to generate a Large Button List out of all that here? -->
	<nav id="profileButtonContainer">
	</nav>
</div>
	
<section id="profileContent" class="tabMenuContainer" data-active="{$__wcf->getUserProfileMenu()->getActiveMenuItem()->getIdentifier()}">
	<nav class="tabMenu">
		<ul>
			{foreach from=$__wcf->getUserProfileMenu()->getMenuItems() item=menuItem}
				<li><a href="#{$menuItem->getIdentifier()}" title="{lang}{@$menuItem->menuItem}{/lang}">{lang}{@$menuItem->menuItem}{/lang}</a></li>
			{/foreach}
		</ul>
	</nav>

	{foreach from=$__wcf->getUserProfileMenu()->getMenuItems() item=menuItem}
		<div id="{$menuItem->getIdentifier()}" class="border tabMenuContent" data-menu-item="{$menuItem->menuItem}">
			{if $menuItem === $__wcf->getUserProfileMenu()->getActiveMenuItem()}
				{@$profileContent}
			{/if}
		</div>
	{/foreach}
</section>

<div class="contentFooter">
	<!-- ToDo -->
</div>

{include file='footer' sandbox=false}

</body>
</html>
