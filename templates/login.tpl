{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.login{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">
{include file='header' sandbox=false __disableLoginLink=true}

<header class="mainHeading">
	<img src="{icon size='L'}logIn1{/icon}" alt="" />
	<hgroup>
		<h1>{lang}wcf.user.login{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="{link controller='Login'}{/link}" id="loginForm">
	<div class="border content">
		<fieldset>
			<legend>{lang}wcf.user.login.data{/lang}</legend>
	
			<dl>
				<dt><label for="username">{lang}wcf.user.usernameOrEmail{/lang}</label></dt>
				<dd>
					<input type="text" id="username" name="username" value="{$username}" required="required" class="medium" />
					{if $errorField == 'username'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'notFound'}{lang}wcf.user.error.username.notFound{/lang}{/if}
							{if $errorType == 'notEnabled'}{lang}wcf.user.login.error.username.notEnabled{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			<dl>
				<dt>{lang}wcf.user.login.action{/lang}</dt>
				<dd><label><input type="radio" name="action" value="register" /> {lang}wcf.user.login.action.register{/lang}</label></dd>
				<dd><label><input type="radio" name="action" value="login" checked="checked" /> {lang}wcf.user.login.action.login{/lang}</label></dd>
			</dl>
			
			<dl>
				<dt><label for="password">{lang}wcf.user.password{/lang}</label></dt>
				<dd>
					<input type="password" id="password" name="password" value="{$password}" class="medium" />
					{if $errorField == 'password'}
						<small class="innerError">
							{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							{if $errorType == 'false'}{lang}wcf.user.login.error.password.false{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			{if $supportsPersistentLogins}
				<dl>
					<dt class="reversed"><label for="useCookies">{lang}wcf.user.useCookies{/lang}</label></dt>
					<dd><input type="checkbox" id="useCookies" name="useCookies" value="1" {if $useCookies}checked="checked" {/if}/></dd>
				</dl>
			{/if}
		</fieldset>
	</div>
	
	<div class="formSubmit">
		<input type="reset" value="{lang}wcf.global.button.reset{/lang}" accesskey="r" />
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SID_INPUT_TAG}
 	</div>
</form>

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$('#loginForm input[name=action]').change(function(event) {
			if ($(event.target).val() == 'register') {
				$('#password').disable();
				$('#password').parents('dl').addClass('disabled');
				$('#useCookies').disable();
				$('#useCookies').parents('dl').addClass('disabled');
			}
			else {
				$('#password').enable();
				$('#password').parents('dl').removeClass('disabled');
				$('#useCookies').enable();
				$('#useCookies').parents('dl').removeClass('disabled');
			}
		});
	});
	//]]>
</script>

{include file='footer' sandbox=false}

</body>
</html>