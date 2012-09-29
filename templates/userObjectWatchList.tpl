{include file='documentHeader'}

<head>
	<title>{lang}wcf.user.watchedObjects{/lang}</title>
	{include file='headInclude'}
</head>

<body id="tpl{$templateName|ucfirst}">

{capture assign='sidebar'}
	<div>
		<form id="sidebarContainer" method="get" action="{link controller='UserObjectWatchList'}{/link}">
			<fieldset>
				{*@todo: language variables*}
				<legend>{lang}wbb.board.displayOptions{/lang}</legend>
				
				<dl>
					<dt><label for="sortField">{lang}wbb.board.sortBy{/lang}</label></dt>
					<dd>
						<select id="sortField" name="sortField">
							<option value="title"{if $sortField == 'title'} selected="selected"{/if}>{lang}wcf.user.watchedObjects.title{/lang}</option>
							<option value="username"{if $sortField == 'username'} selected="selected"{/if}>{lang}wcf.user.watchedObjects.username{/lang}</option>
							<option value="time"{if $sortField == 'time'} selected="selected"{/if}>{lang}wcf.user.watchedObjects.time{/lang}</option>
							<option value="lastChangeTime"{if $sortField == 'lastChangeTime'} selected="selected"{/if}>{lang}wcf.user.watchedObjects.lastChangeTime{/lang}</option>
						</select>
						<select name="sortOrder">
							<option value="ASC"{if $sortOrder == 'ASC'} selected="selected"{/if}>{lang}wcf.global.sortOrder.ascending{/lang}</option>
							<option value="DESC"{if $sortOrder == 'DESC'} selected="selected"{/if}>{lang}wcf.global.sortOrder.descending{/lang}</option>
						</select>
					</dd>
				</dl>
			</fieldset>
		
			<div class="formSubmit">
				<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
				<input type="hidden" name="pageNo" value="{@$pageNo}" />
			</div>
		</form>
	</div>
{/capture}

{include file='header' sidebarOrientation='right'}

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.user.watchedObjects{/lang} <span class="badge">{#$items}</span></h1>
	</hgroup>
</header>

{include file='userNotice'}

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller='UserObjectWatchList' link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
</div>

{hascontent}
	<div class="container marginTop shadow">
		<ul class="containerList">
			{content}
				{foreach from=$objects->getObjects() item=watchedObject}
					<li>
						{include file=$watchedObject->getTemplateName()}
					</li>
				{/foreach}
			{/content}
		</ul>
	</div>
{hascontentelse}
	<p class="info">{lang}wcf.user.watchedObjects.noObjects{/lang}</p>
{/hascontent}

<div class="contentNavigation">
	{@$pagesLinks}
</div>

{include file='footer'}

</body>
</html>
