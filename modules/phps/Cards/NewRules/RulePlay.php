<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;
/*
 * RulePlay: base class for all Rule cards that adapt the current Play rule
 */
class RulePlay extends RuleCard
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);
    }

    public function getRuleType()
    {
        return "playRule";
    }

    protected $playCount;

    public function setNewPlayCount($newValue)
    {
        $this->playCount = $newValue;
    }

    public function usedInPlayerTurn()
    {
        return false;
    }

    public function immediateEffectOnPlay($player)
    {
        // current Play Rule is changed immediately
        $this->adaptPlayRule($player, $this->playCount);
    }

    public function immediateEffectOnDiscard($player)
    {
        // reset to Basic Play Rule
        $this->adaptPlayRule($player, 1);
    }

    protected function adaptPlayRule($player, $newValue)
    {
        $oldValue = Utils::getGame()->getGameStateValue("playRule");
        // discard any other play rules
        Utils::getGame()->discardRuleCardsForType("playRule");
        // set new play rule
        Utils::getGame()->setGameStateValue("playRule", $newValue);
    }
}
