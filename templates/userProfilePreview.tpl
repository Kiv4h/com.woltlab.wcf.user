<div class="box128 userProfilePreview">
	<a href="{link controller='User' object=$user}{/link}" title="{$user->username}">{@$user->getAvatar()->getImageTag(128)}</a>
	
	<script type="text/javascript">
		//<![CDATA[
			$(function() {
				WCF.Icon.addObject({
					'wcf.icon.add': '{icon}add{/icon}',
					'wcf.icon.enabled': '{icon}enabled{/icon}',
					'wcf.icon.disabled': '{icon}disabled{/icon}',
					'wcf.icon.remove': '{icon}remove{/icon}'
				})
				
				WCF.Language.addObject({
					'wcf.user.button.follow': '{lang}wcf.user.button.follow{/lang}',
					'wcf.user.button.ignore': '{lang}wcf.user.button.ignore{/lang}',
					'wcf.user.button.unfollow': '{lang}wcf.user.button.unfollow{/lang}',
					'wcf.user.button.unignore': '{lang}wcf.user.button.unignore{/lang}'
				})
				
				new WCF.User.Action.Follow($('.userInformation'));
				new WCF.User.Action.Ignore($('.userInformation'));
			});
		//]]>
	</script>
	
	<div class="userInformation">
		{include file='userInformation'}
		
		{*TODO: show additional fields*}
		{hascontent}
			<dl class="plain dataList">
				{content}
					{if $user->occupation}
						<dt>{lang}wcf.user.option.occupation{/lang}</dt>
						<dd>{$user->occupation}</dd>
					{/if}
					{if $user->hobbies}
						<dt>{lang}wcf.user.option.hobbies{/lang}</dt>
						<dd>{$user->hobbies}</dd>
					{/if}
				{/content}
			</dl>
		{/hascontent}
	</div>
</div>