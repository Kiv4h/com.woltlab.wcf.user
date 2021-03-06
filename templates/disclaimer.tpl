{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.register.disclaimer{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='header'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.register.disclaimer{/lang}</h1>
	</hgroup>
</header>

{include file='userNotice'}

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<div class="contentNavigation">
	{hascontent}
		<nav>
			<ul>
				{content}
					{event name='contentNavigationButtons'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</div>

<form method="post" action="{link controller='Disclaimer'}{/link}">
	<div class="container containerPadding marginTop htmlContent">
		{lang}wcf.user.register.disclaimer.text{/lang}
		
		{event name='fieldsets'}
	</div>
	
	<div class="formSubmit">
		<input type="submit" name="accept" value="{lang}wcf.user.register.disclaimer.accept{/lang}" accesskey="s" />
		<a class="button" href="{link controller='Index'}{/link}">{lang}wcf.user.register.disclaimer.decline{/lang}</a>
	</div>
</form>

{include file='footer'}

</body>
</html>
