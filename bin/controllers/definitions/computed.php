<?php namespace definitions;

use BaseController;
use spitfire\exceptions\HTTPMethodException;

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

class ComputedController extends BaseController
{
	
	public function index() {
		$query = db()->table('definitions\computed')->getAll();
		$pages = new \spitfire\storage\database\pagination\Paginator($query);
		
		$this->view->set('records', $pages->records());
		$this->view->set('pages', $pages);
	}
	
	public function create() {
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException(); }
			
			$computed = db()->table('definitions\computed')->newRecord();
			$computed->app = $_POST['app'];
			$computed->source = db()->table('definitions\setting')->get('_id', $_POST['source'])->first(true);
			$computed->target = db()->table('definitions\setting')->get('_id', $_POST['target'])->first(true);
			$computed->hidden = isset($_POST['hidden']) && $_POST['hidden'];
			$computed->store();
			
			$this->view->set('created', $computed);
		} 
		catch (HTTPMethodException $ex) {}
	}
	
	
	public function update(ComputedModel $computed) {
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException(); }
			
			$computed->app = $_POST['app'];
			$computed->source = db()->table('definitions\setting')->get('_id', $_POST['source'])->first(true);
			$computed->target = db()->table('definitions\setting')->get('_id', $_POST['target'])->first(true);
			$computed->hidden = isset($_POST['hidden']) && $_POST['hidden'];
			$computed->store();
			
			$this->view->set('created', $computed);
		} 
		catch (HTTPMethodException $ex) {}
		
		$this->view->set('computed', $computed);
	}
	
	
	public function delete(ComputedModel $computed) {
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException(); }
			
			$computed->delete();
			$this->view->set('deleted', true);
		} 
		catch (HTTPMethodException $ex) {}
		
		$this->view->set('computed', $computed);
	}
	
}
