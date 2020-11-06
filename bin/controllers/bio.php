<?php

use spitfire\validation\ValidationException;
use spitfire\exceptions\HTTPMethodException;

class BioController extends BaseController
{
	
	/**
	 * Provides the current user with the necessary tools to adjust their display
	 * name.
	 * 
	 * @validate >> POST.body (required string length[3, 300])
	 */
	public function set() {
		
		if (!$this->user) {
			throw new PublicException('Please login first', 401);
		}
		
		$user = UserModel::retrieve($this->user->id);
		$previous = db()->table('bio')->get('user', $user)->where('expires', null)->first();
		
		/*
		 * We always fetch the old displayname to make it easier for the user to edit
		 * it and present them with the details of their change.
		 */
		$this->view->set('previous', $previous);
		
		try {
			if (!$this->request->isPost()) { throw new HTTPMethodException('not posted'); }
			if (!empty($this->validation)) { throw new ValidationException('Validation error', 2007230955, $this->validation); }
			
			$change = db()->table('bio')->newRecord();
			$change->user = $user;
			$change->body = $_POST['body'];
			$change->expires = null;
			$change->store();
			
			if ($previous) {
				$previous->expires = time();
				$previous->store();
			}
			
			$this->view->set('current', $change);
			return;
		}
		catch (\spitfire\exceptions\HTTPMethodException$e) {
			/*Do nothing about it.*/
		}
		
	}
	
}