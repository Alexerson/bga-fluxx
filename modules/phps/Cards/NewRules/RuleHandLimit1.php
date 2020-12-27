<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;

class RuleHandLimit1 extends RuleHandLimit
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);

        $this->name = clienttranslate("Hand Limit 1");
        $this->subtitle = clienttranslate("Replaces Hand Limit");
        $this->description = clienttranslate(
            "If it isn't your turn, you can only have 1 card in your hand. Discard extras immediately. During your turn, this rule does not apply to you, after your turn ends, discard down to 1 card."
        );

        $this->setNewHandLimit(1);
    }
}
