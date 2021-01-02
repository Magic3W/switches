

<div class="row l1">
	<div class="span l1 align-right">
		<a class="button outline button-color-grey-700" href="<?= url(['definitions', 'computed'], 'create') ?>">+ Add computed setting</a>
	</div>
</div>

<div class="spacer medium"></div>

<div class="row l1">
	<div class="span l1">
		<?php foreach ($records as $record): ?>
		<div class="row l1">
			<div class="span l1">
				<div class="material">
					<a href="<?= url(['definitions', 'computed'], 'detail', $record->_id) ?>"><?= __($record->target->node->key) ?> &raquo; <?= __($record->source->node->key) ?></a> 
				</div>
				<div class="spacer small"></div>
			</div>
		</div>
		<?php endforeach ?>
	</div>
</div>