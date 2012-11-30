<?php
namespace wcf\data\user\notification;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\UserInputException;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\request\LinkHandler;
use wcf\system\user\notification\UserNotificationHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\WCF;

/**
 * Executes user notification-related actions.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.notification
 * @subpackage	data.user.notification
 * @category	Community Framework
 */
class UserNotificationAction extends AbstractDatabaseObjectAction {
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::create()
	 */
	public function create() {
		// create notification
		$notification = parent::create();
		
		// save recpients
		if (!empty($this->parameters['recipients'])) {
			$sql = "INSERT INTO	wcf".WCF_N."_user_notification_to_user
						(notificationID, userID, mailNotified)
				VALUES		(?, ?, ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			foreach ($this->parameters['recipients'] as $recipient) {
				$statement->execute(array($notification->notificationID, $recipient->userID, ($recipient->mailNotificationType == 'daily' ? 0 : 1)));
			}
		}
		
		return $notification;
	}
	
	/**
	 * Does nothing.
	 */
	public function validateLoad() { }
	
	/**
	 * Loads user notifications.
	 * 
	 * @return	array<array>
	 */
	public function load() {
		$returnValues = UserNotificationHandler::getInstance()->getNotifications();
		$returnValues['totalCount'] = UserNotificationHandler::getInstance()->getNotificationCount();
		
		// check if additional notifications are available
		if ($returnValues['count'] < $returnValues['totalCount']) {
			$returnValues['showAllLink'] = LinkHandler::getInstance()->getLink('NotificationList');
		}
		
		return $returnValues;
	}
	
	/**
	 * Validates if given notification id is valid for current user.
	 */
	public function validateMarkAsConfirmed() {
		// validate notification id
		if (!isset($this->parameters['notificationID'])) {
			throw new UserInputException('notificationID');
		}
		
		$sql = "SELECT	COUNT(*) AS count
			FROM	wcf".WCF_N."_user_notification_to_user
			WHERE	notificationID = ?
				AND userID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			$this->parameters['notificationID'],
			WCF::getUser()->userID
		));
		$row = $statement->fetchArray();
		
		if (!$row['count']) {
			throw new UserInputException('notificationID');
		}
	}
	
	/**
	 * Marks a notification as confirmed.
	 * 
	 * @return	array
	 */
	public function markAsConfirmed() {
		$sql = "DELETE FROM	wcf".WCF_N."_user_notification_to_user
			WHERE		notificationID = ?
					AND userID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
			$this->parameters['notificationID'],
			WCF::getUser()->userID
		));
		
		// reset notification count
		UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'userNotificationCount', PackageDependencyHandler::getInstance()->getPackageID('com.woltlab.wcf.user'));
		
		return array(
			'notificationID' => $this->parameters['notificationID'],
			'totalCount' => UserNotificationHandler::getInstance()->getNotificationCount()
		);
	}
}
