<?php
namespace wcf\acp\form;
use wcf\data\user\rank\UserRankAction;
use wcf\data\user\rank\UserRank;
use wcf\system\exception\IllegalLinkException;
use wcf\system\language\I18nHandler;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\WCF;

/**
 * Shows the user rank edit form.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	acp.form
 * @category	Community Framework
 */
class UserRankEditForm extends UserRankAddForm {
	/**
	 * @see	wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.user.rank.list';
	
	/**
	 * rank id
	 * @var	integer
	 */
	public $rankID = 0;
	
	/**
	 * rank object
	 * @var	wcf\data\user\rank\UserRank
	 */
	public $rank = null;
	
	/**
	 * @see	wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->rankID = intval($_REQUEST['id']);
		$this->rank = new UserRank($this->rankID);
		if (!$this->rank->rankID) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see	wcf\form\IForm::save()
	 */
	public function save() {
		ACPForm::save();
		
		$this->rankTitle = 'wcf.user.rank.userRank'.$this->rank->rankID;
		if (I18nHandler::getInstance()->isPlainValue('rankTitle')) {
			// @todo: PackageDependencyHandler > PackageCache
			I18nHandler::getInstance()->remove($this->rankTitle, PackageDependencyHandler::getInstance()->getPackageID('com.woltlab.wcf.user'));
			$this->rankTitle = I18nHandler::getInstance()->getValue('rankTitle');
		}
		else {
			// @todo: PackageDependencyHandler > PackageCache
			I18nHandler::getInstance()->save('rankTitle', $this->rankTitle, 'wcf.user', PackageDependencyHandler::getInstance()->getPackageID('com.woltlab.wcf.user'));
		}
		
		// update label
		$this->objectAction = new UserRankAction(array($this->rank), 'update', array('data' => array(
			'rankTitle' => $this->rankTitle,
			'cssClassName' => ($this->cssClassName == 'custom' ? $this->customCssClassName : $this->cssClassName),
			'groupID' => $this->groupID,
			'neededPoints' => $this->neededPoints,
			'rankImage' => $this->rankImage,
			'repeatImage' => $this->repeatImage,
			'gender' => $this->gender
		)));
		$this->objectAction->executeAction();
		$this->saved();
		
		// reset values if non-custom value was choosen
		if ($this->cssClassName != 'custom') $this->customCssClassName = '';
		
		// show success
		WCF::getTPL()->assign(array(
			'success' => true
		));
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (empty($_POST)) {
			// @todo: PackageDependencyHandler > PackageCache
			I18nHandler::getInstance()->setOptions('rankTitle', PackageDependencyHandler::getInstance()->getPackageID('com.woltlab.wcf.user'), $this->rank->rankTitle, 'wcf.user.rank.userRank\d+');
			$this->rankTitle = $this->rank->rankTitle;
			$this->cssClassName = $this->rank->cssClassName;
			if (!in_array($this->cssClassName, $this->availableCssClassNames)) {
				$this->customCssClassName = $this->cssClassName;
				$this->cssClassName = 'custom';
			}
			$this->groupID = $this->rank->groupID;
			$this->neededPoints = $this->rank->neededPoints;
			$this->gender = $this->rank->gender;
			$this->repeatImage = $this->rank->repeatImage;
			$this->rankImage = $this->rank->rankImage;
		}
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		I18nHandler::getInstance()->assignVariables(!empty($_POST));
		
		WCF::getTPL()->assign(array(
			'rankID' => $this->rankID,
			'rank' => $this->rank,
			'action' => 'edit'
		));
	}
}
