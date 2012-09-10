<div class="containerPadding">
	{foreach from=$options item=category}
		{foreach from=$category[categories] item=optionCategory}
			<fieldset>
				<legend>{lang}wcf.user.option.category.{@$optionCategory[object]->categoryName}{/lang}</legend>
				
				<dl>
					{foreach from=$optionCategory[options] item=userOption}
						<dt>{lang}wcf.user.option.{@$userOption[object]->optionName}{/lang}</dt>
						<dd>{@$userOption[object]->optionValue}</dd>
					{/foreach}
				</dl>
			</fieldset>
		{/foreach}
	{/foreach}
</div>