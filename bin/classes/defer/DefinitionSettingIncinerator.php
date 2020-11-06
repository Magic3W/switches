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

/**
 * When a group is incinerated, the system needs to perform checks before it can
 * start deleting the task proper.
 */
class DefinitionSettingIncinerator extends Task
{
	
	public function body(): Result {
		$settingid = $this->getSettings();
		$setting = db()->table('definitions\setting')->get('_id', $settingid)->first();
		
		if (!$setting) {
			return new Result('The setting was already incinerated');
		}
		
		$usersettings = db()->table('setting')->get('setting', $setting)->range(0, 10000);
		
		/*
		 * Loop over all user settings and delete the settings.
		 */
		foreach ($usersettings as $usersetting) {
			$usersetting->delete();
		}
		
		/*
		 * If all the user settings are deleted, we can permanently remove the setting,
		 * otherwise we will have to continue deleting data until the system is ready to 
		 * delete the definition.
		 */
		if ($usersettings->isEmpty()) {
			$setting->delete();
			return new Result('Setting deleted');
		}
		else {
			\spitfire\core\async\Async::defer(0, $this);
			return new Result('Deleted user settings for this, requeued');
		}
	}

}
