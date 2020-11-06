
<div class="row l1">
	<div class="span l1">
		
		<h1><?= __($url->url) ?></h1>
		
		<p class="text:grey-500">
			This URL was added to your account, and will be listed as belonging to you.
		</p>
		
		<div class="spacer medium"></div>
		
		<div class="material">
			<div class="spacer medium"></div>
			
			<div class="row l5 s2">
				<div class="span l2 s1">
					URL
				</div>
				<div class="span l3 s1">
					<?= __($url->url) ?>
				</div>
			</div>
			
			<div class="spacer medium"></div>
			
			<div class="row l5 s2">
				<div class="span l2 s1">
					Created
				</div>
				<div class="span l3 s1">
					<?= date('M d, Y', $url->created) ?>
				</div>
			</div>
			
			<div class="spacer medium"></div>
			
			<div class="row l5 s2">
				<div class="span l2 s1">
					Verified
				</div>
				<div class="span l3 s1">
					<?php if ($url->verified): ?>
					<span >Yes</span> <!-- TODO: Add checkmark icon -->
					<?php else: ?>
					<span class="text:red-500">No</span> &centerdot; <a href="<?= Url('url', 'verify', $url->_id) ?>">Verify now</a>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="spacer medium"></div>
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
