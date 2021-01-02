
<div class="row l3">
	<div class="span l1 align-center">
		<div style="border-radius: 50%; width: 256px; height: 256px; overflow: hidden; box-shadow: 0 0 5px #CCC; display: grid; align-items: center">
			<?php if (isset($current)): ?>
			<figure id="figure-upload-preview" data-src="<?= $current->figure ?>:<?= $current->secret ?>" data-fallback="<?= url('avatar', 'fallback', $authUser->id)->setExtension('svg') ?>" data-size="square-s,capped-s" style='width: 100%; vertical-align: middle'></figure>
			<?php elseif ($previous): ?>
			<figure id="figure-upload-preview" data-src="<?= $previous->figure ?>:<?= $previous->secret ?>" data-fallback="<?= url('avatar', 'fallback', $authUser->id)->setExtension('svg') ?>" data-size="square-s,capped-s" style='width: 100%; vertical-align: middle'></figure>
			<?php else : ?>
			<div style="border-radius: 50%; width: 256px; height: 256px; overflow: hidden; box-shadow: 0 0 5px #CCC;">
				<img src="<?= url('avatar', 'fallback', $authUser->id)->setExtension('svg'); ?>" style="width: 100%">
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="span l2">
		<h1>Selecting a new avatar</h1>
		<p>
			Select an avatar that represents you. You can select any image, the image
			will be automatically cropped to be square.
		</p>
		
		<div class="spacer medium"></div>
		
		<div class="align-center">
			<input type="file" style="display: none;" id="figure-upload-input">
			<a class="button outline" id="figure-upload-link">Click to select a file</a>
		</div>
		
		<div class="spacer medium"></div>
		
		<p class="text:grey-500">
			Please remember that your avatar is	visible to guest users and potentially 
			minors, avatars that are inappropriate may be removed without notice.
		</p>
		
		<div class="spacer medium"></div>
		
		<div class="align-center">
			<form method="POST">
				<input type="hidden" name="figure" id="input-figure">
				<input type="hidden" name="secret" id="input-secret">
				<input type="submit" value="Store" id="input-submit" disabled class="button" style="width: 100%">
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

