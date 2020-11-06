
<div class="row l1">
	<div class="span l1">
		
		<h1>Verify your URL</h1>
		
		<p>
			To make sure that you own the URL <code><?= __($url->url) ?></code> you 
			will need to add the following random code to it. This can be done in one
			of several ways. 
		</p>
		
		<div class="spacer medium"></div>
		
		<div class="material">
			<div class="align-center" style="font-size: 3rem">
				<?= __($url->secret) ?>
			</div>
		</div>
		
		<div class="spacer medium"></div>
		
		<p>
			For example, if you're adding a social media profile you
			can put this code inside your bio or profile description or a post that's
			pinned to your profile. If you are adding a website or a blog, you can 
			include this code inside a comment in your HTML code.
		</p>
		
		<div class="spacer medium"></div>
		
		<p class="text:grey-500">
			Copy the code into your website, and click verify to start the verification
			and then wait for the system to process your verification. Once the verification
			is complete you can remove the code from your website.
		</p>
		
		<div class="spacer medium"></div>
		
		<p class="text:red-500 align-center" id="request-error" style="display: none">
			Verification of your URL was unsuccessful. Please try again, you will have to
			wait for 2 minutes before you can retry. You can also skip the verification if you
			are having trouble adding this.
		</p>
		
		<div class="spacer medium"></div>
		
		<div class="align-center">
			<a class="button" id="submit-verification" href="<?= url('url', 'verify', $url->_id, (string)$xsrf) ?>">Verify</a>
			<a class="button borderless" href="<?= url('url', 'index', $url->_id) ?>">Skip verification</a>
		</div>
	</div>
</div>

<script type="text/javascript">
(function () {
	var timer = <?= $url->requested?: 0 ?> + 120;
	var button = document.getElementById('submit-verification');
	var error  = document.getElementById('request-error');
	
	setInterval(function () {
		var current = (+new Date()) / 1000;
		
		if (timer > current) {
			button.disabled = true;
			button.innerHTML = parseInt(timer - current) + ' seconds...';
			error.style.display ='block';
		} else {
			button.disabled = true;
			button.innerHTML = 'Verify';
			error.style.display = 'none';
		}
	}, 100);
}());
</script>
