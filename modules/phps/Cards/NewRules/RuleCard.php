<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Cards\Card;
use Fluxx\Game\Utils;
/*
 * RuleCard: base class to handle new rule cards
 */
class RuleCard extends Card
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);
    }

    public function getRuleType()
    {
        return "others";
    }

    // Indicates this Rule effect can be used during client-side player turns
    public function usedInPlayerTurn()
    {
        return false;
    }

    // Implements the immediate effect when this rule is put in play
    public function immediateEffectOnPlay($player)
    {
    }

    // Implements the immediate effect when this rule is discarded from play
    public function immediateEffectOnDiscard($player)
    {
    }

    /**
     * playFromHand : default function to execute when a Card is played from hand.
     * return: null if the game should continue the play loop,
     * or "state Transition Name" if another state need to be called
     */
    public function playFromHand($player)
    {
        // Execute the immediate effect
        $this->immediateEffectOnPlay($player);

        return null;
    }

    /**
     * resolvedBy : default function to execute when interactive decisions
     * for the played Rule card have been resolved (passed in via args).
     * return: null if the game should continue the play loop,
     * or "state Transition Name" if another state need to be called
     */
    public function resolvedBy($player, $args)
    {
        return null;
    }
}
