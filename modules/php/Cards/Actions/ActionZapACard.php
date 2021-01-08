<?php
namespace Fluxx\Cards\Actions;

use Fluxx\Game\Utils;
use Fluxx\Cards\Rules\RuleCardFactory;
use fluxx;

class ActionZapACard extends ActionCard
{
  public function __construct($cardId, $uniqueId)
  {
    parent::__construct($cardId, $uniqueId);

    $this->name = clienttranslate("Zap a Card!");
    $this->description = clienttranslate(
      "Choose any card in play, anywhere on the table (except for the Basic Rules) and add it to your hand."
    );
  }

  public $interactionNeeded = "cardSelection";

  public function immediateEffectOnPlay($player_id)
  {
    $game = Utils::getGame();
    $keepersInPlay = $game->cards->countCardInLocation("keepers");
    $rulesInPlay = $game->cards->countCardInLocation("rules");
    $goalsInPlay = $game->cards->countCardInLocation("goals");
    if ($keepersInPlay + $rulesInPlay + $goalsInPlay == 0) {
      // no cards in play anywhere, this action does nothing
      return;
    }

    return parent::immediateEffectOnPlay($player_id);
  }

  public function resolvedBy($player_id, $args)
  {
    $game = Utils::getGame();

    $card = $args["card"];
    $card_definition = $game->getCardDefinitionFor($card);

    $card_location = $card["location"];

    if (!in_array($card_location, ["keepers", "rules", "goals"])) {
      Utils::throwInvalidUserAction(
        clienttranslate("You must select a card in play on the table")
      );
    }

    // if a rule is taken back, its effect stops
    if ($card["type"] == "rule") {
      $card_definition->immediateEffectOnDiscard($player_id);
    }

    // move this card to player hand
    $game->cards->moveCard($card["id"], "hand", $player_id);

    $game->notifyAllPlayers(
      "cardFromTableToHand",
      clienttranslate('${player_name} zaps <b>${card_name}</b>'),
      [
        "player_name" => $game->getActivePlayerName(),
        "card_name" => $card_definition->getName(),
        "card" => $card,
        "player_id" => $player_id,
        "handCount" => $game->cards->countCardInLocation("hand", $player_id),
      ]
    );
  }
}
