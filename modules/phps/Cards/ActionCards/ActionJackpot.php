<?php
namespace Fluxx\Cards\ActionCards;

use Fluxx\Game\Utils;

class ActionJackpot extends ActionCard
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);

        $this->name = clienttranslate("Jackpot!");
        $this->description = clienttranslate("Draw 3 extra cards!");
    }

    public function needsInteraction()
    {
        return false;
    }

    public function immediateEffectOnPlay($player)
    {
        Utils::getGame()->drawExtraCards($player, 3);
    }
}
