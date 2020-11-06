<?php if ($deleted) {
	 current_context()->response->getHeaders()->redirect(url(['definitions', 'group'], 'index', db()->table('definitions\group')->get('node', $node->parent)->first()->_id));
} ?>
<div class="spacer medium"></div>

<div class="row l1">
	<div class="span l1">
		<h1>Confirm deletion</h1>
		<div class="material">
			<p class="text:grey-500">
				You are attempting to delete a setting. This will permanently remove 
				the setting, and erase all associated user choices. Applications that 
				rely on the availability of this setting may stop working.
				<span class="text:grey-700">This action cannot be undone.</span>
			</p>
			
			<div class="spacer medium"></div>
			
			<div class="align-center">
				<a href="<?= url(['definitions', 'setting'], 'detail', $item->_id) ?>" class="button borderless">Cancel, do not delete</a>
				<a href="<?= url(['definitions', 'setting'], 'delete', $item->_id, $xsrf) ?>" class="button button-color-red-500">Delete <?= __($item->caption) ?></a>
			</div>
			
			<div class="spacer medium"></div>
		</div>
	</div>
</div>
