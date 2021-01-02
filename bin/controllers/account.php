<?php

use spitfire\core\http\AbsoluteURL;

class AccountController extends BaseController
{
	
	public function login () {
		$session = $this->session;
		
		if (isset($_GET['returnto']) && is_string($_GET['returnto']) && 
				Strings::startsWith($_GET['returnto'], '/') && !Strings::startsWith($_GET['returnto'], '//')) {
			$returnto = $_GET['returnto'];
		}
		else {
			$returnto = url('account');
		}
		
		if (isset($_GET['code'])) {
			
			if ($_GET['state'] !== $session->get('state')) {
				throw new PublicException('OAuth error: State did not match', 403);
			}
			
			$request = request('http://localhost/Auth/token/create.json');
			$request->post('code', $_GET['code']);
			$request->post('type', 'code');
			$request->post('client', $this->sso->getAppId());
			$request->post('secret', $this->sso->getSecret());
			$request->post('verifier', $session->get('verifier'));
			$response = $request->send()->expect(200)->json();
			
			$token = $this->sso->makeToken($response->tokens->access->token);
			$session->lock($token);
			
			$this->response->setBody('Redirect')->getHeaders()->redirect(url());
			return;
		}
		
		$state = base64_encode(random_bytes(10));
		$verifier = base64_encode(random_bytes(20));
		
		$session->set('state', $state);
		$session->set('verifier', $verifier);
		
		$url = sprintf('http://localhost/Auth/auth/oauth/?%s', http_build_query([
			'response_type' => 'code',
			'client' => $this->sso->getAppId(),
			'state'  => $state,
			'redirect' => strval(url('account', 'login')->absolute()),
			'challenge' => sprintf('%s:%s', 'sha256', hash('sha256', $verifier))
		]));
		
		header('location: ' . $url);
		die();
	}
	
	public function logout() {
		$this->session->destroy();
		$this->response->getHeaders()->redirect(url());
	}
	
}
