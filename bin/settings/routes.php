<?php

/* Use this file to add routes to your Spitfire app. Just use them like this
 *
 * router::route('/old/url', '/new/url');
 *
 * Or like this
 * 
 * router:route('old/url/*', 'new/$2/url');
 * 
 * Remember that routes are blocking. If one matches it'll stop the execution
 * of the following rules. So add them wisely.
 * It's really easy and fun!
 */

\spitfire\core\router\Router::getInstance()->request('/definitions/group/:action?', ['controller' => ['definitions', 'group'], 'action' => ':action']);
\spitfire\core\router\Router::getInstance()->request('/definitions/setting/:action?', ['controller' => ['definitions', 'setting'], 'action' => ':action']);
\spitfire\core\router\Router::getInstance()->request('/definitions/computed/:action?', ['controller' => ['definitions', 'computed'], 'action' => ':action']);
