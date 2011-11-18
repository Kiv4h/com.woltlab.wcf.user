<?php
namespace wcf\system\menu\user\profile\content;
use wcf\data\user\User;
use wcf\system\event\EventHandler;
use wcf\system\option\user\UserOptionHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Handles user profile overview content.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.menu.user.profile.content
 * @category 	Community Framework
 */
class OverviewUserProfileMenuContent extends SingletonFactory implements IUserProfileMenuContent {
	/**
	 * cache name
	 * @var string
	 */
	public $cacheName = 'user-option';
	
	/**
	 * cache class name
	 * @var string
	 */
	public $cacheClass = 'wcf\system\cache\builder\OptionCacheBuilder';
	
	public $categoryFilter = array(
		'profile.aboutMe',
		'profile.personal',
		'profile.contact'
	);
	
	public $optionHandler = null;
	
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		EventHandler::getInstance()->fireAction($this, 'init');
		
		$this->optionHandler = new UserOptionHandler($this->cacheName, $this->cacheClass, false, '', 'profile', false);
	}
	
	/**
	 * @see	wcf\system\menu\user\profile\content\IUserProfileMenuContent::getContent()
	 */
	public function getContent($userID) {
		// get options
		$user = new User($userID);
		
		$this->optionHandler->setUser($user);
		
		$options = array();
		foreach ($this->categoryFilter as $categoryName) {
			$userOptions = $this->optionHandler->getCategoryOptions($categoryName);
			if (!empty($userOptions)) {
				$options[$categoryName] = $userOptions;
			}
		}
		
		WCF::getTPL()->assign(array(
			'options' => $options
		));
		
		return WCF::getTPL()->fetch('userProfileOverview');
	}
}
