<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;
/*
 * RuleKeeperLimit: base class for all Rule cards that adapt the current Keeper Limit
 */
class RuleKeeperLimit extends RuleCard
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);
    }

    public function getRuleType()
    {
        return "keepersLimit";
    }

    protected $keeperLimit;

    public function setNewKeeperLimit($newValue)
    {
        $this->keeperLimit = $newValue;
    }

    public function usedInPlayerTurn()
    {
        return false;
    }

    public function immediateEffectOnPlay($player)
    {
        // current Keeper Limit is changed immediately
        $this->adaptKeeperLimit($player, $this->keeperLimit);

        // @TODO: Keeper Limits are currently only applied
        // at the end of the active player turn.
        // They should be enforced on other players immediately during the turn.
    }

    public function immediateEffectOnDiscard($player)
    {
        // reset to Basic Keeper Limit = none
        $this->adaptKeeperLimit($player, -1);
    }

    protected function adaptKeeperLimit($player, $newValue)
    {
        $oldValue = Utils::getGame()->getGameStateValue("keepersLimit");
        // discard any other keeper limit rules
        Utils::getGame()->discardRuleCardsForType("keepersLimit");
        // set new play rule
        Utils::getGame()->setGameStateValue("keepersLimit", $newValue);
    }
}
