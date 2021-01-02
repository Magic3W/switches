<?php if (isset($created) && $created) {current_context()->response->getHeaders()->redirect(url()); } ?>

<div class="spacer medium"></div>

<div class="row l1">
	<div class="span l1">
		<div class="material">
			<form method="POST" action="">
				<div class="frm-ctrl-outer">
					<input type="text" name="app" placeholder="" id="appid" class="frm-ctrl">
					<label class="frm-lbl" for="appid">App ID</label>
				</div>
				
				<div class="spacer medium"></div>
				
				<div class="frm-ctrl-outer">
					<select type="text" name="source" placeholder="" id="source" class="frm-ctrl">
						<?php foreach (db()->table('definitions\setting')->getAll()->all() as $setting): ?>
						<option value="<?= _q(_e($setting->_id)) ?>"><?= _q(_e($setting->caption)) ?></option>
						<?php endforeach ?>
					</select>
					<label class="frm-lbl" for="source">Source</label>
				</div>
				
				<div class="spacer medium"></div>
				
				<div class="frm-ctrl-outer">
					<select type="text" name="target" placeholder="" id="target" class="frm-ctrl">
						<?php foreach (db()->table('definitions\setting')->getAll()->all() as $setting): ?>
						<option value="<?= _q(_e($setting->_id)) ?>"><?= _q(_e($setting->caption)) ?></option>
						<?php endforeach ?>
					</select>
					<label class="frm-lbl" for="target">Target</label>
				</div>
				
				<div class="spacer medium"></div>
				
				<label class="frm-lbl" for="hidden">
					<input type="checkbox" name="hidden" placeholder="" id="hidden" class="frm-ctrl">
					<span class="frm-ctrl-chk"></span>
					Hide the target setting, so users cannot edit it.
				</label>
				
				<div class="spacer medium"></div>
				
				<div class="align-right">
					<input type="submit" class="button" value="Store">
				</div>
			</form>
		</div>
	</div>
</div>
