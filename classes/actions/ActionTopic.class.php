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

class PluginMagicrule_ActionTopic extends PluginMagicrule_Inherit_ActionTopic {

	protected function EventAdd() {
		if (true===$mRes=$this->PluginMagicrule_Main_CheckRuleAction('create_topic',$this->oUserCurrent)) {
			return parent::EventAdd();
		} else {
			if (is_string($mRes)) {
				$this->Message_AddErrorSingle($mRes,$this->Lang_Get('attention'));
				return Router::Action('error');
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('plugin.magicrule.check_rule_action_error'),$this->Lang_Get('attention'));
				return Router::Action('error');
			}
		}
	}
}
