
<?php if (isset($stored) && $stored) { current_context()->response->getHeaders()->redirect(url('setting')); return;} ?>

<div class="spacer medium"></div>

<div class="row l1">
	<div class="span l1">
		<div class="material">
			<h1><?= _q(_e($setting->caption)) ?></h1>
			
			<form method="POST" action="">
				<input type="hidden" name="setting" value="<?= $setting->node->key ?>">
				
				<?php if ($setting->type === 'string'): ?> 
				<?php elseif ($setting->type === 'enum'): ?> 
				<select class="frm-ctrl" name="value">
					<?php foreach (json_decode($setting->additional) as $value => $option): ?> 
					<option value="<?= _q(_e($value)) ?>" <?= isset($preference) && $preference->value == $value? 'selected' : '' ?>><?= _e($option) ?></option>
					<?php endforeach ?> 
				</select>
				<?php elseif ($setting->type === 'boolean'): ?>
				<?php elseif ($setting->type === 'external'): ?>
				<?php elseif ($setting->type === 'media'): ?>
				<?php else : ?>
				<?php endif; ?> 
				<div class="spacer medium"></div>
				
				<div class="align-right">
					<input type="submit" class="button" value="Store">
				</div>
			</form>
		</div>
	</div>
</div>
