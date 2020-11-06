
<div class="row l1">
	<div class="span l1">
		
		<h1>Set your bio</h1>
		
		<p>
			Your bio is displayed on your profiles and allows users to get a quick
			idea of who you are and what your achievements and goals in life are.
			It's recommended to keep personal details out of the bio.
		</p>
		
		<div class="spacer medium"></div>
		
		<div class="material">
			<form method="POST" action="">
				<div class="row l9">
					<div class="span l1">
						<?php $dbuser = db()->table('user')->get('_id', $authUser->id)->first(); ?>
						<?php $display = db()->table('displayname')->get('user', $dbuser)->where('expires', null)->first(); ?>
						<?php $avatar = db()->table('avatar')->get('user', $dbuser)->where('expires', null)->first(); ?>
						<figure data-src="<?= $avatar->figure ?>:<?= $avatar->secret ?>" data-size="square-s:poster"  width="128" height="128" style="border-radius: 50%; width: 100%;" ></figure>
					</div>
					<div class="span l8">
						<span class="text:grey-300"><strong><?= $display? __($display->name) : $authUser->username ?></strong></span>
						<span class="text:grey-500" style="font-size: .9rem">@<?= $authUser->username ?></span>
						<div class="spacer minuscule"></div>
						<textarea type="text" name="body" class="frm-ctrl" id="input-display-name" placeholder="Tell the world about yourself..."><?= __($previous? $previous->body : '') ?></textarea>
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
			Your bio is your "business card" on our platform, keep it short and sweet.
			It is recommended that you try to write a bio that encourages people to 
			get in touch and interact with you. Try to be inviting.
		</p>
	</div>
</div>

<script type="text/javascript">
(function () {
	var maxLen = 300;
	var update = function () {
		document.getElementById('character-counter').innerHTML = maxLen - document.getElementById('input-display-name').value.length;
	}
	
	document.getElementById('input-display-name').addEventListener('input', update);
	update();
}())
</script>
