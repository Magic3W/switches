
<div class="row l1">
	<div class="span l1">
		
		<h1>Add URL to your account</h1>
		
		<p>
			In this step you can add a URL to your account, this way you can link to
			your profiles on other websites.
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
						<span class="text:grey-500" style="font-size: .9rem">Paste your URL here</span>
						<div class="spacer minuscule"></div>
						<input type="text" name="url" class="frm-ctrl" id="input-url" value="" placeholder="http://...">
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
			Once you hit submit you will be asked to verify your link. In this process
			you will be asked to copy a code into your existing profile so we can verify
			you are the owner of the page.
		</p>
	</div>
</div>

<script type="text/javascript">
(function () {
	var maxLen = <?= db()->table('url')->getSchema()->getField('url')->getLength() ?>;
	var update = function () {
		document.getElementById('character-counter').innerHTML = maxLen - document.getElementById('input-url').value.length;
	}
	
	document.getElementById('input-url').addEventListener('input', update);
	update();
}())
</script>
