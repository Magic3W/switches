<?php

use magic3w\phpauth\sdk\SSO;
use auth\Token;
use chad\Chad;
use permission\Permission;
use ping\Ping;
use spitfire\cache\MemcachedAdapter;
use spitfire\core\Environment;
use spitfire\io\session\Session;
use figure\sdk\Client as Figure;

class BaseController extends Controller
{
	
	/** @var Session */
	protected $session;
	
	/** @var SSO */
	public $sso;

	/** 
	 * @var Token 
	 */
	protected $token;

	/** @var object */
	protected $user;
	
	/**
	 *
	 * @var Ping
	 */
	protected $ping;
	
	/**
	 *
	 * @var Chad
	 */
	protected $chad;
	
	protected $authapp;
	
	protected $permission;
	
	protected $figure;
	
	public function _onload() {
		$this->sso   = new SSO(Environment::get('SSO'));
		$this->figure   = new Figure($this->sso, Environment::get('figure'));
		#$this->ping  = new Ping(Environment::get('ping'), $this->sso);
		#$this->chad  = new Chad(Environment::get('chad'), $this->sso);
		#$this->permission = new Permission(Environment::get('permission'), $this->sso);
		
		$s = $this->session = Session::getInstance();
		$t = isset($_GET['token'])? $this->sso->makeToken($_GET['token']) : $s->getUser(spitfire());
		
		#Create a cache to reduce the load on PHPAuth
		$c = new MemcachedAdapter();
		$c->setTimeout(120);
		
		$this->token = $t?                          $t                       : null;
		$this->user  = $t ? $c->get('token_' . $this->token->getId(), function () use ($t) { 
			return $t->isAuthenticated()? $t->getTokenInfo()->user : null; 
		}) : null;
		
		if (isset($_GET['signature']) && is_string($_GET['signature'])) {
			
			$this->authapp = $this->sso->authApp($_GET['signature'])->getSrc();
		}
		
		$this->view->set('authUser', $this->user);
		$this->view->set('authSSO', $this->sso);
		$this->view->set('figure', $this->figure);
		
	}
	
}
