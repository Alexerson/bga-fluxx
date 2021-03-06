<?php
namespace Fluxx\Cards\Goals;

use Fluxx\Game\Utils;

class GoalPeaceNoWar extends GoalCard
{
  public function __construct($cardId, $uniqueId)
  {
    parent::__construct($cardId, $uniqueId);

    $this->set = "creeperpack";
    $this->name = clienttranslate("Peace (No War)");
    $this->description = clienttranslate(
      "If no one has War on the table, the player with Peace on the table wins."
    );

    $this->peace_keeper = 19;
    $this->war_creeper = 51;
  }

  public function goalReachedByPlayer()
  {
    $cards = Utils::getGame()->cards;

    $peace_keeper_card = array_values(
      $cards->getCardsOfType("keeper", $this->peace_keeper)
    )[0];

    // Someone needs to have peace
    if ($peace_keeper_card["location"] != "keepers") {
      return null;
    }

    $war_creeper_cards = $cards->getCardsOfTypeInLocation(
      "creeper",
      $this->war_creeper,
      "keepers"
    );

    // If anyone has War, can't win
    if (count($war_creeper_cards) > 0) {
      return null;
    }

    // Else the player with peace wins
    return $peace_keeper_card["location_arg"];
  }
}
