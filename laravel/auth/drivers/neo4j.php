<?php namespace Laravel\Auth\Drivers;

use Laravel\Hash,
	Laravel\Config,
	VD\Member,
	VD\Database as DB;

class Neo4j extends Driver {

	/**
	 * Get the current user of the application.
	 *
	 * If the user is a guest, null should be returned.
	 *
	 * @param  int         $id
	 * @return mixed|null
	 */
	public function retrieve($id)
	{
		try {
			if (filter_var($id, FILTER_VALIDATE_INT) !== false)
			{
				$member = Member::getById($id);
				
				// ----------------------------------------------------
				// TODO: сделать более приличную обработку исключений
				// ----------------------------------------------------

				if ($member == NULL) {
					throw new \Exception("Узел с указанным ID не найден");
				}

				return $member;
			}
		} catch(\Exception $e) {
			echo $e->getMessage();
			return NULL;
		}
	}

	/**
	 * Attempt to log a user into the application.
	 *
	 * @param  array  $arguments
	 * @return void
	 */
	public function attempt($arguments = array())
	{
		try {	
			$member = Member::getByIndex('login', $arguments['username']);
			if (!$member) {
				throw new \Exception('Пользователь не найден');
			}
			if (! is_null($member) && Hash::check( $arguments['password'], $member->get('password') ) )
			{
				return $this->login($member->getId(), 0);//$arguments['remember']
			}
		} catch(\Exception $e) {
			echo $e->getMessage();
			return false;
		}
	}
}




