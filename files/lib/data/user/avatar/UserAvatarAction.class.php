<?php
namespace wcf\data\user\avatar;
use wcf\data\user\UserEditor;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\exception\ValidateActionException;
use wcf\system\image\ImageHandler;
use wcf\system\upload\AvatarUploadFileValidationStrategy;
use wcf\system\WCF;
use wcf\util\FileUtil;

define('MAX_AVATAR_WIDTH', 150);
define('MAX_AVATAR_HEIGHT', 150);

/**
 * Executes avatar-related actions.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.avatar
 * @category 	Community Framework
 */
class UserAvatarAction extends AbstractDatabaseObjectAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$className
	 */
	public $className = 'wcf\data\user\avatar\UserAvatarEditor';
	
	/**
	 * Validates the upload action.
	 */
	public function validateUpload() {
		// check upload permissions
		/*if (!WCF::getSession()->getPermission('user.profile.avatar.canUploadAvatar')) {
			throw new ValidateActionException('Insufficient permissions');
		}*/
		
		if (count($this->parameters['__files']->getFiles()) != 1) {
			throw new ValidateActionException('Invalid input');
		}
		
		// check max filesize, allowed file extensions etc.
		$this->parameters['__files']->validateFiles(new AvatarUploadFileValidationStrategy(1000000/*WCF::getSession()->getPermission('user.profile.avatar.maxSize')*/, array('jpg', 'png')/*explode('\n', WCF::getSession()->getPermission('user.profile.avatar.allowedFileExtensions'))*/));
	}
	
	/**
	 * Handles uploaded attachments.
	 */
	public function upload() {
		// save files
		$files = $this->parameters['__files']->getFiles();
		$file = $files[0];
		$result = array();
		
		if (!$file->getValidationErrorType()) {
			// shrink avatar if necessary
			$fileLocation = $file->getLocation();
			$imageData = getimagesize($fileLocation);
			if ($imageData[0] > MAX_AVATAR_WIDTH || $imageData[1] > MAX_AVATAR_HEIGHT) {
				// @todo: error handling
				$adapter = ImageHandler::getInstance()->getAdapter();
				$adapter->loadFile($fileLocation);
				$fileLocation = FileUtil::getTemporaryFilename();
				$thumbnail = $adapter->createThumbnail(MAX_AVATAR_WIDTH, MAX_AVATAR_HEIGHT, false);
				$adapter->writeImage($thumbnail, $fileLocation);
				$imageData = getimagesize($fileLocation);
			}
			
			$data = array(
				'avatarName' => $file->getFilename(),
				'avatarExtension' => $file->getFileExtension(),
				'width' => $imageData[0],
				'height' => $imageData[1],
				'userID' => WCF::getUser()->userID,
				'fileHash' => sha1_file($fileLocation)
			);
			
			// create avatar
			$avatar = UserAvatarEditor::create($data);
			
			// check avatar directory
			// and create subdirectory if necessary
			$dir = dirname($avatar->getLocation());
			if (!@file_exists($dir)) {
				@mkdir($dir, 0777);
			}
			
			// move uploaded file
			if (@copy($fileLocation, $avatar->getLocation())) {
				@unlink($fileLocation);
				
				// create thumbnails
				$action = new UserAvatarAction(array($avatar), 'generateThumbnails');
				$action->executeAction();
				
				// update user
				$userEditor = new UserEditor(WCF::getUser());
				$userEditor->update(array(
					'avatarID' => $avatar->avatarID
				));
				
				// return result
				return array(
					'errorType' => '',
					'url' => $avatar->getURL(96)
				);
			}
			else {
				// moving failed; delete avatar
				$editor = new UserAvatarEditor($avatar);
				$editor->delete();
				$file->setValidationErrorType('uploadFailed');
			}
		}
		
		return array('errorType' => $file->getValidationErrorType());
	}
	
	/**
	 * Generates thumbnails.
	 */
	public function generateThumbnails() {
		if (!count($this->objects)) {
			$this->readObjects();
		}
		
		foreach ($this->objects as $avatar) {
			$adapter = ImageHandler::getInstance()->getAdapter();
			$adapter->loadFile($avatar->getLocation());
			
			foreach (UserAvatar::$avatarThumbnailSizes as $size) {
				if ($avatar->width <= $size && $avatar->height <= $size) break 2;
				
				$thumbnail = $adapter->createThumbnail($size, $size, false);
				$adapter->writeImage($thumbnail, $avatar->getLocation($size));
			}
		}
	}
}
