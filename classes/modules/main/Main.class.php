<?php

class PluginMagicrule_ModuleMain extends ModuleORM {

	const BLOCK_TYPE_VOTE=1;
	const BLOCK_TYPE_CREATE=2;

	/**
	 * Список направлений голосований и их синонимы
	 *
	 * @var array
	 */
	protected $aVoteMirrow=array(1=>'up',-1=>'down',0=>'abstain');
	/**
	 * Объект маппера
	 *
	 */
	protected $oMapper;

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		parent::Init();
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}

	public function CheckRuleAction($sAction,$oUser,$aParams=array()) {
		if ($oUser->isAdministrator()) {
			return true;
		}
		/**
		 * Проверка на наличие блокировок
		 */
		list($iBlockType,$sBlockTarget)=$this->GetTypeAndTargetByAction($sAction);
		if (true!==$mRes=$this->CheckRuleBlock($iBlockType,$sBlockTarget,$oUser,$aParams)) {
			return $mRes ? $mRes : false;
		}

		/**
		 * Проверка на запрещающие правила
		 */
		$sMsg=(string)Config::Get('plugin.magicrule.rule_disallow.'.$sAction.'.msg');
		if ('NOT_FOUND_LANG_TEXT'!=$sMsgLang=$this->Lang_Get($sMsg)) {
			$sMsg=$sMsgLang;
		}
		$bSkip=false;
		$aType=(array)Config::Get('plugin.magicrule.rule_disallow.'.$sAction.'.type');
		if ($iBlockType==self::BLOCK_TYPE_VOTE and isset($aParams['vote_value']) and count($aType) and !in_array($this->aVoteMirrow[$aParams['vote_value']],$aType)) {
			$bSkip=true;
		}
		$aGroups=(array)Config::Get('plugin.magicrule.rule_disallow.'.$sAction.'.groups');
		if (!$bSkip and count($aGroups)) {
			foreach($aGroups as $aRule) {
				$bCheck=true;
				foreach($aRule as $sParam=>$mValue) {
					if (!$this->CheckRuleDisallowActionParam($sParam,$mValue,$oUser,$aParams)) {
						$bCheck=false;
						break;
					}
				}
				if ($bCheck) {
					return $sMsg ? $sMsg : false;
				}
			}
		}

		/**
		 * Проверка на разрешающие правила
		 */
		$aGroups=(array)Config::Get('plugin.magicrule.rule.'.$sAction.'.groups');
		if (!count($aGroups)) {
			return true;
		}
		$sMsg=(string)Config::Get('plugin.magicrule.rule.'.$sAction.'.msg');
		if ('NOT_FOUND_LANG_TEXT'!=$sMsgLang=$this->Lang_Get($sMsg)) {
			$sMsg=$sMsgLang;
		}
		foreach($aGroups as $aRule) {
			$bCheck=true;
			foreach($aRule as $sParam=>$mValue) {
				if (!$this->CheckRuleActionParam($sParam,$mValue,$oUser,$aParams)) {
					$bCheck=false;
					break;
				}
			}
			if ($bCheck) {
				return true;
			}
		}
		return $sMsg ? $sMsg : false;
	}

	public function GetTypeAndTargetByAction($sAction) {
		$aPath=explode('_',strtolower($sAction));
		if (isset($aPath[0]) and isset($aPath[1])) {
			$iBlockType=null;
			if ($aPath[0]=='vote') {
				$iBlockType=self::BLOCK_TYPE_VOTE;
			} elseif ($aPath[0]=='create') {
				$iBlockType=self::BLOCK_TYPE_CREATE;
			}
			return array($iBlockType,$aPath[1]);
		}
		return array(null,null);
	}

	public function CheckRuleBlock($iType,$sTarget,$oUser,$aParams=array()) {
		$aBlockItems=$this->GetBlockItemsByFilter(array(
			'user_id' => $oUser->getId(),
			'type' => $iType,
			'target' => $sTarget,
			'date_block >=' => date("Y-m-d H:i:s"),
										));
		/**
		 * Проверяем все действующие блокировки
		 */
		foreach($aBlockItems as $oBlock) {
			/**
			 * Проверяем на направление голосования
			 */
			if ($iType==self::BLOCK_TYPE_VOTE) {
				if (isset($aParams['vote_value']) and $aDirection=$oBlock->getData('direction')) {
					/**
					 * Если нужного направления голосования нет в списке, то пропускаем блокировку
					 */
					if (!in_array($aParams['vote_value'],$aDirection)) {
						continue;
					}
				}
			}
			if ($oBlock->getMsg()) {
				return $oBlock->getMsg();
			} else {
				return false;
			}
		}
		return true;
	}

	public function CheckRuleActionParam($sParam,$mValue,$oUser,$aParams=array()) {
		if ($sParam=='registration_time') {
			if (time()-strtotime($oUser->getDateRegister()) >= $mValue) {
				return true;
			} else {
				return false;
			}
		}
		if ($sParam=='rating') {
			if ($oUser->getRating()>=$mValue) {
				return true;
			} else {
				return false;
			}
		}
		if ($sParam=='skill') {
			if ($oUser->getSkill()>=$mValue) {
				return true;
			} else {
				return false;
			}
		}
		if ($sParam=='count_comment') {
			if ($this->Comment_GetCountCommentsByUserId($oUser->getId(),'topic') >= $mValue) {
				return true;
			} else {
				return false;
			}
		}
		if ($sParam=='count_topic') {
			if ($this->Topic_GetCountTopicsPersonalByUser($oUser->getId(),1) >= $mValue) {
				return true;
			} else {
				return false;
			}
		}
		if ($sParam=='rating_sum_topic') {
			if (is_array($mValue) and count($mValue)>1) {
				$iRating=$mValue[0];
				$iTime=$mValue[1];
			} else {
				$iRating=$mValue;
				$iTime=60*60*24*14;
			}
			if ($this->GetSumRatingTopic($oUser->getId(),date("Y-m-d H:i:s",time()-$iTime)) >= $iRating) {
				return true;
			} else {
				return false;
			}
		}
		if ($sParam=='rating_sum_comment') {
			if (is_array($mValue) and count($mValue)>1) {
				$iRating=$mValue[0];
				$iTime=$mValue[1];
			} else {
				$iRating=$mValue;
				$iTime=60*60*24*7;
			}
			if ($this->GetSumRatingComment($oUser->getId(),date("Y-m-d H:i:s",time()-$iTime)) >= $iRating) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}

	public function CheckRuleDisallowActionParam($sParam,$mValue,$oUser,$aParams=array()) {
		if ($sParam=='user_id') {
			if (!is_array($mValue)) {
				$mValue=array($mValue);
			}
			if (in_array($oUser->getId(),$mValue)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}

	public function CheckForCreateBlockVote($oVote) {
		if (!($oUser=$this->User_GetUserById($oVote->getVoterId()))) {
			return false;
		}
		$sTarget=$oVote->getTargetType();
		$sType=$this->aVoteMirrow[$oVote->getDirection()];

		$aGroups=(array)Config::Get('plugin.magicrule.block_rule_vote');
		foreach($aGroups as $aRule) {
			if (!in_array($sTarget,$aRule['target'])) {
				continue;
			}
			if (!in_array($sType,$aRule['type'])) {
				continue;
			}
			if (isset($aRule['rating'])) {
				if ($oUser->getRating()>=$aRule['rating']) {
					continue;
				}
			}

			$sDate=date('Y-m-d H:i:s',time()-$aRule['period']);
			$iCount=$this->GetCountVote($oUser->getId(),$sTarget,$sDate);
			if ($iCount>=$aRule['count']) {
				$oBlock=Engine::GetEntity('PluginMagicrule_ModuleMain_EntityBlock');
				$oBlock->setUserId($oUser->getId());
				$oBlock->setType(self::BLOCK_TYPE_VOTE);
				$oBlock->setName(isset($aRule['name']) ? $aRule['name'] : '');
				$oBlock->setTarget($sTarget);
				if (isset($aRule['block_msg'])) {
					$sMsg=$aRule['block_msg'];
					if ('NOT_FOUND_LANG_TEXT'!=$sMsgLang=$this->Lang_Get($sMsg)) {
						$sMsg=$sMsgLang;
					}
					$oBlock->setMsg($sMsg);
				}
				$oBlock->setDateBlock(date('Y-m-d H:i:s',time()+$aRule['block_time']));
				$oBlock->setData(array('direction'=>array_values(array_intersect_key(array_flip($this->aVoteMirrow),array_flip($aRule['type'])))));
				$oBlock->Add();
				/**
				 * Прекращаем обход правил
				 */
				if (!Config::Get('plugin.magicrule.processing_block_rule_all')) {
					break;
				}
			}
		}
	}

	public function GetCountVote($iUserId,$sTargetType,$sDate) {
		return $this->oMapper->GetCountVote($iUserId,$sTargetType,$sDate);
	}

	public function GetSumRatingTopic($iUserId,$sDate=null) {
		return $this->oMapper->GetSumRatingTopic($iUserId,$sDate);
	}

	public function GetSumRatingComment($iUserId,$sDate=null) {
		return $this->oMapper->GetSumRatingComment($iUserId,$sDate);
	}
}