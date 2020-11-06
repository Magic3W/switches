<?php namespace definitions;

use BaseController;
use definitions\GroupModel;
use figure\SDK;
use spitfire\core\Environment;
use spitfire\exceptions\HTTPMethodException;
use spitfire\io\XSSToken;
use function db;

/* 
 * The MIT License
 *
 * Copyright 2020 César de la Cal Bretschneider <cesar@magic3w.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * This controller allows an operator to manage the setting groups in switches. A
 * setting group aggregates a series of settings under a category.
 * 
 * Groups can be nested.
 * 
 * @author César de la Cal Bretschneider <cesar@magic3w.com>
 */
class GroupController extends BaseController
{
	
	/**
	 * Lists the groups and settings within a parent (the parent may be null to
	 * indicate listing the root elements). This method requires the user to have
	 * permissions to edit the groups.
	 * 
	 * @policy access @.definitions.group
	 * @policy-enforce access
	 * 
	 * @param GroupModel $parent
	 */
	public function index(GroupModel$parent = null) {
		$node = $parent? $parent->node : null;
		$children = db()->table('definitions\node')->get('parent', $node)->where('deleted', null)->setOrder('ordinal', 'ASC')->all();
		
		$this->view->set('parent', $parent);
		$this->view->set('children', $children);
	}
	
	/**
	 * Creates a new group.
	 * 
	 * @policy access @.definitions.group
	 * @policy-enforce access
	 * 
	 * @param GroupModel $parent
	 * @throws HTTPMethodException
	 */
	public function create(GroupModel$parent = null) {
		
		#TODO: Figure should be initialized outside of this environment.
		$figure = new SDK($this->sso, Environment::get('figure'));
		$this->view->set('parent', $parent);
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException(); }
			
			$node = db()->table('definitions\node')->newRecord();
			$node->store();
			
			$item = db()->table('definitions\group')->newRecord();
			$item->title = $_POST['title'];
			$item->node  = $node;
			
			$figure->claim($_POST['figure'], $_POST['secret']);
			$item->icon   = $_POST['figure'];
			$item->secret = $_POST['secret'];
			$item->store();
			
			if ($parent) { 
				$node->parent = $parent->node; 
				$node->store();
			}
			
			$this->view->set('item', $item);
		} 
		catch (HTTPMethodException$ex) {/*Do nothing*/}
	}
	
	/**
	 * This method allows updating some of the properties of the group, currently
	 * this is limited to the title and the parent element.
	 * 
	 * @param GroupModel $item
	 */
	public function update(GroupModel$item) {
		
		try {
			if (isset($_POST['title'])) { 
				$item->title = $_POST['title'];
				$item->store();
			}
			
			if (isset($_POST['parent'])) { 
				$item->node->parent = db()->table('definitions\group')->get('_id', $_POST['parent'])->first()->node;
				$item->node->store();
			}
			
			
			$this->view->set('item', $item);
		} 
		catch (HTTPMethodException$ex) {/*Do nothing*/}
	}
	
	/**
	 * Removes a group from the system. Since groups may be extremely costly to delete, 
	 * the system should perform advisory deletion and start purging the database
	 * in the background.
	 * 
	 * @param GroupModel $item
	 * @param string $confirm
	 */
	public function delete(GroupModel$item, $confirm = null) {
		
		$xsrf = new XSSToken();
		$this->view->set('item', $item);
		$this->view->set('xsrf', $xsrf);
		$this->view->set('deleted', false);
		
		if ($confirm && $xsrf->verify($confirm)) {
			$item->node->deleted = time();
			$item->node->store();
			
			\spitfire\core\async\Async::defer(0, new \defer\DefinitionNodeIncinerator($item->node->_id));
			
			$this->view->set('deleted', true);
		}
	}
	
	/**
	 * Invoking this method allows an operator to rearrange the menus for the 
	 * users' settings pages.
	 * 
	 * @param GroupModel $parent
	 * @throws PublicException
	 */
	public function arrange(GroupModel$parent = null) {
		if (!$this->user) {
			throw new PublicException('You need to be logged in for this', 403);
		}
		
		$sections = db()->table('definitions\node')->get('parent', $parent)->where('deleted', null)->all();
		$order = collect($_POST['sections']);
		
		$sections->each(function ($e) use ($order) {
			if (!$order->contains($e->_id)) { throw new PublicException('Sections to be sorted are missing', 400); }
		});
		
		foreach ($sections as $item) {
			$item->ordinal = array_search($item->_id, $order->toArray()) + 1;
			$item->store();
		}
	}
}
