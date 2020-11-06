<?php

use spitfire\core\http\AbsoluteURL;

class AccountController extends BaseController
{
	
	public function login () {
		$sso = $this->sso;
		$rtt = isset($_GET['returnto']) && Strings::startsWith($_GET['returnto'], '/') && !Strings::startsWith($_GET['returnto'], '//');
		
		if ($this->user) {
			return $this->response->setBody('Redirecting...')
					->getHeaders()->redirect($rtt? $_GET['returnto'] : url('account'));
		}
		
		$this->session->lock($token = $sso->createToken(604800));
		$this->response->setBody('redirection')->getHeaders()->redirect($token->getRedirect((string) AbsoluteURL::current()));
		return;
	}
	
	public function logout() {
		$this->session->destroy();
		$this->response->getHeaders()->redirect(url());
	}
	
}
