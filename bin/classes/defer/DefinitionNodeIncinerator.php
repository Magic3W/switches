<?php namespace defer;

use spitfire\core\async\Async;
use spitfire\core\async\Result;
use spitfire\core\async\Task;
use function db;

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
class DefinitionNodeIncinerator extends Task
{
	
	public function body(): Result {
		$nodeid = $this->getSettings();
		$node   = db()->table('definitions\node')->get('_id', $nodeid)->first();
		
		if (!$node) {
			return new Result('The node was already incinerated');
		}
		
		$children = db()->table('definitions\node')->get('parent', $node)->all();
		
		$group = db()->table('definitions\group')->get('node', $node)->first();
		$setting = db()->table('definitions\setting')->get('node', $node)->first();
		
		if ($group) {
			#Incinerate the group
			Async::defer(0, new DefinitionGroupIncinerator($group->_id));
			Async::defer(3600, $this);
			return new Result('Node contains a group. Deferred!');
		} 
		elseif ($setting) {
			#Incinerate the setting
			Async::defer(0, new DefinitionSettingIncinerator($setting->_id));
			Async::defer(3600, $this);
			return new Result('Node contains a setting. Deferred!');
		}
		else {
			#The node is already empty
		}
		
		foreach ($children as $child) {
			#Incinerate the child
			Async::defer(0, new DefinitionNodeIncinerator($child->_id));
		}
		
		if ($children->isEmpty()) {
			$node->delete();
			return new Result('Node was empty. Deleted!');
		}
		else {
			Async::defer(3600, $this);
			return new Result('Node has children. Deferred!');
		}
	}

}
