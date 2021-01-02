<?php

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

/**
 * The block controller allows users to make use of a permission server to set
 * the permissions whether a certain user should be able to access their profile
 * or not.
 * 
 * The system will generate permission resources like this:
 * 
 * * _profile.{read|interact}.guest: * (global)
 * * _profile.{read|interact}.registered: * (global)
 * * _profile.user.{userid-blocker}.{read|interact}.guest: *
 * * _profile.user.{userid-blocker}.{read|interact}.registered: *(all registered users) | @{user-id} (exceptions)
 * * _profile.user.{userid-blocker}.{read|interact}
 * 
 * This allows the user granular control by allowing them to set permissions on a
 * global scope (app{appid}.profile.read.{userid-blocker}), for guests, or for 
 * individuals.
 * 
 * Depending on whether the user sets their root setting to grant or deny access,
 * the system will use the individual entries as grant lists.
 * 
 * The top level settings would be more akin to privacy settings and should be 
 * placed appropriately.
 */
class PrivacyController extends BaseController
{
	
	/**
	 * List users in your blacklist.
	 */
	public function index() {
		
	}
	
	/**
	 * 
	 * @param string $userid
	 */
	public function add($userid) {
		
	}
	
	/**
	 * 
	 * @param type $username
	 */
	public function remove($username) {
		
	}
	
}
