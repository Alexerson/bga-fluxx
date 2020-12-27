<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;
/*
 * RuleHandLimit: base class for all Rule cards that adapt the current Hand Limit
 */
class RuleHandLimit extends RuleCard
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);
    }

    public function getRuleType()
    {
        return "handLimit";
    }

    protected $handLimit;

    public function setNewHandLimit($newValue)
    {
        $this->handLimit = $newValue;
    }

    public function usedInPlayerTurn()
    {
        return false;
    }

    public function immediateEffectOnPlay($player)
    {
        // current Hand Limit is changed immediately
        $this->adaptHandLimit($player, $this->handLimit);

        // @TODO: Hand Limits are currently only applied
        // at the end of the active player turn in state.
        // They should be enforced on other players immediately during the turn.
    }

    public function immediateEffectOnDiscard($player)
    {
        // reset to Basic Hand Limit = none
        $this->adaptHandLimit($player, -1);
    }

    protected function adaptHandLimit($player, $newValue)
    {
        $oldValue = Utils::getGame()->getGameStateValue("handLimit");
        // discard any other hand limit rules
        Utils::getGame()->discardRuleCardsForType("handLimit");
        // set new play rule
        Utils::getGame()->setGameStateValue("handLimit", $newValue);
    }
}
