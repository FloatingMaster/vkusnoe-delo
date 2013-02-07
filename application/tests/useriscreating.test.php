<?php
use Laravel\Auth,
	Everyman\Neo4j\Node,
	VD\Member,
	VD\Database as DB;

class TestUserIsCreating extends PHPUnit_Framework_TestCase {

	public function test_process($value='')
	{
		for($id = 0; $id < 50; $id++) {
			$user = Auth::retrieve($id);
			$node = DB::client()->getNode($id);

			if($node == NULL) {
				$this->assertNull($user);
			} else {
				$this->assertEquals($node->type === 'user', $user instanceof Member);
				$this->assertEquals($node->id, $user->id);
				if ($user != NULL) {
					$this->assertInstanceOf('VD\Member', $user);
					$this->objectHasAttribute('properties', $user);
					$this->assertEquals($user->type, 'user');
				}
			}
		}
	}
}
