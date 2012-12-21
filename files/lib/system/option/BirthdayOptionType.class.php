<?php
namespace wcf\system\option;
use wcf\data\option\Option;
use wcf\data\user\User;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;
use wcf\util\DateUtil;

/**
 * Option type implementation for birthday input fields.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.option
 * @category	Community Framework
 */
class BirthdayOptionType extends DateOptionType {
	/**
	 * @see	wcf\system\option\ISearchableUserOption::getSearchFormElement()
	 */
	public function getSearchFormElement(Option $option, $value) {
		$ageFrom = $ageTo = '';
		if (!empty($value['ageFrom'])) $ageFrom = intval($value['ageFrom']);
		if (!empty($value['ageTo'])) $ageTo = intval($value['ageTo']);
		
		WCF::getTPL()->assign(array(
			'option' => $option,
			'valueAgeFrom' => $ageFrom,
			'valueAgeTo' => $ageTo
		));
		return WCF::getTPL()->fetch('birthdaySearchableOptionType');
	}
	
	/**
	 * @see	wcf\system\option\ISearchableUserOption::getCondition()
	 */
	public function getCondition(PreparedStatementConditionBuilder &$conditions, Option $option, $value) {
		if (empty($value['ageFrom']) || empty($value['ageTo'])) return false;
		
		$ageFrom = intval($value['ageFrom']);
		$ageTo = intval($value['ageTo']);
		if (!$ageFrom || !$ageTo) return false;
		
		$dateFrom = DateUtil::getDateTimeByTimestamp(TIME_NOW)->sub(new \DateInterval('P'.($ageTo + 1).'Y'))->add(new \DateInterval('P1D'));
		$dateTo = DateUtil::getDateTimeByTimestamp(TIME_NOW)->sub(new \DateInterval('P'.$ageFrom.'Y'));
		
		// @todo: check/fix postgresql support
		$conditions->add("option_value.userOption".User::getUserOptionID('birthdayShowYear')." = ? AND option_value.userOption".$option->optionID." BETWEEN DATE(?) AND DATE(?)", array(1, $dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d')));
		return true;
	}
}
