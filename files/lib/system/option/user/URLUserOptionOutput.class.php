<?php
namespace wcf\system\option\user;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\style\StyleHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * User option output implementation for the output of an url.
 *
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.option.user
 * @category	Community Framework
 */
class URLUserOptionOutput implements IUserOptionOutput {
	/**
	 * @see	wcf\system\option\user\IUserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, UserOption $option, $value) {
		return $this->getImage($user, $value, 'S');
	}
	
	/**
	 * @see	wcf\system\option\user\IUserOptionOutput::getMediumOutput()
	 */
	public function getMediumOutput(User $user, UserOption $option, $value) {
		return $this->getImage($user, $value);
	}
	
	/**
	 * @see	wcf\system\option\user\IUserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, UserOption $option, $value) {
		if (empty($value) || $value == 'http://') return '';
		
		$value = self::getURL($value);
		$value = StringUtil::encodeHTML($value);
		return '<a href="'.$value.'" class="externalURL"'.(EXTERNAL_LINK_REL_NOFOLLOW ? ' rel="nofollow"' : '').(EXTERNAL_LINK_TARGET_BLANK ? ' target="_blank"' : '').'>'.$value.'</a>';
	}
	
	/**
	 * Generates an image button.
	 * 
	 * @see	wcf\system\option\user\IUserOptionOutput::getShortOutput()
	 */
	protected function getImage(User $user, $value, $imageSize = 'M') {
		if (empty($value) || $value == 'http://') return '';
		
		$value = self::getURL($value);
		$title = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.homepage.title', array('username' => StringUtil::encodeHTML($user->username)));
		return '<a href="'.StringUtil::encodeHTML($value).'" class="externalURL"'.(EXTERNAL_LINK_REL_NOFOLLOW ? ' rel="nofollow"' : '').(EXTERNAL_LINK_TARGET_BLANK ? ' target="_blank"' : '').'><img src="'.StyleHandler::getInstance()->getStyle()->getIconPath('globe').'" alt="" title="'.$title.'" /></a>';
	}
	
	/**
	 * Formats the URL.
	 * 
	 * @param	string		$url
	 * @return	string
	 */
	private static function getURL($url) {
		if (!preg_match('~^https?://~i', $url)) {
			$url = 'http://'.$url;
		}
		
		return $url;
	}
}
