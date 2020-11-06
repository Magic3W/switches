<?php

use spitfire\Model;
use spitfire\storage\database\Schema;


class UserModel extends Model
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
	}
	
	
	public static function retrieve($userid) {
		$user = db()->table('user')->get('_id', $userid)->first();
		
		if (!$user) {
			$user = db()->table('user')->newRecord();
			$user->_id = $userid;
			$user->store();
		}
		
		return $user;
	}

}