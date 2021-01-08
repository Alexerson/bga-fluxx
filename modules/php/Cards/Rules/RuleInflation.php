<?php
namespace Fluxx\Cards\Rules;

use Fluxx\Game\Utils;

class RuleInflation extends RuleCard
{
  public function __construct($cardId, $uniqueId)
  {
    parent::__construct($cardId, $uniqueId);

    $this->name = clienttranslate("Inflation");
    $this->subtitle = clienttranslate("Takes Instant Effect");
    $this->description = clienttranslate(
      "Any time a numeral is seen on another card, add one to that numeral. For example, 1 becomes 2, while one remains one. Yes, this affects the Basic Rules."
    );
  }

  public function immediateEffectOnPlay($player)
  {
    // @TODO : set game state?
  }

  public function immediateEffectOnDiscard($player)
  {
    // @TODO : unset game state?
  }
}