<?php

use spitfire\Model;
use spitfire\storage\database\Schema;

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

class URLModel extends Model
{
	
	/**
	 * 
	 * @param Schema $schema
	 * @return Schema
	 */
	public function definitions(Schema $schema) {
		$schema->user      = new Reference(UserModel::class);
		$schema->ordinal   = new IntegerField(true);
		$schema->url       = new StringField(255);
		$schema->secret    = new StringField(10);
		$schema->created   = new IntegerField(true);
		$schema->verified  = new IntegerField(true);
		$schema->requested = new IntegerField(true);
		$schema->removed   = new IntegerField(true);
	}
	
	public function onbeforesave(): void {
		parent::onbeforesave();
		
		if (!$this->created) {
			$this->created = time();
		}
		
		if (!$this->secret) {
			$this->secret = substr(base64_encode(random_bytes(5)), 0, 10);
		}
		
		if (!$this->ordinal) {
			# While this is not technically correct, it will ensure that the URL that
			# was created last, stays last.
			$this->ordinal = time();
		}
	}

}
