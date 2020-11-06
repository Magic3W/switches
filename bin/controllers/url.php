<?php

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

class URLController extends BaseController
{
	
	public function _onload() {
		parent::_onload();
		
		if (!$this->user) {
			throw new PublicException('Not logged in', 403);
		}
		
	}
	
	public function index() {
		$this->view->set('urls', db()->table('url')->get('user', UserModel::retrieve($this->user->id))->where('removed', null)->setOrder('ordinal', 'ASC')->all());
	}
	
	public function add() {
		
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException('Not Posted'); }
			if (!filter_var($_POST['url'], FILTER_VALIDATE_URL)) { throw new ValidationException('Invalid URL', 2007281050, ['Invalid URL']); }
			
			$exists = db()->table('url')->get('url', $_POST['url'])->where('user', UserModel::retrieve($this->user->id))->first();
			
			if ($exists && $exists->removed == null) {
				$this->response->setBody('Redirect')->getHeaders()->redirect(url('url', 'verify', $url->_id));
				return;
			}
			
			if ($exists && $exists->removed > time() - 86400) {
				throw new PublicException('You removed this URL today. It has been blocked for 24 hours.', 403);
			}
			
			$url = db()->table('url')->newRecord();
			$url->user = UserModel::retrieve($this->user->id);
			$url->url  = $_POST['url'];
			$url->store();
			
			$this->response->setBody('Redirect')->getHeaders()->redirect(url('url', 'verify', $url->_id));
			return;
		} 
		catch (HTTPMethodException $ex) {
			#Do nothing, show the form
		}
	}
	
	public function remove(URLModel$url) {
		
		#Set the success flag to false, assuming that something is not right, until
		#all checks succeeded and the link is definitely deleted
		$this->view->set('success', false);
		$this->view->set('url', $url);
		
		#Since the system checks that the user is logged in in the onload method, 
		#we do not need to check again.
		
		/*
		 * Check whether the user is allowed to delete the URL. This could benefit
		 * from using policy instead, this way we could introduce admins or mods that
		 * can delete content without the user being implicated.
		 */
		$dbuser = db()->table('user')->get('_id', $this->user->_id)->first();
		
		if ($dbuser->_id != $url->user->_id) {
			throw new PublicException('Not authorized', 403);
		}
		
		/*
		 * Flag the URL as removed by the user. Causing the system to no longer list
		 * it and later delete it once data retention policy allows for it.
		 */
		$url->removed = time();
		$url->store();
		
		#TODO: Create a task to incinerate the old URL once data retention policy expired
		
		$this->view->set('success', true);
	}
	
	/**
	 * 
	 * 
	 * @todo This method needs to be able to do some black magic for websites like Facebook or Twitter that will not show the actual content.
	 * @param URLModel $url
	 * @param type $confirm
	 * @return type
	 * @throws PublicException
	 */
	public function verify(URLModel$url, $confirm = null) {
		
		$xsrf = new \spitfire\io\XSSToken();
		
		if ($confirm && $xsrf->verify($confirm)) {
			
			if ($url->requested > time() - 120) {
				throw new PublicException('URLs can only be verified every 2 minutes', 400);
			}
			
			$url->requested = time();
			$url->store();
			
			$body = request($url->url)->send()->expect(200)->html();
			
			if (stripos($body, $url->secret) !== false) {
				$url->verified = time();
				$url->store();
				
				#TODO: Create a task to fetch the appropriate icon
				
				
				$this->response->setBody('Redirect')->getHeaders()->redirect(url('url', 'detail', $url->_id));
				return;
			}
		}
		
		$this->view->set('xsrf', $xsrf);
		$this->view->set('url', $url);
	}
	
	public function detail(URLModel$url) {
		$this->view->set('url', $url);
	}
	
	public function arrange() {
		
		if (!$this->user) {
			throw new PublicException('You need to be logged in for this', 403);
		}
		
		$urls = db()->table('url')->get('user', UserModel::retrieve($this->user->id))->where('removed', null)->all();
		$order = collect($_POST['urls']);
		
		$urls->each(function ($e) use ($order) {
			if (!$order->contains($e->_id)) { throw new PublicException('URLs to be sorted are missing', 400); }
		});
		
		foreach ($urls as $item) {
			$item->ordinal = array_search($item->_id, $order->toArray()) + 1;
			$item->store();
		}
	}
	
}
