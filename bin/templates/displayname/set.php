
<div class="row l1">
	<div class="span l1">
		
		<h1>Set your display name</h1>
		
		<p>
			The display name is the preferred way to display your identity across the
			site. Unlike the username, your display name does not need to be unique and
			may contain special characters or spaces.
		</p>
		
		<div class="spacer medium"></div>
		
		<div class="material">
			<form method="POST" action="">
				<div class="row l9">
					<div class="span l1">
						<?php $avatar = db()->table('avatar')->get('user', db()->table('user')->get('_id', $authUser->id)->first())->where('expires', null)->first(); ?>
						<figure data-src="<?= $avatar->figure ?>:<?= $avatar->secret ?>" data-size="square-s:poster"  width="128" height="128" style="border-radius: 50%; width: 100%;" ></figure>
					</div>
					<div class="span l8">
						<span class="text:grey-500" style="font-size: .9rem">@<?= $authUser->username ?></span>
						<div class="spacer minuscule"></div>
						<input type="text" name="name" class="frm-ctrl" id="input-display-name" value="<?= $previous? $previous->name : $authUser->username ?>" placeholder="Display name...">
					</div>
				</div>
				
				<div class="spacer small"></div>
				
				<div class="align-right">
					<span id="character-counter"></span>
					<input type="submit" value="Save" class="button">
				</div>
			</form>
		</div>
		
		<div class="spacer medium"></div>
		
		<p class="text:grey-500">
			If you choose a display name that is too long for the system to manage, applications
			may shorten it to display it properly. For best effect, please pick a display name that 
			is short and descriptive.
		</p>
	</div>
</div>

<script type="text/javascript">
(function () {
	var maxLen = <?= db()->table('displayname')->getSchema()->getField('name')->getLength() ?>;
	var update = function () {
		document.getElementById('character-counter').innerHTML = maxLen - document.getElementById('input-display-name').value.length;
	}
	
	document.getElementById('input-display-name').addEventListener('input', update);
	update();
}())
</script>
