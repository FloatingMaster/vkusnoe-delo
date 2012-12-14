<?php namespace Laravel\Auth\Drivers;

use Laravel\Hash,
	Laravel\Config,
	VD\User,
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
				$node = DB::client()->getNode($id);

				// ----------------------------------------------------
				// TODO: сделать более приличную обработку исключений
				// ----------------------------------------------------

				if ($node == NULL) {
					throw new \Exception("Узел с указанным ID не найден");
				}
				if ($node->getProperty('type') !== 'user') {
					throw new \Exception("Тип запрашиваемого узла [".$node->getProperty('type')."] неверен (требуется тип user)");
				}
				return new User($node);
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
		$handle = Config::get('auth.username');
		$password_field = Config::get('auth.password', 'password');

		//Производим поиск пользователя по индексу
		$user = User::getByIndex($handle, $arguments['handle']);
		if (! is_null($user) && Hash::check( $arguments['password'], $user->getProperty($password_field) ) )
		{
			return $this->login($user->getId(), $arguments['remember']);
		}

		return false;
	}
}




