<?php
namespace wcf\page;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\menu\page\PageMenu;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows the list team members.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	page
 * @category	Community Framework
 */
class TeamPage extends MultipleLinkPage {
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('user.profile.canViewMembersList');

	/**
	 * @see	wcf\page\AbstractPage::$neededModules
	 */
	public $neededModules = array('MODULE_TEAM_PAGE');
	
	/**
	 * @see	wcf\page\AbstractPage::$enableTracking
	*/
	public $enableTracking = true;

	/**
	 * @see	wcf\page\MultipleLinkPage::$itemsPerPage
	 */
	public $itemsPerPage = 1000;

	/**
	 * @see	wcf\page\MultipleLinkPage::$sortField
	 */
	public $sortField = MEMBERS_LIST_DEFAULT_SORT_FIELD;

	/**
	 * @see	wcf\page\MultipleLinkPage::$sortOrder
	 */
	public $sortOrder = MEMBERS_LIST_DEFAULT_SORT_ORDER;

	/**
	 * @see	wcf\page\MultipleLinkPage::$objectListClassName
	*/
	public $objectListClassName = 'wcf\data\user\TeamList';

	/**
	 * @see	wcf\page\IPage::show()
	 */
	public function show() {
		PageMenu::getInstance()->setActiveMenuItem('wcf.user.team');

		parent::show();
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
	
		// add breadcrumbs
		WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('wcf.user.members'), LinkHandler::getInstance()->getLink('MembersList')));
	}
}
