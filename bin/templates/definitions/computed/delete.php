<?php if (isset($deleted) && $deleted) { current_context()->response->getHeaders()->redirect(url()); return; } ?>

<div class="spacer medium"></div>

<div class="row l1">
	<div class="span l1">
		<div class="material">
			<h1>Delete computed setting?</h1>
			<p>This action cannot be undone</p>
			
			<form method="POST" action="" class="align-right">
				<a href="<?= url(['definitions', 'computed'], 'update', $computed->_id) ?>">Cancel</a>
				<input type="submit" class="button solid button-color-red-500" value="Delete">
			</form>
		</div>
	</div>
</div>