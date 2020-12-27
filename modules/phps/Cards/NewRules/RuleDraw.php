<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;
/*
 * RuleDraw: base class for all Rule cards that adapt the current Draw rule
 */
class RuleDraw extends RuleCard
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);
    }

    public function getRuleType()
    {
        return "drawRule";
    }

    protected $drawCount;

    public function setNewDrawCount($newValue)
    {
        $this->drawCount = $newValue;
    }

    public function usedInPlayerTurn()
    {
        return false;
    }

    public function immediateEffectOnPlay($player)
    {
        // current Draw Rule is changed immediately
        $this->adaptDrawRule($player, $this->drawCount);
    }

    public function immediateEffectOnDiscard($player)
    {
        // reset to Basic Draw Rule
        $this->adaptDrawRule($player, 1);
    }

    protected function adaptDrawRule($player, $newValue)
    {
        $oldValue = Utils::getGame()->getGameStateValue("drawRule");
        // discard any other draw rules
        Utils::getGame()->discardRuleCardsForType("drawRule");
        // set new draw rule
        Utils::getGame()->setGameStateValue("drawRule", $newValue);
        // draw extra cards for the difference
        if ($newValue - $oldValue > 0) {
            Utils::getGame()->drawExtraCards($player, $newValue - $oldValue);
        }
    }
}
