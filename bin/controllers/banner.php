<?php

use figure\SDK as Figure;
use spitfire\core\Environment;
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

class BannerController extends BaseController
{
	
	public function set() {
		$figure = new Figure($this->sso, Environment::get('figure'));
		
		if (!$this->user) {
			throw new PublicException('Please login first', 401);
		}
		
		$user = UserModel::retrieve($this->user->id);
		$previous = db()->table('banner')->get('user', $user)->where('expires', null)->first();
		
		/*
		 * We always fetch the old displayname to make it easier for the user to edit
		 * it and present them with the details of their change.
		 */
		$this->view->set('previous', $previous);
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException('not posted'); }
			
			$change = db()->table('banner')->newRecord();
			$change->user = $user;
			$change->figure = $_POST['figure'];
			$change->secret = $_POST['secret'];
			$change->expires = null;
			$change->store();
			
			$figure->delete($previous->figure);
			$figure->claim($_POST['figure'], $_POST['secret']);
			
			$previous && $previous->expires = time();
			$previous && $previous->store();
			
			$this->view->set('current', $change);
		}
		catch (HTTPMethodException$e) {
			/*Do nothing about it.*/
		}
		
		$this->view->set('figure', $figure);
	}
	
}
