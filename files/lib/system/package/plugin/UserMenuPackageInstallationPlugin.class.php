<?php
namespace wcf\system\package\plugin;

/**
 * This PIP installs, updates or deletes user menu items.
 *
 * @author 	Alexander Ebert
 * @copyright	2001-2012 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.user
 * @subpackage	system.package.plugin
 * @category 	Community Framework
 */
class UserMenuPackageInstallationPlugin extends AbstractMenuPackageInstallationPlugin {
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::$className
	 */
	public $className = 'wcf\data\user\menu\item\UserMenuItemEditor';
	
	/**
	 * @see	wcf\system\package\plugin\AbstractPackageInstallationPlugin::$tableName
	 */
	public $tableName = 'user_menu_item';
	
	/**
	 * @see	wcf\system\package\plugin\AbstractXMLPackageInstallationPlugin::$tagName
	 */	
	public $tagName = 'usermenuitem';
}
