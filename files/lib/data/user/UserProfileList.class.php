<?php
namespace wcf\data\user;

/**
 * Represents a list of user profiles.
 * 
 * @author 	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user
 * @category 	Community Framework
 */
class UserProfileList extends UserList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$sqlOrderBy
	 */
	public $sqlOrderBy = 'user_table.username';
	
	/**
	 * decorator class name
	 * @var string
	 */
	public $decoratorClassName = 'wcf\data\user\UserProfile';
	
	/**
	 * Creates a new UserProfileList object.
	 */
	public function __construct() {
		parent::__construct();
		
		if (!empty($this->sqlSelects)) $this->sqlSelects .= ',';
		$this->sqlSelects .= "user_avatar.*";
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user_avatar user_avatar ON (user_avatar.avatarID = user_table.avatarID)";
	}
	
	/**
	 * @see	wcf\data\DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		if ($this->objectIDs === null) $this->readObjectIDs();
		parent::readObjects();
		
		foreach ($this->objects as $userID => $user) {
			$this->objects[$userID] = new $this->decoratorClassName($user);
		}
	}
}
