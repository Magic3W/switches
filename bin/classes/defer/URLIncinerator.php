<?php namespace defer;

use spitfire\core\async\Result;
use spitfire\core\async\Task;

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

class URLIncinerator extends Task
{
	
	const RETENTION = 86400 * 30;
	
	/**
	 * 
	 * @return Result
	 */
	public function body(): Result {
		$url = db()->table('url')->get('_id', $this->getSettings())->first(true);
		
		/*
		 * In case the URL was not yet removed, the system should log that the 
		 * URL was queued for incineration even though the removal was never requested.
		 * 
		 * This could be due to a bug or an administrative action reinstating the URL.
		 */
		if (!$url->removed) {
			return new Result('URL was not flagged as removed. Skipped.');
		}
		
		/*
		 * If the URL was not yet ready to be removed, because the retention policy
		 * expects a removed URL to be maintained for 30 days to allow for administrative
		 * investigations.
		 */
		if ($url->removed + self::RETENTION > time()) {
			\spitfire\core\async\Async::defer($url->removed + self::RETENTION, $this);
			return new Result('Policy expects URL to be kept. Defering');
		}
		
		/*
		 * If the URL was removed and the retention period has expired, we can safely
		 * remove the URL permanently.
		 */
		$url->delete();
	}

}
