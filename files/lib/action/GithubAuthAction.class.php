<?php
namespace wcf\action;
use wcf\data\user\option\UserOption;
use wcf\data\user\User;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\SystemException;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\util\HTTPRequest;
use wcf\util\JSON;
use wcf\util\StringUtil;

/**
 * Handles github auth.
 * 
 * @author	Tim Duesterhus
 * @copyright	2001-2013 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	action
 * @category	Community Framework
 */
class GithubAuthAction extends AbstractAction {
	/**
	 * @see	wcf\action\AbstractAction::$neededModules
	 */
	public $neededModules = array('GITHUB_PUBLIC_KEY', 'GITHUB_PRIVATE_KEY');
	
	/**
	 * @see	wcf\action\IAction::execute()
	 */
	public function execute() {
		parent::execute();
		
		// user accepted the connection
		if (isset($_GET['code'])) {
			try {
				// fetch access_token
				$request = new HTTPRequest('https://github.com/login/oauth/access_token', array(), array(
					'client_id' => GITHUB_PUBLIC_KEY,
					'client_secret' => GITHUB_PRIVATE_KEY,
					'code' => $_GET['code']
				));
				$request->execute();
				$reply = $request->getReply();
				
				$content = $reply['body'];
			}
			catch (SystemException $e) {
				throw new IllegalLinkException();
			}
			
			parse_str($content, $data);
			
			// check whether the token is okay
			if (isset($data['error'])) throw new IllegalLinkException();
			
			// check whether a user is connected to this github account
			$user = $this->getUser($data['access_token']);
			
			if ($user->userID) {
				// a user is already connected, but we are logged in, break
				if (WCF::getUser()->userID) {
					throw new NamedUserException(WCF::getLanguage()->get('wcf.user.3rdparty.github.connect.error.inuse'));
				}
				// perform login
				else {
					WCF::getSession()->changeUser($user);
					WCF::getSession()->update();
					HeaderUtil::redirect(LinkHandler::getInstance()->getLink());
				}
			}
			else {
				try {
					// fetch userdata
					$request = new HTTPRequest('https://api.github.com/user?access_token='.$data['access_token']);
					$request->execute();
					$reply = $request->getReply();
					$userData = JSON::decode(StringUtil::trim($reply['body']));
				}
				catch (SystemException $e) {
					throw new IllegalLinkException();
				}
				
				// save data for connection
				if (WCF::getUser()->userID) {
					WCF::getSession()->register('__githubUsername', $userData['login']);
					WCF::getSession()->register('__githubToken', $data['access_token']);
					
					HeaderUtil::redirect(LinkHandler::getInstance()->getLink('AccountManagement').'#3rdParty');
				}
				// save data and redirect to registration
				else {
					WCF::getSession()->register('__githubData', $userData);
					WCF::getSession()->register('__username', $userData['login']);
					
					// check whether user has entered a public email
					if (isset($userData) && isset($userData['email']) && $userData['email'] !== null) {
						WCF::getSession()->register('__email', $userData['email']);
					}
					// fetch emails via api
					else {
						try {
							$request = new HTTPRequest('https://api.github.com/user/emails?access_token='.$data['access_token']);
							$request->execute();
							$reply = $request->getReply();
							$emails = JSON::decode(StringUtil::trim($reply['body']));
							
							// handle future response as well a current response (see. http://developer.github.com/v3/users/emails/)
							if (is_string($emails[0])) {
								$email = $emails[0];
							}
							else {
								$email = $emails[0]['email'];
								foreach ($emails as $tmp) {
									if ($tmp['primary']) $email = $tmp['email'];
									break;
								}
							}
							WCF::getSession()->register('__email', $email);
						}
						catch (SystemException $e) { }
					}
					
					WCF::getSession()->register('__githubToken', $data['access_token']);
					
					// we assume that bots won't register on github first
					WCF::getSession()->register('recaptchaDone', true);
					
					WCF::getSession()->update();
					HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Register'));
				}
			}
			
			$this->executed();
			exit;
		}
		// user declined or any other error that may occur
		if (isset($_GET['error'])) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.user.3rdparty.github.login.error.'.$_GET['error']));
		}
		
		// start auth by redirecting to github
		HeaderUtil::redirect("https://github.com/login/oauth/authorize?client_id=".rawurlencode(GITHUB_PUBLIC_KEY)."&scope=".rawurlencode('user:email'));
		$this->executed();
		exit;
	}
	
	/**
	 * Fetches the User with the given access-token.
	 * 
	 * @param	string			$token
	 * @return	wcf\data\user\User
	 */
	public function getUser($token) {
		$sql = "SELECT	userID
			FROM	wcf".WCF_N."_user
			WHERE	authData = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array('github:'.$token));
		$row = $statement->fetchArray();
		
		if ($row === false) {
			$row = array('userID' => 0);
		}
		
		$user = new User($row['userID']);
		return $user;
	}
}
