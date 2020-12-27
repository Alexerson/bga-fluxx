<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;

class RuleKeeperLimit2 extends RuleKeeperLimit
{
    public function __construct($cardId, $uniqueId)
    {
        parent::__construct($cardId, $uniqueId);

        $this->name = clienttranslate("Keeper Limit 2");
        $this->subtitle = clienttranslate("Replaces Keeper Limit");
        $this->description = clienttranslate(
            "If it isn't your turn, you can only have 2 Keepers in play. Discard extras immediately. You may acquire new Keepers during your turn as long as you discard down to 2 when your turn ends."
        );

        $this->setNewKeeperLimit(2);
    }
}
