<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;

class RulePlayAll extends RulePlay
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);

        $this->name = clienttranslate("Play All");
        $this->subtitle = clienttranslate("Replaces Play Rule");
        $this->description = clienttranslate("Play all your cards per turn.");

        $this->setNewPlayCount(200);
    }
}
