<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
	die('Hacking attempt!');
}

class PluginMagicrule extends Plugin {

	protected $aInherits=array(
		'action' => array(
			'ActionAjax'=>'_ActionAjax',
		),
		'module' => array(
			'ModuleVote'=>'_ModuleVote',
		)
	);

	/**
	 * Активация плагина
	 */
	public function Activate() {
		if (!$this->isTableExists('prefix_magicrule_block')) {
			/**
			 * При активации выполняем SQL дамп
			 */
			$this->ExportSQL(dirname(__FILE__).'/dump.sql');
		}
		return true;
	}

	/**
	 * Инициализация плагина
	 */
	public function Init() {

	}
}
?>