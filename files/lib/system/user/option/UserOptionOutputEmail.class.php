<?php
namespace wcf\system\user\option;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\request\LinkHandler;
use wcf\system\style\StyleHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * UserOptionOutputEmail is an implementation of IUserOptionOutput for the output of a user email.
 *
 * @author	Marcel Werk
 * @copyright	2001-2011 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.user.option
 * @category 	Community Framework
 */
class UserOptionOutputEmail implements IUserOptionOutput, IUserOptionOutputContactInformation {
	/**
	 * @see wcf\system\user\option\IUserOptionOutput::getShortOutput()
	 */
	public function getShortOutput(User $user, UserOption $option, $value) {
		return $this->getImage($user, 'S');
	}
	
	/**
	 * @see wcf\system\user\option\IUserOptionOutput::getMediumOutput()
	 */
	public function getMediumOutput(User $user, UserOption $option, $value) {
		return $this->getImage($user);
	}
	
	/**
	 * @see wcf\system\user\option\IUserOptionOutput::getOutput()
	 */
	public function getOutput(User $user, UserOption $option, $value) {
		if (!$user->email) return '';
		if ($user->hideEmailAddress && !WCF::getSession()->getPermission('admin.user.canMailUser')) return '';
		if (!WCF::getSession()->getPermission('user.mail.canMail')) return '';
		$email = StringUtil::encodeAllChars($user->email);
		return '<a href="mailto:'.$email.'">'.$email.'</a>';
	}
	
	/**
	 * @see	wcf\system\user\option\IUserOptionOutputContactInformation::getOutput()
	 */
	public function getOutputData(User $user, UserOption $option, $value) {
		if (!$user->email) return null;
		if (!$user->hideEmailAddress || WCF::getSession()->getPermission('admin.user.canMailUser')) {
			$email = StringUtil::encodeAllChars($user->email);
			return array(
				'icon' => StyleManager::getInstance()->getStyle()->getIconPath('email', 'M'),
				'title' => WCF::getLanguage()->get('wcf.user.option.'.$option->optionName),
				'value' => $email,
				'url' => 'mailto:'.$email
			);
		}
		else if ($user->userCanMail && WCF::getSession()->getPermission('user.mail.canMail')) {
			return array(
				'icon' => StyleManager::getInstance()->getStyle()->getIconPath('email', 'M'),
				'title' => WCF::getLanguage()->get('wcf.user.option.'.$option->optionName),
				'value' => WCF::getLanguage()->getDynamicVariable('wcf.user.profile.email.title', array('username' => StringUtil::encodeHTML($user->username))),
				'url' => StringUtil::encodeHTML(LinkHandler::getInstance()->getLink('Mail', array('id' => $user->userID)))
			);
		}
		else {
			return null;
		}
	}
	
	/**
	 * Generates an image button.
	 * 
	 * @see wcf\system\user\option\IUserOptionOutput::getShortOutput()
	 */
	protected function getImage(User $user, $imageSize = 'M') {
		if (!$user->email) return '';
		if (!$user->hideEmailAddress || WCF::getSession()->getPermission('admin.user.canMailUser')) {
			$url = 'mailto:'.StringUtil::encodeAllChars($user->email);
		}
		else if ($user->userCanMail && WCF::getSession()->getPermission('user.mail.canMail')) {
			$url = StringUtil::encodeHTML(LinkHandler::getInstance()->getLink('Mail', array('id' => $user->userID)));
		}
		else {
			return '';
		}
		
		$title = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.email.title', array('username' => StringUtil::encodeHTML($user->username)));
		return '<a href="'.$url.'"><img src="'.StyleManager::getInstance()->getStyle()->getIconPath('email', $imageSize).'" alt="" title="'.$title.'" /></a>';
	}
}
?>
