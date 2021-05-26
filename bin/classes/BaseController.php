<?php

use magic3w\phpauth\sdk\SSO;
use permission\Permission;
use spitfire\cache\MemcachedAdapter;
use spitfire\core\Environment;
use spitfire\io\session\Session;
use figure\sdk\Client as Figure;
use magic3w\phpauth\sdk\Token;

class BaseController extends Controller
{
	
	/** @var Session */
	protected $session;
	
	/** 
	 * 
	 * @var SSO 
	 */
	public $sso;

	/** 
	 * @var Token 
	 */
	protected $token;

	/** @var object */
	protected $user;
	
	protected $authapp;
	
	protected $permission;
	
	protected $figure;
	
	public function _onload() {
		$this->sso   = new SSO(Environment::get('SSO'));
		$this->figure   = new Figure($this->sso, Environment::get('figure'));
		#$this->permission = new Permission(Environment::get('permission'), $this->sso);
		
		#TODO: Read the expiration from a JWT from the Authorization header
		#TODO: This code should be moved towards an authentication provider that generates tokens
		$s = $this->session = Session::getInstance();
		
		if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
			list($type, $token) = explode(' ', $_SERVER['HTTP_AUTHORIZATION'], 2);
			/**
			 * NOTE: This code is dangerous without the later verification using getTokenInfo.
			 * This is a temprary measure for the time being while we implement JWT support 
			 * properly.
			 */
			$t = new Token($this->sso, $token, time() + 240);
		}
		else {
			$t = $s->getUser(spitfire());
		}
		
		#TODO: This should be removed when the JWT are in place since they provide proper expiration times.
		#Create a cache to reduce the load on PHPAuth
		$c = new MemcachedAdapter();
		$c->setTimeout(120);
		
		$this->token = $t?                          $t                       : null;
		$this->user  = $t ? $c->get('token_' . $this->token->getId(), function () use ($t) { 
			return $t->isAuthenticated()? $t->getTokenInfo()->user : null; 
		}) : null;
		
		/**
		 * From here on out, we will have a client for every token that we generate.
		 * 
		 * This client might be the application itself (when just logging in a user)
		 * or it might be a remote application requesting to act on the user's behalf.
		 */
		$this->authapp = $t? $t->client() : null;
		
		$this->view->set('authUser', $this->user);
		$this->view->set('authSSO', $this->sso);
		$this->view->set('figure', $this->figure);
		
	}
	
}
