

<div class="row l1 ng-lr" data-draggable="context">
	<div class="span l1">
		<?php foreach ($children as $node): ?>
		<div class="row l1" data-draggable="element" data-section="<?= $node->_id ?>">
			<div class="span l1">
				<div class="material">
					<span class="draggable-handle" data-draggable="handle"></span>
					<?php if ($group = db()->table('definitions\group')->get('node', $node)->first()): ?>
					<a href="<?= url(['definitions', 'group'], 'index', $group->_id) ?>"><?= _q(_e($group->title)) ?></a>
					<?php elseif ($setting = db()->table('definitions\setting')->get('node', $node)->first()): ?>
					Setting <?= $setting->_id ?>
					<?php else: ?>
					Orphaned node <?= $node->_id ?>
					<?php endif ?>
				</div>
				<div class="spacer small"></div>
			</div>
		</div>
		<?php endforeach ?>
	</div>
</div>

<div class="spacer medium"></div>

<div class="row l1">
	<div class="span l1 align-center">
		<a class="button outline button-color-grey-700" href="<?= url(['definitions', 'group'], 'create', $parent? $parent->_id : null) ?>">+ Add group</a>
		<a class="button outline button-color-grey-700" href="<?= url(['definitions', 'setting'], 'create', $parent? $parent->_id : null) ?>">+ Add setting</a>
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
		xhr.open('POST', '<?= url(['definitions', 'group'], 'arrange', $parent? $parent->_id : null)->setExtension('json') ?>');
		xhr.setRequestHeader('Content-type', 'application/json');
		xhr.send(JSON.stringify({
			'sections' : children.map(function (e) { return e.element.dataset.section; })
		}));
	});
}());
</script>
