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

class PluginMagicrule_ModuleVote extends PluginMagicrule_Inherit_ModuleVote {

	public function AddVote($oVote) {
		$bResult=parent::AddVote($oVote);
		if ($bResult) {
			$this->PluginMagicrule_Main_CheckForCreateBlockVote($oVote);
		}
		return $bResult;
	}
}
