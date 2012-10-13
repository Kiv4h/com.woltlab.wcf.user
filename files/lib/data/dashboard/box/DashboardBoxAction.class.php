<?php
namespace wcf\data\dashboard\box;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\IPositionAction;
use wcf\system\dashboard\DashboardHandler;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\UserInputException;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\WCF;
use wcf\util\ArrayUtil;

/**
 * Executes dashboard box-related actions.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.dashboard.box
 * @category 	Community Framework
 */
class DashboardBoxAction extends AbstractDatabaseObjectAction implements IPositionAction {
	/**
	 * list of available dashboard boxes
	 * @var	array<wcf\data\dashboard\box\DashboardBox>
	 */
	public $boxes = array();
	
	/**
	 * box structure
	 * @var	array<integer>
	 */
	public $boxStructure = array();
	
	/**
	 * object type object
	 * @var	wcf\data\object\type\ObjectType
	 */
	public $objectType = null;
	
	/**
	 * @see	wcf\data\IPositionAction::validateUpdatePosition()
	 */
	public function validateUpdatePosition() {
		// validate permissions
		WCF::getSession()->checkPermissions(array('admin.content.dashboard.canEditDashboard'));
		
		// validate box type
		if (!isset($this->parameters['boxType'])) {
			throw new UserInputException('boxType');
		}
		else if (!in_array($this->parameters['boxType'], array('content', 'sidebar'))) {
			throw new UserInputException('boxType');
		}
		
		// validate object type
		if (isset($this->parameters['objectTypeID'])) {
			$objectType = ObjectTypeCache::getInstance()->getObjectType($this->parameters['objectTypeID']);
			if ($objectType !== null) {
				$objectTypeDefinition = ObjectTypeCache::getInstance()->getDefinitionByName('com.woltlab.wcf.user.dashboardContainer');
				if ($objectTypeDefinition !== null) {
					if ($objectType->definitionID == $objectTypeDefinition->definitionID) {
						$this->objectType = $objectType;
					}
				}
			}
		}
		if ($this->objectType === null) {
			throw new UserInputException('objectTypeID');
		}
		
		// parse structure
		if (isset($this->parameters['data']) & isset($this->parameters['data']['structure']) && isset($this->parameters['data']['structure'][0])) {
			$this->boxStructure = ArrayUtil::toIntegerArray($this->parameters['data']['structure'][0]);
			
			// validate box ids
			if (!empty($this->boxStructure)) {
				$boxList = new DashboardBoxList();
				$boxList->getConditionBuilder()->add("dashboard_box.packageID IN (?)", array(PackageDependencyHandler::getInstance()->getDependencies()));
				$boxList->sqlLimit = 0;
				$boxList->readObjects();
				$this->boxes = $boxList->getObjects();
				
				foreach ($this->boxStructure as $boxID) {
					if (!isset($this->boxes[$boxID])) {
						throw new UserInputException('boxID');
					}
				}
			}
		}
	}
	
	/**
	 * @see	wcf\data\IPositionAction::updatePosition()
	 */
	public function updatePosition() {
		// remove previous settings
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("objectTypeID = ?", array($this->objectType->objectTypeID));
		$conditions->add("boxID IN (?)", array(array_keys($this->boxes)));
		
		$sql = "DELETE FROM	wcf".WCF_N."_dashboard_option
			".$conditions;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getParameters());
		
		// update settings
		if (!empty($this->boxStructure)) {
			$sql = "INSERT INTO	wcf".WCF_N."_dashboard_option
						(objectTypeID, boxID, showOrder)
				VALUES		(?, ?, ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			
			WCF::getDB()->beginTransaction();
			foreach ($this->boxStructure as $index => $boxID) {
				$showOrder = $index + 1;
				
				$statement->execute(array(
					$this->objectType->objectTypeID,
					$boxID,
					$showOrder
				));
			}
			WCF::getDB()->commitTransaction();
		}
		
		// reset cache
		DashboardHandler::clearCache();
	}
}
