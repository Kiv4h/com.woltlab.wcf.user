{include file='documentHeader'}

<head>
	<title>User profile page</title>
	{include file='headInclude' sandbox=false}

	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/WCF.User.Profile.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		$(function() {
			WCF.Language.addObject({
				'wcf.user.profile.followUser': 'follow',
				'wcf.user.profile.unfollowUser': 'unfollow',
				'wcf.user.profile.ignoreUser': 'ignore user',
				'wcf.user.profile.unignoreUser': 'unignore user'
			});

			new WCF.User.Profile.Follow({$user->userID}, {if $__wcf->getUserProfileHandler()->isFollowing($user->userID)}true{else}false{/if});
			new WCF.User.Profile.IgnoreUser({@$user->userID}, {if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}true{else}false{/if});

			WCF.TabMenu.init();
		});
		//]]>
	</script>
	<style type="text/css">
		div#profileButtonContainer {
			margin: 7px;
		}

		div#profileButtonContainer button {
			background-image: -o-linear-gradient(top, rgb(192, 192, 192), rgb(224, 224, 224));
			border: 1px solid rgb(192, 192, 192);
			border-radius: 3px;
			cursor: pointer;
			margin-right: 3px;
			height: 60px;
			padding: 3px;
		}

		div#profileButtonContainer button:hover {
			background-image: -o-linear-gradient(top, rgb(224, 224, 224), rgb(192, 192, 192));
		}
	</style>
</head>

<body>

{include file='header' sandbox=false}

<div id="profileButtonContainer"></div>

<button id="ignoreUser">{if $__wcf->getUserProfileHandler()->isIgnoredUser($user->userID)}un{/if}ignore user</button>

<section id="profileContent" class="tabMenuContainer">
	<nav class="tabMenu">
		<ul>
			{event name='contentListItems'}
		</ul>
	</nav>

	{event name='content'}
</section>

{include file='footer' sandbox=false}

</body>
</html>
