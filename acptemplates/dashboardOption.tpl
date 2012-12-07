{include file='header' pageTitle='wcf.acp.dashboard.option'}

<script type="text/javascript">
	//<![CDATA[
	$(function() {
		WCF.TabMenu.init();
	});
	//]]>
</script>

<header class="boxHeadline">
	<hgroup>
		<h1>{lang}wcf.acp.dashboard.option{/lang}</h1>
		<h2>{lang}wcf.dashboard.objectType.{$objectType->objectType}{/lang}</h2>
	</hgroup>
</header>

<p class="info">{lang}wcf.acp.dashboard.box.sort{/lang}</p>

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a href="{link controller='DashboardList'}{/link}" title="{lang}wcf.acp.menu.link.dashboard.list{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/list.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.menu.link.dashboard.list{/lang}</span></a></li>
			
			{event name='largeButtons'}
		</ul>
	</nav>
</div>

<div class="tabMenuContainer">
	<nav class="tabMenu">
		<ul>
			{if $objectType->allowcontent}
				<li><a href="#dashboard-content">{lang}wcf.dashboard.boxType.content{/lang}</a></li>
			{/if}
			{if $objectType->allowsidebar}
				<li><a href="#dashboard-sidebar">{lang}wcf.dashboard.boxType.sidebar{/lang}</a></li>
			{/if}
			
			{event name='tabMenuTabs'}
		</ul>
	</nav>
	
	{if $objectType->allowcontent}
		<div id="dashboard-content" class="container containerPadding tabMenuContent hidden">
			<fieldset>
				<legend>{lang}wcf.dashboard.box.enabledBoxes{/lang}</legend>
				
				<div class="container containerPadding sortableListContainer">
					<ol class="sortableList" data-object-id="0">
						{foreach from=$enabledBoxes item=boxID}
							{if $boxes[$boxID]->boxType == 'content'}
								<li class="sortableList" data-object-id="{@$boxID}">
									<span class="sortableNodeLabel">{lang}wcf.dashboard.box.{$boxes[$boxID]->boxName}{/lang}</span>
								</li>
							{/if}
						{/foreach}
					</ol>
				</div>
			</fieldset>
			
			<fieldset>
				<legend>{lang}wcf.dashboard.box.availableBoxes{/lang}</legend>
				
				<div class="container containerPadding sortableListContainer">
					<ol class="sortableList">
						{foreach from=$boxes item=box}
							{if $box->boxType == 'content' && !$box->boxID|in_array:$enabledBoxes}
								<li class="sortableList" data-object-id="{@$box->boxID}">
									<span class="sortableNodeLabel">{lang}wcf.dashboard.box.{$box->boxName}{/lang}</span>
								</li>
							{/if}
						{/foreach}
					</ol>
				</div>
			</fieldset>
			
			<div class="formSubmit">
				<button data-type="submit">{lang}wcf.global.button.saveSorting{/lang}</button>
			</div>
			
			<script type="text/javascript">
				//<![CDATA[
				$(function() {
					new WCF.Sortable.List('dashboard-content', 'wcf\\data\\dashboard\\box\\DashboardBoxAction', 0, { }, true, { boxType: 'content', objectTypeID: {@$objectTypeID} });
				});
				//]]>
			</script>
		</div>
	{/if}
	
	{if $objectType->allowsidebar}
		<div id="dashboard-sidebar" class="container containerPadding tabMenuContent hidden">
			<fieldset>
				<legend>{lang}wcf.dashboard.box.enabledBoxes{/lang}</legend>
				
				<div class="container containerPadding sortableListContainer">
					<ol class="sortableList simpleSortableList" data-object-id="0">
						{foreach from=$enabledBoxes item=boxID}
							{if $boxes[$boxID]->boxType == 'sidebar'}
								<li class="sortableNode" data-object-id="{@$boxID}">
									<span class="sortableNodeLabel">{lang}wcf.dashboard.box.{$boxes[$boxID]->boxName}{/lang}</span>
								</li>
							{/if}
						{/foreach}
					</ol>
				</div>
			</fieldset>
			
			<fieldset>
				<legend>{lang}wcf.dashboard.box.availableBoxes{/lang}</legend>
				
				<div id="dashboard-sidebar-enabled" class="container containerPadding sortableListContainer">
					<ol class="sortableList simpleSortableList">
						{foreach from=$boxes item=box}
							{if $box->boxType == 'sidebar' && !$box->boxID|in_array:$enabledBoxes}
								<li class="sortableNode" data-object-id="{@$box->boxID}">
									<span class="sortableNodeLabel">{lang}wcf.dashboard.box.{$box->boxName}{/lang}</span>
								</li>
							{/if}
						{/foreach}
					</ol>
				</div>
			</fieldset>
			
			<div class="formSubmit">
				<button data-type="submit">{lang}wcf.global.button.saveSorting{/lang}</button>
			</div>
			
			<script type="text/javascript">
				//<![CDATA[
				$(function() {
					new WCF.Sortable.List('dashboard-sidebar', 'wcf\\data\\dashboard\\box\\DashboardBoxAction', 0, { }, true, { boxType: 'sidebar', objectTypeID: {@$objectTypeID} });
				});
				//]]>
			</script>
		</div>
	{/if}
</div>

<div class="contentNavigation">
	<nav>
		<ul>
			<li><a href="{link controller='DashboardList'}{/link}" title="{lang}wcf.acp.menu.link.dashboard.list{/lang}" class="button"><img src="{@$__wcf->getPath()}icon/list.svg" alt="" class="icon24" /> <span>{lang}wcf.acp.menu.link.dashboard.list{/lang}</span></a></li>
			
			{event name='largeButtons'}
		</ul>
	</nav>
</div>

{include file='footer'}