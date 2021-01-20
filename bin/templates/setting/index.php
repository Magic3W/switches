
<div class="spacer large"></div>

<div class="row s4">
	<div class="span s1">
		<div style="border-radius: 50%; width: 196px; height: 196px; overflow: hidden; box-shadow: 0 0 5px #CCC; display: grid; align-items: center">
			<?php $avatar = db()->table('avatar')->get('user', db()->table('user')->get('_id', $authUser->id))->where('expires', null)->first() ?>
			<?php if ($avatar): ?>
			<figure id="figure-upload-preview" data-src="<?= $avatar->figure ?>:<?= $avatar->secret ?>" data-fallback="<?= url('avatar', 'fallback', $authUser->id)->setExtension('svg') ?>" data-size="square-s,capped-s" style='width: 100%; vertical-align: middle'></figure>
			<?php else : ?>
			<div style="border-radius: 50%; width: 256px; height: 256px; overflow: hidden; box-shadow: 0 0 5px #CCC;">
				<img src="<?= url('avatar', 'fallback', $authUser->id)->setExtension('svg'); ?>" style="width: 100%">
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="span s3">
		<h1>Hello, <?= db()->table('displayname')->get('user', db()->table('user')->get('_id', $authUser->id))->where('expires', null)->first()->name ?></h1>
		<p>You can here adjust all your account's preferences. </p>
	</div>
</div>

<div class="spacer large"></div>

<div class="row l1">
	<div class="span l1">
		<div class="material">
			<?php foreach ($settings as $node): ?>
				<?php if ($group = db()->table('definitions\group')->get('node', $node)->first()): ?>
				<div class="row l10">
					<div class="span l1">
						<div style="border-radius: 50%; width: 64px; height: 64px; overflow: hidden;">
							<figure data-src="<?= $group->icon ?>:<?= $group->secret ?>" data-size="square-s"></figure>
						</div>
					</div>
					<div class="span l9">
						<div><a href="<?= url(['setting'], 'index', $group->_id) ?>"><?= _q(_e($group->title?: 'Untitled group')) ?></a></div>
						<div class="text:grey-500"><?= _e($group->description) ?></div>
					</div>
				</div>
				<?php elseif (null !== ($setting = db()->table('definitions\setting')->get('node', $node)->first()) && 
						  /* Computed settings that are not allowed to be overridden, should not be listed */
						  !db()->table('definitions\computed')->where('target', $setting)->where('hidden', true)->first()): ?>
				<div class="row l10">
					<div class="span l1">
						<div style="border-radius: 50%; width: 64px; height: 64px; overflow: hidden;">
							<figure data-src="<?= $setting->icon ?>:<?= $setting->secret ?>" data-size="square-s"></figure>
						</div>
					</div>
					<div class="span l9">
						<div class="spacer minuscule"></div>
						<div><a href="<?= url(['setting'], 'set', $setting->_id) ?>"><?= _q(_e($setting->caption?: 'Untitled setting')) ?></a></div>
						<div class="spacer minuscule"></div>
						<div class="text:grey-500"><?= _e($setting->description) ?></div>
					</div>
				</div>
				<?php else: ?>
				Orphaned node <?= $node->_id ?>
				<?php endif ?>
				<div class="spacer medium"></div>
			<?php endforeach; ?>
		</div>
	</div>
</div>