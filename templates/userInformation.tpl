<hgroup class="containerHeadline">
	<h1><a href="{link controller='User' object=$user}{/link}">{$user->username}</a>{if MODULE_USER_RANK && $user->getUserTitle()} <span class="badge userTitleBadge{if $user->getRank() && $user->getRank()->cssClassName} {@$user->getRank()->cssClassName}{/if}">{$user->getUserTitle()}</span>{/if}</h1> 
	<h2><ul class="dataList">
		{if $user->gender}<li>{lang}wcf.user.gender.{if $user->gender == 1}male{else}female{/if}{/lang}</li>{/if}
		{if $user->getAge()}<li>{@$user->getAge()}</li>{/if}
		{if $user->location}<li>{lang}wcf.user.membersList.location{/lang}</li>{/if}
		<li>{lang}wcf.user.membersList.registrationDate{/lang}</li>
	</ul></h2>
</hgroup>


<ul class="buttonList">
	{if $user->homepage}
		<li><a class="jsTooltip" href="{@$user->homepage}" title="{lang}wcf.user.option.homepage{/lang}"><img src="{icon}home{/icon}" alt="" /></a></li>
	{/if}
	{if $__wcf->user->userID && $user->userID != $__wcf->user->userID}
		{if $__wcf->getUserProfileHandler()->isFollowing($user->userID)}
			<li><a data-following="1" data-object-id="{@$user->userID}" class="jsFollowButton jsTooltip" title="{lang}wcf.user.button.unfollow{/lang}"><img src="{icon size='S'}remove{/icon}" alt="" class="icon16" /></a></li>
		{else}
			<li><a data-following="0" data-object-id="{@$user->userID}" class="jsFollowButton jsTooltip" title="{lang}wcf.user.button.follow{/lang}"><img src="{icon size='S'}add{/icon}" alt="" class="icon16" /></a></li>
		{/if}
		
		{if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}
			<li><a data-ignored="1" data-object-id="{@$user->userID}" class="jsIgnoreButton jsTooltip" title="{lang}wcf.user.button.unignore{/lang}"><img src="{icon size='S'}enabled{/icon}" alt="" class="icon16" /></a></li>
		{else}
			<li><a data-ignored="0" data-object-id="{@$user->userID}" class="jsIgnoreButton jsTooltip" title="{lang}wcf.user.button.ignore{/lang}"><img src="{icon size='S'}disabled{/icon}" alt="" class="icon16" /></a></li>
		{/if}
	{/if}
	
	{event name='buttons'}
</ul>

<dl class="inlineDataList">
	{event name='statistics'}
</dl>