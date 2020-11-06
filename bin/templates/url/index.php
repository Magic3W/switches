
<div class="spacer medium"></div>

<div class="row l1">
	<div class="span l1">
		
		<h1>External URL</h1>
		<div class="spacer small"></div>
		<div class="text:grey-500">
			You can add up to ten external URLs to your account so other members can
			look up more information about you.
		</div>
		
		<div class="spacer large"></div>
		
		<div data-draggable="context">
			<?php foreach ($urls as $url): ?>
			<div data-draggable="element" data-section="<?= $url->_id ?>">
				<div class="row s3 ng-lr">
					<div class="span s2">
						<span class="draggable-handle" data-draggable="handle"></span>
						<span class="text:blue-300"><strong><?= __($url->url) ?></strong></span>
						<?php if ($url->verified): ?>
						<img src="<?= asset('img/verified.png') ?>" style="height: 1rem; vertical-align: -.2rem; margin-left: .3rem" title="You verified this link">
						<?php endif; ?>
					</div>
					<div class="span s1 align-right">
						<?php if (!$url->verified): ?>
						<a href="<?= url('url', 'verify', $url->_id) ?>" class="button small outline button-color-blue-500">Verify</a>
						<?php endif; ?>
						<a href="<?= url('url', 'remove', $url->_id) ?>" class="button small outline button-color-red-500">Remove</a>
					</div>
				</div>
				<div class="spacer medium"></div>
			</div>
			<?php endforeach; ?>
		</div>
		
		<?php if ($urls->isEmpty()): ?>
		<?php endif; ?>
		
		<div class="spacer huge"></div>
		
		<div class="align-center">
			<a href="<?= url('url', 'add') ?>" class="button outline">+ Add a URL</a>
		</div>
	</div>
</div>

<script type="text/javascript">
(function () {
	
	var Draggable = function (ctx, element) {
		var self = this;
		this.ctx = ctx;
		this.element = element;
		this.placeholder = document.createElement('div');
		this.handle  = element.querySelector('[data-draggable="handle"]');
		
		this.handle.addEventListener('mousedown', function (e) {
			ctx.floating = self;
			self.lift();
			e.stopPropagation();
			e.preventDefault();
		});
		
		this.element.addEventListener('mouseover', function (e) {
			ctx.setTarget(self);
			console.log(self.element);
			e.stopPropagation();
			e.preventDefault();
		});
		
		this.placeholder.className = 'draggable-placeholder'
	};
	
	Draggable.prototype = {
		lift : function () { 
			this.placeholder.style.height = this.element.clientHeight + 'px';
			this.element.parentNode.insertBefore(this.placeholder, this.element);
			this.element.parentNode.removeChild(this.element);
		},
		
		place : function (before) {
			before.element.parentNode.insertBefore(this.placeholder, before.element);
		},
		
		drop : function () {
			this.placeholder.parentNode.insertBefore(this.element, this.placeholder);
			this.placeholder.parentNode.removeChild(this.placeholder);
		}
	};
	
	var Sortable = function (context, commit) {
		this.element = context;
		this.ctx = this;
		this.commit = commit;
		var ctx = this;
		
		this.floating = undefined;
		this.target = undefined;
		
		this.children = Array.prototype.slice.call(context.querySelectorAll('[data-draggable="element"]')).map(function (e) {
			return new Draggable(ctx, e);
		});
		
		this.setTarget = function (target) {
			ctx.target = target;
			ctx.floating && ctx.floating.place(target);
		};
		
		document.addEventListener('mouseup', function () {
			if (!ctx.floating) {
				return;
			}
			
			if (ctx.floating === ctx.target) {
				console.log('Dropping on itself');
				ctx.floating.drop();
				ctx.floating = undefined;
				return;
			}
			
			ctx.children.splice(ctx.children.indexOf(ctx.floating), 1);
			
			/*
			 * Place the element before the previous element, if the previous element
			 * does not exist it means we just swapped it with itself.
			 */
			ctx.children.splice(ctx.children.indexOf(ctx.target), 0, ctx.floating); 
			ctx.floating.drop();
			ctx.floating = undefined;
			ctx.commit(ctx.children);
		});
	};
	
	new Sortable(document.querySelector('[data-draggable="context"]'), function (children) {
		console.log(children.map(function (e) { return e.element.dataset.section; }));
		
		var xhr = new XMLHttpRequest();
		xhr.open('POST', '<?= url('url', 'arrange')->setExtension('json') ?>');
		xhr.setRequestHeader('Content-type', 'application/json');
		xhr.send(JSON.stringify({
			'urls' : children.map(function (e) { return e.element.dataset.section; })
		}));
	});
}());
</script>

