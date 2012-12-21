<?php 
namespace wcf\form;
use wcf\acp\form\UserOptionListForm;
use wcf\data\search\SearchEditor;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\exception\UserInputException;
use wcf\system\menu\page\PageMenu;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\util\StringUtil;

/**
 * Shows the user search form.
 *
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	form
 * @category	Community Framework
 */
class UserSearchForm extends UserOptionListForm {
	/**
	 * username
	 * @var	string
	 */
	public $username = '';
	
	/**
	 * email address
	 * @var	string
	 */
	public $email = '';
	
	/**
	 * matches
	 * @var	array<integer>
	 */
	public $matches = array();
	
	/**
	 * condtion builder object
	 * @var	wcf\system\database\condition\PreparedStatementConditionBuilder
	 */
	public $conditions = null;
	
	/**
	 * search id
	 * @var	integer
	 */
	public $searchID = 0;
	
	/**
	 * number of results
	 * @var	integer
	 */
	public $maxResults = 1000;
	
	/**
	 * @see	wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
	
		if (isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
		if (isset($_POST['email'])) $this->email = StringUtil::trim($_POST['email']);
	}
	
	/**
	 * @see	wcf\acp\form\AbstractOptionListForm::initOptionHandler()
	 */
	protected function initOptionHandler() {
		$this->optionHandler->enableSearchMode();
		$this->optionHandler->init();
	}
	
	/**
	 * @see	wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->readOptionTree();
	}
	
	/**
	 * Reads option tree on page init.
	 */
	protected function readOptionTree() {
		$this->optionTree = $this->optionHandler->getOptionTree();
	}
	
	/**
	 * @see	wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
	
		WCF::getTPL()->assign(array(
			'username' => $this->username,
			'email' => $this->email,
			'optionTree' => $this->optionTree
		));
	}
	
	/**
	 * @see	wcf\page\IPage::show()
	 */
	public function show() {
		PageMenu::getInstance()->setActiveMenuItem('wcf.user.search');
		
		parent::show();
	}
	
	/**
	 * @see	wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
	
		// store search result in database
		$search = SearchEditor::create(array(
			'userID' => WCF::getUser()->userID,
			'searchData' => serialize(array('matches' => $this->matches)),
			'searchTime' => TIME_NOW,
			'searchType' => 'users'
		));
	
		// get new search id
		$this->searchID = $search->searchID;
		$this->saved();
	
		// forward to result page
		$url = LinkHandler::getInstance()->getLink('MembersList', array('id' => $this->searchID));
		HeaderUtil::redirect($url);
		exit;
	}
	
	/**
	 * @see	wcf\form\IForm::validate()
	 */
	public function validate() {
		AbstractForm::validate();
	
		// do search
		$this->search();
	
		if (empty($this->matches)) {
			throw new UserInputException('search', 'noMatches');
		}
	}
	
	/**
	 * Search for users which fit to the search values.
	 */
	protected function search() {
		$this->matches = array();
		$sql = "SELECT		user_table.userID
			FROM		wcf".WCF_N."_user user_table
			LEFT JOIN	wcf".WCF_N."_user_option_value option_value
			ON		(option_value.userID = user_table.userID)";
	
		// build search condition
		$this->conditions = new PreparedStatementConditionBuilder();
	
		// static fields
		$this->buildStaticConditions();
	
		// dynamic fields
		$this->buildDynamicConditions();
	
		// do search
		$statement = WCF::getDB()->prepareStatement($sql.$this->conditions, $this->maxResults);
		$statement->execute($this->conditions->getParameters());
		while ($row = $statement->fetchArray()) {
			$this->matches[] = $row['userID'];
		}
	}
	
	/**
	 * Builds the static conditions.
	 */
	protected function buildStaticConditions() {
		if (!empty($this->username)) {
			$this->conditions->add("user_table.username LIKE ?", array('%'.addcslashes($this->username, '_%').'%'));
		}
		if (!empty($this->email)) {
			$this->conditions->add("user_table.email LIKE ?", array('%'.addcslashes($this->email, '_%').'%'));
		}
	}

	/**
	 * Builds the dynamic conditions.
	 */
	protected function buildDynamicConditions() {
		foreach ($this->optionHandler->getCategoryOptions('profile') as $option) {
			$option = $option['object'];
				
			$value = isset($this->optionHandler->optionValues[$option->optionName]) ? $this->optionHandler->optionValues[$option->optionName] : null;
			$this->optionHandler->getTypeObject($option->optionType)->getCondition($this->conditions, $option, $value);
		}
	}
}
