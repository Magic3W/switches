<!DOCTYPE html>
<html>
	<head>
		<title><?= isset(${'page.title'}) && ${'page.title'}? ${'page.title'} : 'Account server' ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="_scss" content="<?= asset('css/_') ?>/js/">
		<meta name="figure" content="<?= $figure->URL() ?>">
		<link rel="stylesheet" type="text/css" href="<?= asset('css/app.scss') ?>">
		<link rel="stylesheet" type="text/css" href="<?= asset('css/ui-layout.scss') ?>">
		
		<script>
			window.baseURL = <?= json_encode(strval(url())); ?>
		</script>
	</head>
	<body>
		<script>
		/*
		 * This little script prevents an annoying flickering effect when the layout
		 * is being composited. Basically, since we layout part of the page with JS,
		 * when the browser gets to the JS part it will discard everything it rendered
		 * to this point and reflow.
		 * 
		 * Since the reflow MUST happen in order to render the layout, we can tell 
		 * the browser to not render the layout at all. This will prevent the layout
		 * from shift around before the user had the opportunity to click on it.
		 * 
		 * If, for some reason the layout was unable to start up within 500ms, we 
		 * let the browser render the page. Risking that the browser may need to 
		 * reflow once the layout is ready
		 */
		(function() {
			document.body.style.display = 'none';
			document.addEventListener('DOMContentLoaded', function () { document.body.style.display = null; }, false);
			setTimeout(function () { document.body.style.display = null; }, 500);
		}());
		</script>
		
		<div class="navbar">
			<div class="left">
				<div style="line-height: 32px">
					<span class="toggle-button dark"></span>
				</div>
			</div>
			<div class="right">
				<?php if(isset($authUser) && $authUser): ?>
					<div class="has-dropdown" style="display: inline-block">
						<a href="<?= url('user', $authUser->username) ?>" class="app-switcher" data-toggle="app-drawer">
							<?php $avatar = db()->table('avatar')->get('user', db()->table('user')->get('_id', $authUser->id)->first())->where('expires', null)->first(); ?>
							<figure data-src="<?= $avatar->figure ?>:<?= $avatar->secret ?>" data-size="square-s,capped-s"  width="32" height="32" style="border-radius: 50%; width: 32px; height: 32px;" ></figure>
						</a>
						<div class="dropdown right-bound unpadded" data-dropdown="app-drawer">
							<div class="app-drawer">
								<div class="navigation vertical">
									<!-- Todo: Once a dedicated profile hosting server is available, the link to editing the user profile there could be included here-->
									<a class="navigation-item" href="<?= url('user', 'logout') ?>">Logout</a>
								</div>
							</div>
						</div>
					</div>
				<?php else: ?>
					<a class="menu-item" href="<?= url('account', 'login') ?>">Login</a>
				<?php endif; ?>
			</div>
			<div class="center align-center">
				<form class="search-input">
					<input type="hidden" data-placeholder="Search..." id="search-input">
				</form>
			</div>
		</div>
		
		<div class="auto-extend">
			<div class="content">

				<div class="spacer" style="height: 30px"></div>
				
				<div  data-sticky-context>
					<?= $this->content() ?>
				</div>
			</div>
			
		</div>
		
		<footer>
			<div class="row1">
				<div class="span1">
					<span style="font-size: .8em; color: #777">
						&copy; <?= date('Y') ?> Magic3W - This software is licensed under MIT License
					</span>
				</div>
			</div>
		</footer>
		
		<div class="contains-sidebar">
			<div class="sidebar">
				<div class="spacer" style="height: 20px"></div>

				<?php if(isset($authUser)): ?>
				<div class="menu-title"> Account</div>
				<div class="menu-entry"><a href="<?= url() ?>"                  >My profile</a></div>
				<div class="menu-entry"><a href="<?= url('avatar', 'set')    ?>">Avatar</a></div>
				<div class="menu-entry"><a href="<?= url('banner', 'set')    ?>">Banner</a></div>
				<div class="menu-entry"><a href="<?= url('displayname', 'set') ?>">Display name</a></div>
				<div class="menu-entry"><a href="<?= url('bio', 'set') ?>"      >Bio</a></div>
				<div class="menu-entry"><a href="<?= url('url', 'index') ?>"    >External URL</a></div>

				<?php if(isset($userIsAdmin) && $userIsAdmin): ?> 
				<div class="spacer" style="height: 30px"></div>
				<div class="menu-title">Administration</div>
				<div class="menu-entry"><a href="<?= url('user')  ?>">Users</a></div>
				<div class="menu-entry"><a href="<?= url('group') ?>">Groups</a></div>
				<div class="menu-entry"><a href="<?= url('admin') ?>">System settings</a></div>
				<div class="menu-entry"><a href="<?= url('token') ?>">Active sessions</a></div>

				<!--APPLICATIONS-->
				<div class="menu-entry"><a href="<?= url('app') ?>"  >App administration</a></div>
				<?php endif; ?> 
				<?php endif; ?> 
				
				<div class="menu-title">Our network</div>
				<div id="appdrawer"></div>

			</div>
		</div>
		
		<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			var ae = document.querySelector('.auto-extend');
			var wh = window.innerheight || document.documentElement.clientHeight;
			var dh = document.body.clientHeight;
			
			ae.style.minHeight = Math.max(ae.clientHeight + (wh - dh), 0) + 'px';
		});
		</script>
		
		<!--Import depend.js and the router it uses to load locations -->
		<script src="<?= asset('js/m3/depend.js') ?>" type="text/javascript"></script>
		<script src="<?= asset('js/m3/depend/router.js') ?>" type="text/javascript"></script>
		<script type="text/javascript">
		(function () {
			depend(['m3/depend/router'], function(router) {
				router.all().to(function(e) { return '<?= asset('js/') ?>/' + e + '.js'; });
				router.equals('phpas/app/drawer').to( function() { return '<?= url('appdrawer')->setExtension('js') ?>'; });
				router.equals('_scss').to( function() { return '<?= asset('css/_/js/_.scss.js') ?>'; });
			});
			
			depend(['ui/dropdown'], function (dropdown) {
				dropdown('.app-switcher');
			});
			
			depend(['_scss'], function() {
				//Loaded
			});
			
		}());
		</script>
		
		<script type="text/javascript">
		
			depend(['m3/core/request'], function (Request) {
				var request = new Request('<?= url('appdrawer')->setExtension('json') ?>');
				request
					.then(JSON.parse)
					.then(function (e) {
						e.forEach(function (i) {
							console.log(i)
							var entry = document.createElement('div');
							var link  = entry.appendChild(document.createElement('a'));
							var icon  = link.appendChild(document.createElement('img'));
							entry.className = 'menu-entry';
							
							link.href = i.url;
							link.appendChild(document.createTextNode(i.name));
							
							icon.src = i.icon.m;
							document.getElementById('appdrawer').appendChild(entry);
						});
					})
					.catch(console.log);
			});
		</script>
		
		<script type="text/javascript">
			depend(['sticky'], function (sticky) {
				
				/*
				 * Create elements for all the elements defined via HTML
				 */
				var els = document.querySelectorAll('*[data-sticky]');

				for (var i = 0; i < els.length; i++) {
					sticky.stick(els[i], sticky.context(els[i]), els[i].getAttribute('data-sticky'));
				}
			});
		</script>
		
		
		<script>
		(function (figureURL, upgradeElements) { 
			
			/**
			 * This whole section of madness needs to go. Switches must be able to 
			 * provide a direct location to a user image.
			 */
			var canUseWebP = function () {
				var elem = document.createElement('canvas');

				if (!!(elem.getContext && elem.getContext('2d'))) {
					 // was able or not to get WebP representation
					 return elem.toDataURL('image/webp').indexOf('data:image/webp') == 0;
				}

				// very old browser like IE 8, canvas not supported
				return false;
			}();
			
			//Upgrades for elements that are images
			var upgradeImage = function (element, payload) {
				var replace = element.parentNode.insertBefore(document.createElement('img'), element);
				var targets = element.dataset.size.split(',');
				var source  = null;
				
				for (var i = 0; i < targets.length; i++) {
					var target = targets[i];
					if (!payload[target]) { console.log('Size unavailable: ' + target); continue; }
					
					if (payload[target].poster) {
						for (var j = 0; j < payload[target].poster.length; j++) {
							var source = document.createElement('source');
							source.srcset = payload[target].poster[j].url;
							source.type = payload[target].poster[j].mime;
							replace.appendChild(source);
						}
					}
					
					var source = document.createElement('source');
					source.srcset = payload[target].url;
					source.type = payload[target].mime;
					replace.appendChild(source);
				}
				
					
				var source = document.createElement('img');
				source.src = element.dataset.fallback || payload['original'].url;
				source.id = element.id;
				source.style.cssText = element.style.cssText;
				replace.appendChild(source);
				console.log(replace);
			};
			
			//Upgrades
			var upgrade = function (element) {
				var id, secret;
				[id, secret] = element.dataset.src.split(':');
				
				var xhr = new XMLHttpRequest();
				xhr.open('GET', `${figureURL}/upload/retrieve/${id}/${secret}.json`);
				xhr.onreadystatechange = function () {
					if (xhr.readyState !== 4) { return; }
					
					var json = JSON.parse(xhr.responseText);
					
					if (json.payload.type === 'image') {
						upgradeImage(element, json.payload.media);
					}
					
				};
				xhr.send();
			};
			
			//Upgrade any figures that could be on screen
			for (var i = 0; i < upgradeElements.length; i++) {
				upgrade(upgradeElements[i]);
			};
			
			
		} (document.querySelector('meta[name="figure"]').content, document.querySelectorAll('figure')));
		</script>
	</body>
</html>