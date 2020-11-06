<?php

use spitfire\Model;
use spitfire\storage\database\Schema;


class DisplayNameModel extends Model
{
	
	/**
	 * A display name is a string that a user can define to add flair to their profile,
	 * unlike the username (which identifies them uniquely), the display name is 
	 * way more lenient in the restrictions:
	 * 
	 * - It can be duplicated
	 * - It can contain spaces, Unicode, and any series of special characters
	 * 
	 * @param Schema $schema
	 * @return Schema
	 */
	public function definitions(Schema $schema) {
		$schema->user    = new Reference(UserModel::class);
		$schema->name    = new StringField(120);
		$schema->created = new IntegerField(true);
		$schema->expires = new IntegerField(true);
	}
	
	public function onbeforesave() {
		if (!$this->created) {
			$this->created = time();
		}
	}

}