<input type="number" id="{$option->optionName}_age_from" name="values[{$option->optionName}][ageFrom]" value="{@$valueAgeFrom}" placeholder="{lang}wcf.user.birthday.age.from{/lang}" max="120" maxlength="3" class="tiny" />
<input type="number" id="{$option->optionName}_age_to" name="values[{$option->optionName}][ageTo]" value="{@$valueAgeTo}" placeholder="{lang}wcf.user.birthday.age.to{/lang}" max="120" maxlength="3" class="tiny" />

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#{$option->optionName}_age_from').parents('dl:eq(0)').find('> dt > label').text('{lang}wcf.user.birthday.age{/lang}').attr('for', '{$option->optionName}_age_from');
});
//]]>
</script>