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
				if ($node == NULL) {
					throw new \Exception("Узел с указанным ID не найден");
				}
				if ($node->getProperty('type') !== 'user') {
					throw new \Exception("Тип запрашиваемого узла [".$node->getProperty('type')."] неверен (требуется тип user)");
				}
				return new User(DB::client(), $node);
			}
		} catch(\Exception $e) {
			echo $e->getMessage();
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
		
	}
}




