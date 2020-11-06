<?php namespace definitions;

use Reference;
use spitfire\io\beans\IntegerField;
use spitfire\Model;
use spitfire\storage\database\Schema;
use TextField;

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
 * Computed models allow certain settings to be marked as computed. This means that
 * the user can no longer edit them, and instead will need to edit the source.
 * 
 * A good example for computed settings would be a setting that several applications
 * can share, but is imported for each application individually. Like the content
 * rating they would like to see, applications may have safe, questionable and 
 * explicit, while others may just have adult and general.
 * 
 * This setting allows the administrator to define an override for an application
 * setting that has been imported and is therefore readonly.
 */
class ComputedModel extends Model
{
	
	/**
	 * 
	 * @param Schema $schema
	 * @return Schema
	 */
	public function definitions(Schema $schema) {
		
		$schema->app = new IntegerField(true);
		
		/**
		 * The external vs internal indicates what the key for the application is 
		 * inside switches and what the application wishes to receive (outside switches)
		 * 
		 * The mappings IGNORE keys that are scoped (since they are explicitly tailored
		 * to that application)
		 */
		$schema->source = new Reference(SettingModel::class);
		$schema->target = new Reference(SettingModel::class);
		
		/**
		 * Describes mappings to transform the content in the event of the content
		 * not being literally the same.
		 */
		$schema->transform = new TextField();
		
		$schema->hidden    = new \BooleanField();
	}

}