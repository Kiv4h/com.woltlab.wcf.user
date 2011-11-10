{include file="documentHeader"}

<head>
	<title>{lang}wcf.user.register.activation{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
</head>

<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>

{include file='header' sandbox=false}

{* TODO: Re-enable icons once a style is available *}

<header class="mainHeading">
	{*<img src="{icon}register1.svg{/icon}" alt="" />*}
	<hgroup>
		<h1>{lang}wcf.user.register.activation{/lang}</h1>
	</hgroup>
</header>

{if $userMessages|isset}{@$userMessages}{/if}
	
{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}
	
<form method="post" action="index.php?form=RegisterActivation">
	<div class="border content">
		<div class="container-1">
			<dl{if $errorField == 'u'} class="formError"{/if}>
				<dt><label for="userID">{lang}wcf.user.register.activation.userID{/lang}</label></dt>
				<dd>
					<input type="text" id="userID" name="u" value="{@$u}" class="medium" />
					{if $errorField == 'u'}
						<small class="innerError">
							{if $errorType == 'notValid'}{lang}wcf.user.register.activation.error.userID.notValid{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
	
			<dl{if $errorField == 'a'} class="formError"{/if}>
				<dt><label for="activationCode">{lang}wcf.user.register.activation.code{/lang}</label></dt>
				<dd>
					<input type="text" id="activationCode" maxlength="9" name="a" value="{@$a}" class="long" />
					{if $errorField == 'a'}
						<small class="innerError">
							{if $errorType == 'notValid'}{lang}wcf.user.register.activation.error.code.notValid{/lang}{/if}
						</small>
					{/if}
				</dd>
			</dl>
				
			{if $additionalFields|isset}{@$additionalFields}{/if}
				
			<div class="formElement">
				<div class="formField">
					<ul class="formOptionsLong">
						<li>{*<img src="{icon}register.svg{/icon}" alt="" />*} <a href="index.php?page=Register&amp;action=newActivationCode{@SID_ARG_2ND}">{lang}wcf.user.register.newActivationCode{/lang}</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>	
	
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		{@SID_INPUT_TAG}
		<input type="hidden" name="action" value="enable" />
	</div>
</form>

{include file='footer' sandbox=false}
</body>
</html>
