<?php
namespace Fluxx\Cards\ActionCards;

use Fluxx\Game\Utils;

class ActionTrashAKeeper extends ActionCard
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);

        $this->name = clienttranslate("Trash a Keeper");
        $this->description = clienttranslate(
            "Take a Keeper from in front of any player and put it on the discard pile. <br/> If no one has any Keepers in play, nothing happends when you play this card."
        );
    }

    public function needsInteraction()
    {
        return true;
    }

    public function immediateEffectOnPlay($player)
    {
        // nothing now, needs to go to resolve action state
    }
}
