<?php
namespace Fluxx\Cards\NewRules;

use Fluxx\Game\Utils;

class RuleDraw2 extends RuleDraw
{
  public function __construct($cardId, $uniqueId)
  {
    parent::__construct($cardId, $uniqueId);

    $this->name = clienttranslate("Draw 2");
    $this->subtitle = clienttranslate("Replaces Draw Rule");
    $this->description = clienttranslate(
      "Draw 2 cards per turn. If you just played this card, draw extra cards as needed to reach 2 cards drawn."
    );

    $this->setNewDrawCount(2);
  }
}
