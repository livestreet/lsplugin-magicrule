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

class PluginMagicrule_ActionBlog extends PluginMagicrule_Inherit_ActionBlog {

	protected function SubmitComment() {
		/**
		 * Проверям авторизован ли пользователь
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if (true===$mRes=$this->PluginMagicrule_Main_CheckRuleAction('create_comment',$this->oUserCurrent)) {
			return parent::SubmitComment();
		} else {
			if (is_string($mRes)) {
				$this->Message_AddErrorSingle($mRes,$this->Lang_Get('attention'));
				return;
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('plugin.magicrule.check_rule_action_error'),$this->Lang_Get('attention'));
				return;
			}
		}
	}
}
