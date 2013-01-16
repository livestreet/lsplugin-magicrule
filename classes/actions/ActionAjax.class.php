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

class PluginMagicrule_ActionAjax extends PluginMagicrule_Inherit_ActionAjax {

	protected function EventVoteComment() {
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if (true===$mRes=$this->PluginMagicrule_Main_CheckRuleAction('vote_comment',$this->oUserCurrent,array('vote_value'=>(int)getRequest('value',null,'post')))) {
			return parent::EventVoteComment();
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

	protected function EventVoteTopic() {
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if (true===$mRes=$this->PluginMagicrule_Main_CheckRuleAction('vote_topic',$this->oUserCurrent,array('vote_value'=>(int)getRequest('value',null,'post')))) {
			return parent::EventVoteTopic();
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

	protected function EventVoteUser() {
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if (true===$mRes=$this->PluginMagicrule_Main_CheckRuleAction('vote_user',$this->oUserCurrent,array('vote_value'=>(int)getRequest('value',null,'post')))) {
			return parent::EventVoteUser();
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
