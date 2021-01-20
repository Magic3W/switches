
<?php if (isset($stored) && $stored) { current_context()->response->getHeaders()->redirect(url('setting')); return;} ?>

<div class="spacer medium"></div>

<div class="row l1">
	<div class="span l1">
		<div>
			<h1><?= _q(_e($setting->caption)) ?></h1>
			
			<div class="spacer large"></div>
			<form method="POST" action="">
				<input type="hidden" name="setting" value="<?= $setting->node->key ?>">
				
				<?php if ($setting->type === 'string'): ?> 
				<div class="frm-ctrl-outer">
					<input class="frm-ctrl" type="text" id="input-value" name="value" placeholder="">
					<label class="frm-lbl" for="input-value"><?= _q(_e($setting->caption)) ?></label>
				</div>
				<?php elseif ($setting->type === 'enum'): ?> 
				<div class="frm-ctrl-outer">
					<select class="frm-ctrl" name="value" id="input-value">
						<?php foreach (json_decode($setting->additional) as $value => $option): ?> 
						<option value="<?= _q(_e($value)) ?>" <?= isset($preference) && $preference->value == $value? 'selected' : '' ?>><?= _e($option) ?></option>
						<?php endforeach ?> 
					</select>
					<label class="frm-lbl" for="input-value"><?= _q(_e($setting->caption)) ?></label>
				</div>
				<?php elseif ($setting->type === 'boolean'): ?>
				<div>
					<label>
						<input type="checkbox" name="value" id="input-value" <?= isset($preference) && $preference->value? 'checked' : '' ?>>
						<?= _q(_e($setting->caption)) ?>
					</label>
				</div>
				<?php elseif ($setting->type === 'external'): ?>
				<div>
					<p class="text:grey-700">
						This is an external setting. This means that another application needs to be opened for this setting
						to be changed. Please click the button below to open the external application.
					</p>
					
					<?php $hideSubmit = true ?>
					<div class="spacer large"></div>
					<div class="align-center">
						<a class="button" href="<?= $setting->additional ?>">Open <?= parse_url($setting->additional, PHP_URL_HOST) ?></a>
					</div>
					<div class="spacer large"></div>
					<p class="text:grey-500">
						<small>
							This setting is handled by an external application, which may as well be a third party,
							please make sure that you understand what permissions you are giving this third party.
							If you're not sure what to do, please contact administration.
						</small>
					</p>
				</div>
				<?php elseif ($setting->type === 'media'): ?>
				<div>
					<?= $previous = isset($preference) && $preference->value? explode(':', $preference->value) : null ?>
					<img id="figure-upload-preview" data-src="<?= $previous? implode(':', $previous) : '' ?>" data-size="capped-m" style='width: 100%; vertical-align: middle'>
					<input type="file" style="display: none;" id="figure-upload-input">
					<a class="button outline" id="figure-upload-link">Click to select a file</a>
					<input type="hidden" name="value[figure]" id="input-figure">
					<input type="hidden" name="value[secret]" id="input-secret">
				</div>
				<?php else : ?>
				<?php endif; ?> 
				
				<?php if (isset($hideSubmit) && $hideSubmit): ?>
				<?php else: ?>
				<div class="spacer medium"></div>
				
				<div class="align-right">
					<input type="submit" id="input-submit" class="button" value="Store">
				</div>
				<?php endif ?>
			</form>
		</div>
	</div>
</div>

<script src="<?= $figure->uploadJS() ?>"></script>
<script>
(function() {
	var link = document.getElementById('figure-upload-link');
	var inpt = document.getElementById('figure-upload-input');
	var prvw = function () { return document.getElementById('figure-upload-preview'); };
	
	link.addEventListener('click', function () {
		inpt.click();
	});
	
	inpt.addEventListener('change', function () {
		var files = this.files;
		
		for (var i = 0; i < files.length; i++) {
			
			//Generate a preview
			var reader = new FileReader();
			reader.onload = function (e) {
				prvw().src = e.target.result;
				prvw().style.display = 'inline-block';
			};
			
			reader.readAsDataURL(files[i]);
			document.getElementById('input-submit').setAttribute('disabled', 'disabled')
			
			window.m3w.figure.upload.upload(files[i], function (response) {
				link.innerHTML = "Click to select a file";
				
				document.getElementById('input-figure').value = response.payload.id;
				document.getElementById('input-secret').value = response.payload.secret;
				document.getElementById('input-submit').removeAttribute('disabled')
			}, function (direction, progress) {
				if (direction == 'upload') { link.innerHTML =  (progress * 100).toFixed(0) + '%'; }
				else { link.innerHTML = 'Processing...'; }
			});
		}
	});
}());
</script>
