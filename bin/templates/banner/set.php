
<div class="row l5">
	<div class="span l3">
		<h1>Selecting a new banner</h1>
		<p>
			Your banner allows to express yourself creatively, by adding information
			that doesn't fit in your avatar. Banners are displayed above the profile
			pages and give your page a personal flair
		</p>
		
		<div class="spacer medium"></div>
		
		<div class="align-center">
			<input type="file" style="display: none;" id="figure-upload-input">
			<a class="button outline" id="figure-upload-link">Click to select a file</a>
		</div>
		
		<div class="spacer medium"></div>
		
		<p class="text:grey-500">
			Please remember that your banner is	visible to guest users and potentially 
			minors, banners that are inappropriate may be removed without notice.
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
	<div class="span l2">
		<div class="spacer medium"></div>
		<div class="material unpadded">
			<div style="background: #DDD; overflow: hidden; max-height: 180px;">
				<?php if (isset($current)): ?>
				<figure id="figure-upload-preview" data-src="<?= $current->figure ?>:<?= $current->secret ?>" data-size="capped-m" style='width: 100%; vertical-align: middle'></figure>
				<?php elseif ($previous): ?>
				<figure id="figure-upload-preview" data-src="<?= $previous->figure ?>:<?= $previous->secret ?>" data-size="capped-m" style='width: 100%; vertical-align: middle'></figure>
				<?php else : ?>
				<img id="figure-upload-preview" src="" style="vertical-align: middle">
				<?php endif; ?>
			</div>

			<div class="padded" style="background: #FFF;">
				<?php $dbuser = db()->table('user')->get('_id', $authUser->id)->first(); ?>
				<?php $display = db()->table('displayname')->get('user', $dbuser)->where('expires', null)->first(); ?>
				<?php $avatar = db()->table('avatar')->get('user', $dbuser)->where('expires', null)->first(); ?>
				<div class="row l7 s3">
					<div class="span l2 s1  align-center">
						<div style="display: inline-block; border-radius: 50%; width: 100%; max-width: 100px; overflow: hidden; box-shadow: 0 0 5px rgba(0, 0, 0, .3); margin-top: -30px;">
							<figure data-src="<?= $avatar->figure ?>:<?= $avatar->secret ?>" data-size="square-s:poster"  width="32" height="32" style="border-radius: 50%; width: 100%; vertical-align: middle" ></figure>
						</div>
					</div>
					<div class="span l5 s2">
						<span class="text:grey-300"><strong><?= $display? __($display->name) : $authUser->username ?></strong></span>
						<span class="text:grey-500" style="font-size: .9rem">@<?= $authUser->username ?></span>
					</div>
				</div>
			</div>
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

