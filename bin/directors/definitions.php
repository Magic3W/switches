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

class DefinitionsDirector extends spitfire\mvc\Director
{
	
	public function import($url) {
		
		$payload = request($url)->send()->expect(200)->json();
		
		foreach ($payload->definitions as $node) {
			/*
			 * Find the node that the system is reading from.
			 */
			$existing = db()->table('definitions\node')->get('key', $node->key)->first()?: db()->table('definitions\node')->newRecord();
			
			if (!$existing) {
				#Create the node
			}
			
			else {
				#Update the node, perform health-checks
				#Check if the type of the node matches
			}
			
			//Shared behavior. Affects both new records and existing ones
			#Update the parent
			
			#Set the source
			
			#Group behavior
			#Setting behavior
		}
	}
	
}
