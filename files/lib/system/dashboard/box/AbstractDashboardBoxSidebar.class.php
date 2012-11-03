<?php
namespace wcf\system\dashboard\box;

/**
 * Default implementation for dashboard boxes displayed within the sidebar container.
 * 
 * @author	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.dashboard.box
 * @category	Community Framework
 */
abstract class AbstractDashboardBoxSidebar extends AbstractDashboardBoxContent {
	/**
	 * @see	wcf\system\dashboard\box\AbstractDashboardBoxContent::$templateName
	 */
	public $templateName = 'dashboardBoxSidebar';
}
