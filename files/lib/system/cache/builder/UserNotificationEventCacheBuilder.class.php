<?php
namespace wcf\system\cache\builder;
use wcf\data\user\notification\event\UserNotificationEvent;
use wcf\system\WCF;

/**
 * Caches user notification events.
 *
 * @author	Marcell Werk, Oliver Kliebisch
 * @copyright	2001-2012 WoltLab GmbH, Oliver Kliebisch
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.notification
 * @subpackage	system.cache.builder
 * @category	Community Framework
 */
class UserNotificationEventCacheBuilder implements ICacheBuilder {
	/**
	 * @see	wcf\system\cache\ICacheBuilder::getData()
	 */
	public function getData(array $cacheResource) {
		$data = array();
		
		// get events
		$sql = "SELECT		event.*, object_type.objectType
			FROM		wcf".WCF_N."_user_notification_event event
			LEFT JOIN	wcf".WCF_N."_object_type object_type
			ON		(object_type.objectTypeID = event.objectTypeID)";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		while ($row = $statement->fetchArray()) {
			if (!isset($data[$row['objectType']])) {
				$data[$row['objectType']] = array();
			}
			
			if (!isset($data[$row['objectType']][$row['eventName']])) {
				$databaseObject = new UserNotificationEvent(null, $row);
				$data[$row['objectType']][$row['eventName']] = $databaseObject->getProcessor();
			}
		}
		
		return $data;
	}
}
