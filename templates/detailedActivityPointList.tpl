<div id="userTableContainer" class="tabularBox marginTop shadow">
	<table class="table jsClipboardContainer">
		<thead>
			<tr>
				<th>{lang}wcf.user.activity.point.objects{/lang}</th>
				<th>{lang}wcf.user.activity.point.objectType{/lang}</th>
				<th>{lang}wcf.user.activity.point.pointsPerObject{/lang}</th>
				<th>{lang}wcf.user.activity.point.sum{/lang}</th>
			</tr>
		</thead>
		<tbody>
			{assign var='activityPointSum' value=0}
			{foreach from=$activityPointObjectTypes item='objectType'}
				{if $objectType->activityPoints > 0 && $objectType->points > 0}
					<tr>
						<td class="columnText">
							{#$objectType->activityPoints/$objectType->points} ×
						</td>
						<td class="columnTitle">
							{lang}wcf.user.activity.point.objectType.{$objectType->objectType}{/lang}
						</td>
						<td class="columnDigits">
							{#$objectType->points}
						</td>
						<td class="columnDigits">
							{#$objectType->activityPoints}
						</td>
						{assign var='activityPointSum' value=$activityPointSum + $objectType->activityPoints}
					</tr>
				{/if}
			{/foreach}
			
			{if $user->activityPoints - $activityPointSum > 0}
				<tr>
					<td class="columnTitle right" colspan="3">{lang}wcf.user.activity.point.notInDependency{/lang}</td>
					<td class="columnDigits">{#$user->activityPoints - $activityPointSum}</td>
				</tr>
			{/if}
			<tr>
				<td class="columnTitle focus right" colspan="3">Σ</td>
				<td class="columnDigits focus"><span class="badge">{#$user->activityPoints}</span></td>
			</tr>
		</tbody>
	</table>
</div>