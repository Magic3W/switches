<?php namespace figure;

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

class SDK
{
	
	/**
	 *
	 * @var \auth\SSO
	 */
	private $sso;
	private $endpoint;
	private $appid;
	
	
	public function __construct($sso, $url) {
		$this->sso = $sso;
		
		$reflection = URLReflection::fromURL($url);
		$this->endpoint = rtrim($reflection->getProtocol() . '://' . $reflection->getServer() . ':' . $reflection->getPort() . $reflection->getPath(), '/');
		$this->appid    = $reflection->getUser();
	}
	
	public function uploadJS() {
		return rtrim($this->endpoint, '\/') . '/upload/create.js';
	}
	
	public function URL() {
		return rtrim($this->endpoint, '\/');
	}
	
	public function claim($id, $secret) {
		$request = request(sprintf('%s/upload/claim/%s/%s.json', $this->endpoint, $id, $secret));
		$request->get('signature', (string) $this->sso->makeSignature($this->appid));
		$request->send();
	}
	
	public function delete($id) {
		$request = request(sprintf('%s/upload/delete/%s.json', $this->endpoint, $id, $secret));
		$request->get('signature', (string) $this->sso->makeSignature($this->appid));
		$request->send();
	}
}
