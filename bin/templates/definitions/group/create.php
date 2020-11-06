<?php if (isset($item)) { 
	return current_context()->response->setBody('Redirect')->getHeaders()->redirect(url(['definitions', 'group'], 'detail', $item->_id)); } ?>

<div class="row l1">
	<div class="span l1">
		
		<h1>Create section</h1>
		
		<p>
			Add a section to the user settings, a section (or group) will allow you
			aggregate similar options and options that are related within a group.
			This allows your application to define big setting trees that are easy
			to navigate for the user.
		</p>
		
		<div class="spacer medium"></div>
		
		<div class="material unpadded">
			<?php if ($parent): ?>
			<div class="padded">
				<div class="row l10">
					<div class="span l1 align-center">
						<div class="spacer minuscule"></div>
						<div style="display: inline-block; border-radius: 50%; width: 48px; height: 48px; overflow: hidden " >
							<figure data-src="<?= $parent->icon ?>:<?= $parent->secret ?>" data-size="square-s:poster:image/webp, capped-s" style='width: 100%; vertical-align: middle'></figure>
						</div>
					</div>
					<div class="span l8">
						<span class="text:grey-500" style="font-size: .9rem">Creating in</span>
						<div class="spacer minuscule"></div>
						<strong><?= __($parent->title?: 'Unnamed') ?></strong>
					</div>
				</div>
			</div>
			<div class="border-bottom:grey-800"></div>
			<?php endif; ?>
			<div class="padded">
				<form method="POST" action="">
					<div class="row l9">
						<div class="span l1 align-center">
							<input type="file" style="display: none;" id="figure-upload-input">
							<div class="spacer small"></div>
							<div id="figure-upload-link" style="display: inline-block; border-radius: 50%; width: 64px; height: 64px; background-color: #D0E9BC; position: relative; background-image: url('<?= asset('img/picture.png') ?>'); background-position: center center; background-size: 32px auto; background-repeat: no-repeat" >
								<img id="figure-upload-preview" style="vertical-align: middle">
							</div>
						</div>
						<div class="span l8">
							<span class="text:grey-500" style="font-size: .9rem">Enter a name for your section</span>
							<div class="spacer minuscule"></div>
							<input type="text" name="title" class="frm-ctrl" id="input-url" value="" placeholder="Name...">
							<input type="hidden" name="figure" id="input-figure">
							<input type="hidden" name="secret" id="input-secret">
						</div>
					</div>

					<div class="spacer small"></div>

					<div class="align-right">
						<span id="character-counter"></span>
						<input type="submit" value="Save" class="button" id="input-submit">
					</div>
				</form>
			</div>
		</div>
		
		<div class="spacer medium"></div>
		
	</div>
</div>

<script type="text/javascript">
(function () {
	var maxLen = <?= db()->table('definitions\group')->getSchema()->getField('title')->getLength() ?>;
	var update = function () {
		document.getElementById('character-counter').innerHTML = maxLen - document.getElementById('input-url').value.length;
	}
	
	document.getElementById('input-url').addEventListener('input', update);
	update();
}())
</script>

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
				
				document.getElementById('input-figure').value = response.payload.id;
				document.getElementById('input-secret').value = response.payload.secret;
				document.getElementById('input-submit').removeAttribute('disabled')
			}, function (direction, progress) {
			});
		}
	});
}());
</script>
