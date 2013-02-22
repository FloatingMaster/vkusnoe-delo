<?php
namespace VD;

use Everyman\Neo4j\Node,
	Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Index,
	Everyman\Neo4j\Cypher,
	Everyman\Neo4j\Batch,
	VD\Database as DB;
/**
 *	Класс контроля доступа. Даёт добро или запрет на выполнения запрошенного действия  
 */
class Access extends Node
{
	/*
	*	Запросить разрешение для действия
	*	@params string $act | Member $member
	*	@return bool
	*/
	public function askFor($act, $member = Auth::user()) // Придумай название получше)
	{
		$role = $this->getMemberRole($member);
		$perm = $role->getProperty($act);
		if (is_null($perm)) {
			$perm = $this->getDefault()->getProperty($act);
		}
		if ($perm) {
			return true;
		}
		return false;
	}
	
	/*
	*	Установить (или запретить) разрешение для действия
	*	@params string $role | string $act | bool $allow
	*	@return true or throw an Excaption
	*/
	public function setRolePerm($role, $act, $allow)
	{
		$node = $this->GetRole($role);
		if (!$node) {
			throw new Excaption('Role not found'); // можно просто создать пустую роль (Всё равно наследует дефолтную)
		}
		$node->setProperty($act, $allow);
		return true;
	}
	
	/*
	*	Новая роль, "наследуется" от стандартной.
	*	@params string $name | array $actions
	*	@return true
	*/
	public function NewRole($name, $actions)
	{
		$index = new Index\NodeIndex(DB::client(), 'Roles');
		$role = $index->findOne('Name', $name);
		if (!$role) {
			$role = new Node;
			$role->setProperty('Name', $name);
			$index->add($role, 'Name', $name);
			foreach($actions as $action) {
				$role->setProperty($action['act'], $action['allow']);
			}
			$role->relateTo($this->getDefault()); // дефолтная нода, несуществующие действия спроят с неё
			return true;
		}
		foreach($actions as $action) {
			$role->setProperty($action['act'], $action['allow']);
		}
		return true;
	}
	
	/*
	*	Получить ноду роли
	*	@params string $name
	*	@return Node or NULL
	*/
	protected function getRole($name)
	{
		/*
			if ($name = 'Default') {
				return NULL;
			}
		*/
		$index = new Index\NodeIndex(DB::client(), 'Roles');
		return $index->findOne('Name', $name);
	}
	
	/*
	*	Получить стандартную ноду.
	*	В ней мы храним основные действия, потом, по необходимости переопределяем или добавляем в остальных.
	*	
	*	@return Node
	*/
	protected function getDefault()
	{
		/* Notice!
		*	Можно загружать из конфигов, сейчас получаю по индексу, как все роли. 
		*/
		$index = new Index\NodeIndex(DB::client(), 'Roles');
		$default = $index->findOne('Name', 'Default');
		if (!$default) { // Default node not found. Ошибка или создать новую, такого не должно быть, так что исключение
			throw new Excaption('Default Role node not found!');
		}
		return $default;
	}
	
	
	
	
}