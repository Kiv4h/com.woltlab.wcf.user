<?php
namespace wcf\data\user\avatar;
use wcf\data\DatabaseObject;
use wcf\util\StringUtil;

/**
 * Represents a user's avatar.
 *
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	data.user.avatar
 * @category 	Community Framework
 */
class UserAvatar extends DatabaseObject implements IUserAvatar {
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'user_avatar';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'avatarID';
	
	/**
	 * @see	wcf\data\user\avatar\IUserAvatar::getURL()
	 */
	public function getURL($size = null) {
		return RELATIVE_WCF_DIR . 'images/avatars/avatar-' . $this->avatarID . '.' . StringUtil::encodeHTML($this->avatarExtension);
	}
	
	/**
	 * @see	wcf\data\user\avatar\IUserAvatar::getImageTag()
	 */
	public function getImageTag($size = null) {
		$width = $this->width;
		$height = $this->height;
		if ($size !== null) {
			if ($this->width > $size || $this->height > $size) {
				$widthFactor = $size / $this->width;
				$heightFactor = $size / $this->height;
				
				if ($widthFactor < $heightFactor) {
					$width = $size;
					$height = round($this->height * $widthFactor, 0);
				}
				else {
					$width = round($this->width * $heightFactor, 0);
					$height = $size;
				}
			}
		}
		
		return '<img src="'.$this->getURL($size).'" style="width: '.$width.'px; height: '.$height.'px" alt="" />';
	}
	
	/**
	 * @see	wcf\data\user\avatar\IUserAvatar::setMaxHeight()
	 */
	/*public function setMaxHeight($maxHeight) {
		if ($this->height > $maxHeight) {
			$this->data['width'] = round($this->width * $maxHeight / $this->height, 0);
			$this->data['height'] = $maxHeight;
			return true;
		}
		
		return false;
	}*/
	
	/**
	 * @see	wcf\data\user\avatar\IUserAvatar::setMaxSize()
	 */
	/*public function setMaxSize($maxWidth, $maxHeight) {
		if ($this->width > $maxWidth || $this->height > $maxHeight) {
			$widthFactor = $maxWidth / $this->width;
			$heightFactor = $maxHeight / $this->height;
			
			if ($widthFactor < $heightFactor) {
				$this->data['width'] = $maxWidth;
				$this->data['height'] = round($this->height * $widthFactor, 0);
			}
			else {
				$this->data['width'] = round($this->width * $heightFactor, 0);
				$this->data['height'] = $maxHeight;
			}
			
			return true;
		}
		
		return false;
	}*/
	
	/**
	 * @see	wcf\data\user\avatar\IUserAvatar::getWidth()
	 */
	public function getWidth() {
		return $this->width;
	}
	
	/**
	 * @see	wcf\data\user\avatar\IUserAvatar::getHeight()
	 */
	public function getHeight() {
		return $this->height;
	}
}
