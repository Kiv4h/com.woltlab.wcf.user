<?php
namespace wcf\page;
use wcf\data\user\follow\UserFollowingList;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\menu\user\UserMenu;
use wcf\system\WCF;

/**
 * Shows the followed users page.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	page
 * @category	Community Framework
 */
class FollowedUsersPage extends AbstractPage {
	public $followedUsers = array();
	
	/**
	 * @see wcf\page\AbstractPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->followedUsers = new UserFollowingList();
		$this->followedUsers->sqlLimit = 100;
		$this->followedUsers->sqlOrderBy = "user_table.username ASC";
		$this->followedUsers->getConditionBuilder()->add("user_follow.userID = ?", array(WCF::getUser()->userID));
		$this->followedUsers->readObjects();
	}
	
	/**
	 * @see wcf\page\AbstractPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'count' => $this->followedUsers->countObjects(),
			'followedUsers' => $this->followedUsers
		));
	}
	
	/**
	 * @see wcf\page\Page::show()
	 */
	public function show() {
		if (!WCF::getUser()->userID) {
			throw new PermissionDeniedException();
		}
		
		// set active tab
		UserMenu::getInstance()->setActiveMenuItem('wcf.user.menu.community.followedUsers');
		
		parent::show();
	}
}
