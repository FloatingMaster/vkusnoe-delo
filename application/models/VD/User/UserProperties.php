<?php namespace VD\User;

class UserProperties
{
	/**
	 * Имя пользователя
	 * @var string
	 */
	public $username;

	/**
	 * Email
	 * @var string
	 */
	public $email;

	/**
	 * Пароль
	 * @var string
	 */
	public $password;

	/**
	 * Разрешения
	 * @var Array
	 */
	public $capabilities = array();
}