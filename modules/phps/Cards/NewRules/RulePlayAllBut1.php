<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;

class RulePlayAllBut1 extends RulePlay
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);

        $this->name = clienttranslate("Play All But 1");
        $this->subtitle = clienttranslate("Replaces Play Rule");
        $this->description = clienttranslate(
            "Play all but 1 of your cards. If you started with no cards in your hand and only drew 1, draw an extra card."
        );

        $this->setNewPlayCount(-1);
    }
}
