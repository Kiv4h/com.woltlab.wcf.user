<?php
namespace wcf\data\user\profile\menu\item;
use wcf\data\DatabaseObject;
use wcf\system\exception\SystemException;
use wcf\util\ClassUtil;

/**
 * Represents an user profile menu item.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.profile.menu.item
 * @category 	Community Framework
 */
class UserProfileMenuItem extends DatabaseObject {
	/**
	 * content manager
	 * @var	wcf\system\menu\user\profile\content\IUserProfileContent
	 */
	protected $contentManager = null;
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'user_profile_menu_item';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'menuItemID';
	
	/**
	 * Returns the item identifier, dots are replaced by underscores.
	 * 
	 * @return	string
	 */
	public function getIdentifier() {
		return str_replace('.', '_', $this->menuItem);
	}
	
	/**
	 * Returns the content manager for this menu item.
	 * 
	 * @return	wcf\system\menu\user\profile\content\IUserProfileMenuContent
	 */
	public function getContentManager() {
		if ($this->contentManager === null) {
			if (!class_exists($this->className)) {
				throw new SystemException("Unable to find class '".$this->className."'");
			}
			
			if (!ClassUtil::isInstanceOf($this->className, 'wcf\system\menu\user\profile\content\IUserProfileMenuContent')) {
				throw new SystemException("'".$this->className."' should implement wcf\system\menu\user\profile\content\IUserProfileMenuContent");
			}
			
			$this->contentManager = new $this->className();
		}
		
		return $this->contentManager;
	}
}
