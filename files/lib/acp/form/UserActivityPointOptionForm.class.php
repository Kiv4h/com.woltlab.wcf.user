<?php
namespace wcf\acp\form;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\object\type\ObjectTypeEditor;
use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\system\user\activity\point\UserActivityPointHandler;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use wcf\util\HeaderUtil;

/**
 * Provides the user activity point option form.
 * 
 * @author	Tim Düsterhus
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	acp.form
 * @category 	Community Framework
 */
class UserActivityPointOptionForm extends ACPForm {
	/**
	 * @see	wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.activityPoint';
	
	/**
	 * @see	wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('admin.user.canEditActivityPoints'); // TODO: Add permission
	
	/**
	 * points to objectType
	 * @var array<integer>
	 */
	public $points = array();
	
	/**
	 * valid object types
	 * @var array<wcf\data\object\type\ObjectType>
	 */
	public $objectTypes = array();
	
	/**
	 * @see	wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['points']) && is_array($_POST['points'])) $this->points = ArrayUtil::toIntegerArray($_POST['points']);
	}
	
	/**
	 * @see	wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		foreach ($this->points as $objectTypeID => $points) {
			if ($points < 0) throw new UserInputException($objectTypeID, 'invalid');
		}
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		$this->objectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.activityPointEvent');
		if (empty($_POST)) {
			foreach ($this->objectTypes as $objectType) {
				$this->points[$objectType->objectTypeID] = $objectType->points;
			}
		}
		
		parent::readData();
	}
	
	/**
	 * @see	wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		foreach ($this->objectTypes as $objectType) {
			if (!isset($this->points[$objectType->objectTypeID])) continue;
			$editor = new ObjectTypeEditor($objectType);
			$data = $objectType->additionalData;
			$data['points'] = $this->points[$objectType->objectTypeID];
			$editor->update(array('additionalData' => serialize($data)));
		}
		
		ObjectTypeEditor::resetCache();
		
		$this->saved();
		
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'objectTypes' => $this->objectTypes,
			'points' => $this->points
		));
	}
}
