<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;

class RuleDraw3 extends RuleDraw
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);

        $this->name = clienttranslate("Draw 3");
        $this->subtitle = clienttranslate("Replaces Draw Rule");
        $this->description = clienttranslate(
            "Draw 3 cards per turn. If you just played this card, draw extra cards as needed to reach 3 cards drawn."
        );

        $this->setNewDrawCount(3);
    }
}
