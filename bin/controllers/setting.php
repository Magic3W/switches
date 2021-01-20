<?php

use definitions\GroupModel;
use definitions\SettingModel;
use spitfire\exceptions\HTTPMethodException;
use spitfire\exceptions\PublicException;

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
	
	public function index(GroupModel$parent = null) {
		
		if (!$this->user) {
			throw new PublicException('No authenticated user. Please login', 403);
		}
		
		$settings = db()->table('definitions\node')->get('parent', $parent)->where('deleted', null)->setOrder('ordinal', 'ASC')->all();
		$this->view->set('settings', $settings);
	}
	
	/**
	 * 
	 * @param SettingModel $setting This variable is only used in get scenarios
	 * @throws HTTPMethodException
	 */
	public function set(SettingModel $setting = null) {
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException(); }
			
			$node = db()->table('definitions\node')->get('key', $_POST['setting'])->first();
			$setting = db()->table('definitions\setting')->get('node', $node)->first(true);
			$preference = db()->table('setting')->get('setting', $setting)->where('user', UserModel::retrieve($this->user->id))->first()?: db()->table('setting')->newRecord();
			
			$preference->setting = $setting;
			$preference->user = UserModel::retrieve($this->user->id);
			$preference->value = $_POST['value'];
			$preference->store();
			
			$this->view->set('stored', true);
		}
		catch (HTTPMethodException$e) {
			$preference = db()->table('setting')->get('setting', $setting)->first();
		}
		
		$this->view->set('preference', $preference);
		$this->view->set('setting', $setting);
	}
	
	public function reset(SettingModel$setting = null) {
		
		if (!$setting) {
			$node = db()->table('definitions\node')->get('key', $_POST['setting'])->first();
			$setting = db()->table('definitions\setting')->get('node', $node)->first(true);
		}
		
		$preference = db()->table('setting')->get('setting', $setting)->where('user', UserModel::retrieve($this->user->id))->first();
		$preference && $preference->delete();
		
		$this->view->set('setting', $setting);
		$this->view->set('preference', $preference);
	}
	
}
