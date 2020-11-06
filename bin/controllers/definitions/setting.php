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

class SettingController extends BaseController
{
	
	public function create(GroupModel$parent = null) {
		#TODO: Check the privileges of the user
		
		$figure = new SDK($this->sso, Environment::get('figure'));
		$this->view->set('parent', $parent);
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException(); }
			
			$node = db()->table('definitions\node')->newRecord();
			$node->store();
			
			$item = db()->table('definitions\setting')->newRecord();
			$item->caption = $_POST['title'];
			$item->description = $_POST['description'];
			$item->type = $_POST['type'];
			$item->additional = $_POST['additional'];
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
	
	public function show(SettingModel$item) {
		$this->view->set('item', $item);
	}
	
	public function update(SettingModel$item) {
		
		
		try {
			if (isset($_POST['caption'])) { 
				$item->title = $_POST['caption'];
				$item->store();
			}
			
			if (isset($_POST['description'])) { 
				$item->title = $_POST['description'];
				$item->store();
			}
			
			if (isset($_POST['additional'])) { 
				$item->title = $_POST['additional'];
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
	
	public function delete(SettingModel$item, $confirm = null) {
		
		$xsrf = new XSSToken();
		$this->view->set('item', $item);
		$this->view->set('node', $item->node);
		$this->view->set('xsrf', (string)$xsrf);
		$this->view->set('deleted', false);
		
		if ($confirm && $xsrf->verify($confirm)) {
			/*
			 * The node needs to be extracted, so we can delete this too.
			 */
			$node = $item->node;
			$node->deleted = time();
			$node->store();
			
			\spitfire\core\async\Async::defer(0, new \defer\DefinitionNodeIncinerator($node->_id));
			
			$this->view->set('deleted', true);
		}
	}
}