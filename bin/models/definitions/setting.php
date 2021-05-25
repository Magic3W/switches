<?php namespace definitions;

use EnumField;
use IntegerField;
use Reference;
use spitfire\Model;
use spitfire\storage\database\Schema;
use StringField;
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

class SettingModel extends Model
{
	
	/**
	 * 
	 * @param Schema $schema
	 * @return Schema
	 */
	public function definitions(Schema $schema) {
		
		$schema->node   = new Reference(NodeModel::class);
		$schema->index($schema->node);
		
		/**
		 * The key refers to settings, since only settings provide actual meaning. Groups
		 * do not have keys since they contain no user defined settings.
		 * 
		 * When reading user data, the application can either receive a user's setting 
		 * dictionary, allowing overlays to modify these settings.
		 */
		$schema->key = new StringField(100);
		
		/**
		 * Allows the application to define an app id for the application that has
		 * read / write access to this setting. This prevents all other apps from
		 * using this setting.
		 */
		$schema->scope   = new IntegerField(true);
		
		
		/**
		 * 
		 */
		$schema->caption = new StringField(150);
		$schema->description = new TextField();
		
		/*
		 * The default allows the operator to define a default value for the setting
		 * that will be used if the user did not provide a value.
		 */
		$schema->default = new StringField(1024);
		$schema->type = new EnumField('string', 'enum', 'boolean', 'external', 'media');
		
		/**
		 * Contains type based settings, for example, an enum type will have a 
		 * list of the options it provides - a media type can contain settings on
		 * which mime-types or sizes to allow.
		 */
		$schema->additional = new TextField();
		
		$schema->icon  = new StringField(200);
		$schema->secret  = new StringField(256);
	}

}