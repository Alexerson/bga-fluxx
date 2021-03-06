<?php
namespace Fluxx\Cards\Rules;

use Fluxx\Game\Utils;

class RuleSwapPlaysForDraws extends RuleCard
{
  public function __construct($cardId, $uniqueId)
  {
    parent::__construct($cardId, $uniqueId);

    $this->name = clienttranslate("Swap Plays for Draws");
    $this->subtitle = clienttranslate("Takes Instant Effect");
    $this->description = clienttranslate(
      "During your turn, you may decide to play no more cards and instead draw as many cards as you have plays remaining. If Play All, draw as many cards as you hold."
    );
  }

  public function canBeUsedInPlayerTurn($player_id)
  {
    $drawCount = Utils::calculateCardsLeftToPlayFor($player_id);
    return $drawCount > 0;
  }

  public function immediateEffectOnPlay($player)
  {
    // nothing
  }

  public function immediateEffectOnDiscard($player)
  {
    // nothing
  }

  public function freePlayInPlayerTurn($player_id)
  {
    $game = Utils::getGame();
    // calculate how many cards player should still play
    $drawCount = Utils::calculateCardsLeftToPlayFor($player_id);
    // draw as many cards as we could have still played
    if ($drawCount > 0) {
      $game->performDrawCards($player_id, $drawCount);
    }

    // Force end of turn
    return "endOfTurn";
  }
}
