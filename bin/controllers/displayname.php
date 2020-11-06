<?php

use spitfire\exceptions\HTTPMethodException;

class DisplaynameController extends BaseController
{
	
	/**
	 * Provides the current user with the necessary tools to adjust their display
	 * name.
	 */
	public function set() {
		
		if (!$this->user) {
			throw new PublicException('Please login first', 401);
		}
		
		$user = UserModel::retrieve($this->user->id);
		$previous = db()->table('displayname')->get('user', $user)->where('expires', null)->first();
		
		/*
		 * We always fetch the old displayname to make it easier for the user to edit
		 * it and present them with the details of their change.
		 */
		$this->view->set('previous', $previous);
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException('not posted'); }
			
			$change = db()->table('displayname')->newRecord();
			$change->user = $user;
			$change->name = $_POST['name'];
			$change->expires = null;
			$change->store();
			
			if ($previous) {
				$previous->expires = time();
				$previous->store();
			}
			
			$this->view->set('current', $change);
			$this->view->set('previous', $change);
			return;
		}
		catch (\spitfire\exceptions\HTTPMethodException$e) {
			/*Do nothing about it.*/
		}
		
	}
	
}