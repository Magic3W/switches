<?php

use definitions\SettingModel as ParentModel;
use figure\SDK as Figure;
use spitfire\core\Environment;
use spitfire\exceptions\PublicException;
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


class SettingModel extends Model
{
	
	/**
	 * 
	 * @param Schema $schema
	 */
	public function definitions(Schema $schema) {
		$schema->user = new Reference('user');
		
		/**
		 * The external vs internal indicates what the key for the application is 
		 * inside switches and what the application wishes to receive (outside switches)
		 * 
		 * The mappings IGNORE keys that are scoped (since they are explicitly tailored
		 * to that application)
		 */
		$schema->setting = new Reference(ParentModel::class);
		$schema->value   = new StringField(1024);
		
		/*
		 * Record when the setting was changed by the user.
		 */
		$schema->created = new IntegerField(true);
		$schema->updated = new IntegerField(true);
		
		
		$schema->index($schema->user, $schema->setting)->unique(true);
	}
	
	/**
	 * Magic set method. This is used to validate input to the value whenever 
	 * it is modified.
	 * 
	 * @todo This should be refactored into a proper validation mechanism, but it
	 * does the trick for now.
	 * @param type $field
	 * @param type $value
	 * @return type
	 * @throws PublicException	
 */
	public function __set($field, $value) 
	{
		#If the application is trying to write the value, we need to verify it's
		#a valid value
		if ($field === 'value') {
			switch ($this->setting->type) {
				case 'enum':
					$options = json_decode($this->setting->additional, true);
					if (array_search($value, array_keys($options)) === false) { throw new PublicException('Validation failed', 400); }
					break;
				case 'boolean':
					$value = !!$value;
					break;
				case 'media': 
					#TODO: Claim the upload
					$figure = new Figure($this->sso, Environment::get('figure'));
					$figure->delete(explode(':', $this->value)[0]);
					$figure->claim($value['figure'], $value['secret']);
					break;
				/*
				 * External data can only be set in the exact same manner as a string.
				 * Also note that the external should NOT be considered a safe location
				 * to place data into, the user may be able to manipulate this.
				 */
				case 'external':
				case 'string':
					if (!is_string($value)) { throw new PublicException('Validation failed. Only strings are allowed', 400); }
					break;
			}
		}
		
		return parent::__set($field, $value);
	}
	
	public function onbeforesave(): void {
		parent::onbeforesave();
		
		if (!$this->created) {
			$this->created = time();
		}
		
		$this->updated = time();
	}

}
