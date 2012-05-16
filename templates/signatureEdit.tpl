{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.signature.title{/lang} - {PAGE_TITLE|language}</title>
	
	{include file='headInclude'}
	
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.Language.addObject({
				'wcf.user.signature.preview': '{lang}wcf.user.signature.preview{/lang}'
			});
			
			new WCF.User.SignaturePreview('wcf\\data\\user\\UserProfileAction', 'text', 'previewButton');
		});
		//]]>
	</script>
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='userMenuSidebar'}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.signature.title{/lang}</h1>
	</hgroup>
</header>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="{link controller='SignatureEdit'}{/link}">
	<div class="container containerPadding marginTop shadow">
		{if $signatureCache}
			<fieldset>
				<legend>{lang}wcf.user.signature.current{/lang}</legend>
				
				{@$signatureCache}
			</fieldset>
		{/if}
		
		<fieldset id="signatureContainer">
			<legend>{lang}wcf.user.signature.text{/lang}</legend>
				
			<dl class="wide{if $errorField == 'text'} formError{/if}">
				<dt><label for="text">{lang}wcf.user.signature.text{/lang}</label></dt>
				<dd>
					<textarea id="text" name="text" rows="20" cols="40">{$text}</textarea>
					{if $errorField == 'text'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{elseif $errorType == 'tooLong'}
								{lang}wcf.message.error.tooLong{/lang}
							{else}
								{lang}wbb.post.message.error.{@$errorType}{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>
		</fieldset>
		
		{include file='messageFormTabs'}
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		<button id="previewButton" accesskey="p">{lang}wcf.global.button.preview{/lang}</button>
		<input type="hidden" name="tmpHash" value="{$tmpHash}" />
 	</div>
</form>

{include file='footer'}
{include file='wysiwyg'}

</body>
</html>