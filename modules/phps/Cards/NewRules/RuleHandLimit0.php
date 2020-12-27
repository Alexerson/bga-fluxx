<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;

class RuleHandLimit0 extends RuleHandLimit
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);

        $this->name = clienttranslate("Hand Limit 0");
        $this->subtitle = clienttranslate("Replaces Hand Limit");
        $this->description = clienttranslate(
            "If it isn't your turn, you can only have 0 cards in your hand. Discard extras immediately. During your turn, this rule does not apply to you, after your turn ends, discard down to 0 cards."
        );

        $this->setNewHandLimit(0);
    }
}
