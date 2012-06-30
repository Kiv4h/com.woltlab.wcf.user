<?php
namespace wcf\system\dashboard\box;
use wcf\data\dashboard\box\DashboardBoxList;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\cache\builder\ICacheBuilder;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\WCF;

/**
 * Caches user dashboard boxes.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.cache.builder
 * @category 	Community Framework
 */
class DashboardBoxCacheBuilder implements ICacheBuilder {
	/**
	 * @see wcf\system\cache\ICacheBuilder::getData()
	 */
	public function getData(array $cacheResource) {
		$data = array(
			'boxes' => array(),
			'pages' => array()
		);
		
		// load boxes
		$boxList = new DashboardBoxList();
		$boxList->getConditionBuilder()->add("box.packageID IN (?)", array(PackageDependencyHandler::getInstance()->getDependencies()));
		$boxList->sqlLimit = 0;
		$boxList->sqlOrderBy = "box.showOrder ASC";
		$boxList->readObjects();
		
		foreach ($boxList as $box) {
			$data['boxes'][$box->boxID] = $box;
		}
		
		// load settings
		$objectTypes = ObjectTypeCache::getInstance()->getObjectTypes('com.woltlab.wcf.user.dashboardContainer');
		$objectTypeIDs = array();
		foreach ($objectTypes as $objectType) {
			$objectTypeIDs[] = $objectTypes->objectTypeID;
		}
		
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("objectTypeID (?)", array($objectTypeIDs));
		
		$sql = "SELECT	*
			FROM	wcf".WCF_N."_dashboard_option
			".$conditions;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getParameters());
		while ($row = $statement->fetchArray()) {
			if (!isset($data['pages'][$row['objectTypeID']])) {
				$data['pages'][$row['objectTypeID']] = array();
			}
			
			$data['pages'][$row['objectTypeID']][$row['boxID']] = $row['enabled'];
		}
		
		return $data;
	}
}
