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
 * Сущность
 *
 */
class PluginMagicrule_ModuleMain_EntityBlock extends EntityORM {

	protected function beforeSave() {
		if ($this->_isNew()) {
			$this->setDateCreate(date("Y-m-d H:i:s"));
		}
		return true;
	}

	public function setData($aData) {
		$this->_aData['data']=serialize($aData);
	}

	public function getData($sKey=null) {
		$aData=$this->_getDataOne('data');
		$aReturn=@unserialize($aData);
		if (is_null($sKey)) {
			if ($aReturn) {
				return $aReturn;
			}
			return array();
		} else {
			if ($aReturn and array_key_exists($sKey,$aReturn)) {
				return $aReturn[$sKey];
			} else {
				return null;
			}
		}
	}
}
?>