<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;

class RuleKeeperLimit3 extends RuleKeeperLimit
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);

        $this->name = clienttranslate("Keeper Limit 3");
        $this->subtitle = clienttranslate("Replaces Keeper Limit");
        $this->description = clienttranslate(
            "If it isn't your turn, you can only have 3 Keepers in play. Discard extras immediately. You may acquire new Keepers during your turn as long as you discard down to 3 when your turn ends."
        );

        $this->setNewKeeperLimit(3);
    }
}
