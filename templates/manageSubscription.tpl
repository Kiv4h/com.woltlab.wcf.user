<fieldset>
	<legend>{lang}wcf.user.objectWatch.manageSubscription{/lang}</legend>
	
	<dl class="wide">
		<dd>
			<label><input type="radio" name="subscribe" value="1"{if $userObjectWatch} checked="checked"{/if} /> {lang}wcf.user.objectWatch.subscribe.{@$objectType->objectType}{/lang}</label>
			
			<small><label><input type="checkbox" name="enableNotification" value="1"{if $userObjectWatch && $userObjectWatch->notification} checked="checked"{/if} /> {lang}wcf.user.objectWatch.enableNotification{/lang}</label></small>
		</dd>
	</dl>
	<dl class="wide">
		<dd>
			<label><input type="radio" name="subscribe" value="0"{if !$userObjectWatch} checked="checked"{/if} /> {lang}wcf.user.objectWatch.unsubscribe.{@$objectType->objectType}{/lang}</label>
		</dd>
	</dl>
</fieldset>

<div class="formSubmit">
	<button class="jsButtonSave">{lang}wcf.global.button.save{/lang}</button>
</div>