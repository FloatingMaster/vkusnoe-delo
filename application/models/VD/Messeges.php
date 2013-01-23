<?php
namespace VD;

use Everyman\Neo4j\Node,
	Everyman\Neo4j\Relationship,
    Everyman\Neo4j\Index;
	
class Messege
{	
	protected $node = null;
	
	public function privateMassege(Member $from, $recipients, $msg)
	{
		$node = new Node(DataBase::client());
		$node->addProperty('msg', $msg);
		$from->node->relateTo 
		foreach($recipients as $recipient) {
			$this->node->relateTo($recipient->node, 'PrivateMessege');
		}
	}
}