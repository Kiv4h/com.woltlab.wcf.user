{if $__wcf->user->userID}
	<!-- user menu -->
	<li id="userMenu" class="dropdown">
		<a class="dropdownToggle framed" data-toggle="userMenu">{if $__wcf->getUserProfileHandler()->getAvatar()}{@$__wcf->getUserProfileHandler()->getAvatar()->getImageTag(24)}{/if} {lang}wcf.user.userNote{/lang}</a>
		<ul class="dropdownMenu">
			<li><a href="{link controller='User' object=$__wcf->user}{/link}" class="box32">
				<div class="framed">{@$__wcf->getUserProfileHandler()->getAvatar()->getImageTag(32)}</div>
				
				<hgroup class="containerHeadline">
					<h1>{$__wcf->user->username}</h1>
					<h2>{lang}wcf.user.myProfile{/lang}</h2>
				</hgroup>
			</a></li>
			<li><a href="{link controller='User' object=$__wcf->user}editOnInit=true#about{/link}">{lang}wcf.user.editProfile{/lang}</a></li>
			<li><a href="{link controller='Settings'}{/link}">{lang}wcf.user.menu.settings{/lang}</a></li>
			{if $__wcf->session->getPermission('admin.general.canUseAcp')}
				<li class="dropdownDivider"></li>
				<li><a href="acp/index.php">ACP</a></li>
			{/if}
			<li class="dropdownDivider"></li>
			<li><a href="{link controller='Logout'}t={@SECURITY_TOKEN}{/link}" onclick="WCF.System.Confirmation.show('{lang}wcf.user.logout.sure{/lang}', $.proxy(function (action) { if (action == 'confirm') window.location.href = $(this).attr('href'); }, this)); return false;">{lang}wcf.user.logout{/lang}</a></li>
		</ul>
	</li>
	
	<!-- user notifications -->
	{if $__wcf->getUserNotificationHandler()->getNotificationCount()}
		<li id="userNotifications" class="dropdown" data-count="{@$__wcf->getUserNotificationHandler()->getNotificationCount()}" data-link="{link controller='NotificationList'}{/link}">
			<a class="dropdownToggle jsTooltip" data-toggle="userNotifications" title="{lang}wcf.user.notification.notifications{/lang}"><img src="{icon size='M'}flashInverse{/icon}" alt="" class="icon24" /> <span class="invisible">{lang}wcf.user.notification.notifications{/lang}</span>{if $__wcf->getUserNotificationHandler()->getNotificationCount() > 0} <span class="badge badgeInverse">{#$__wcf->getUserNotificationHandler()->getNotificationCount()}</span>{/if}</a>
			<div class="dropdownMenu userNotificationContainer">
				<div id="userNotificationContainer" class="scrollableContainer">
					<div class="scrollableItems cleafix">
						<div>
							<p>{lang}wcf.global.loading{/lang}</p>
						</div>
						<div>
							<p>{lang}wcf.global.loading{/lang}</p>
						</div>
					</div>
				</div>
			</div>
		</li>
	{else}
		<li>
			<a class="jsTooltip" href="{link controller='NotificationList'}{/link}" title="{lang}wcf.user.notification.notifications{/lang}"><img src="{icon size='M'}flashInverse{/icon}" alt="" class="icon24" /> <span class="invisible">{lang}wcf.user.notification.notifications{/lang}</span></a>
		</li>
	{/if}
	
	<!-- testing: -->
	<li>
		<a class="jsTooltip" href="#" title="Watched Objects"><img src="{icon size='M'}bookmarkInverse{/icon}" alt="" class="icon24" /> <span class="invisible">Watched Objects</span> <span class="badge badgeInverse">43</span></a>
	</li>
{else}
	{if !$__disableLoginLink|isset}
		<!-- login box -->
		<li>
			<a id="loginLink" href="{link controller='Login'}{/link}">{lang}wcf.user.loginOrRegister{/lang}</a>
			<div id="loginForm" style="display: none;">
				<form method="post" action="{link controller='Login'}{/link}">
					<fieldset>
						<dl>
							<dt><label for="username">{lang}wcf.user.usernameOrEmail{/lang}</label></dt>
							<dd>
								<input type="text" id="username" name="username" value="" required="required" autofocus="autofocus" class="long" />
							</dd>
						</dl>
						
						<dl>
							<dt>{lang}wcf.user.login.action{/lang}</dt>
							<dd>
								<label><input type="radio" name="action" value="register" /> {lang}wcf.user.login.action.register{/lang}</label>
								<label><input type="radio" name="action" value="login" checked="checked" /> {lang}wcf.user.login.action.login{/lang}</label>
							</dd>
						</dl>
						
						<dl>
							<dt><label for="password">{lang}wcf.user.password{/lang}</label></dt>
							<dd>
								<input type="password" id="password" name="password" value="" class="long" />
							</dd>
						</dl>
						
						<dl>
							<dd><label><input type="checkbox" id="useCookies" name="useCookies" value="1" checked="checked" /> {lang}wcf.user.useCookies{/lang}</label></dd>
						</dl>
						
						{event name='additionalLoginFields'}
						
						<div class="formSubmit">
							<input type="submit" id="loginSubmitButton" name="submitButton" value="{lang}wcf.user.button.login{/lang}" accesskey="s" />
							<input type="hidden" name="url" value="{$__wcf->session->requestURI}" />
						</div>
					</fieldset>
				</form>
			</div>
			
			<script type="text/javascript">
				//<![CDATA[
				$(function() {
					WCF.Language.addObject({
						'wcf.user.button.login': '{lang}wcf.user.button.login{/lang}',
						'wcf.user.button.register': '{lang}wcf.user.button.register{/lang}',
						'wcf.user.login': '{lang}wcf.user.login{/lang}'
					});
					new WCF.User.Login(true);
				});
				//]]>
			</script>
		</li>
	{/if}
	<!-- language switcher -->
	<li id="languageIDContainer">
		<script type="text/javascript">
			//<![CDATA[
			$(function() {
				var $languages = {
					{implode from=$__wcf->getLanguage()->getLanguages() item=language}
						'{@$language->languageID}': {
							iconPath: '{@$language->getIconPath()}',
							languageName: '{$language}'
						}
					{/implode}
				};
				
				new WCF.Language.Chooser('languageIDContainer', 'languageID', {@$__wcf->getLanguage()->languageID}, $languages, function(item) {
					var $location = window.location.toString().replace(/#.*/, '').replace(/(\?|&)l=[0-9]+/g, '');
					var $delimiter = ($location.indexOf('?') == -1) ? '?' : '&';
					
					window.location = $location + $delimiter + 'l=' + item.data('languageID') + window.location.hash;
				});
			});
			//]]>
		</script>
	</li>
{/if}

{event name='menuItems'}
