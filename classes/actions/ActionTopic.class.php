<?php
/* -------------------------------------------------------
 *
 *   LiveStreet (v0.4.2)
 *   Plugin KeyCAPTCHA (v.1.0.0)
 *   Copyright © 2011 Yelizarov Alexei aka beauty_free
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://devall.ru
 *   Contact e-mail: felex-ae@ya.ru
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
