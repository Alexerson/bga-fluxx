<?php
namespace Fluxx\Cards\Actions;

use Fluxx\Game\Utils;
use fluxx;

class ActionStealSomething extends ActionCard
{
  public function __construct($cardId, $uniqueId)
  {
    parent::__construct($cardId, $uniqueId);

    $this->name = clienttranslate("Steal Something");
    $this->description = clienttranslate(
      "Take your choice of any Keeper or Creeper from in front of another player and put it in front of you."
    );
  }

  public $interactionNeeded = "keeperSelection";

  public function immediateEffectOnPlay($player_id)
  {
    $game = Utils::getGame();
    $keepersInPlay = $game->cards->countCardInLocation("keepers");
    $playersKeepersInPlay = $game->cards->countCardInLocation(
      "keepers",
      $player_id
    );
    if ($keepersInPlay - $playersKeepersInPlay == 0) {
      // no keepers on the table for others, this action does nothing
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
    $other_player_id = $card["location_arg"];

    if ($card_location != "keepers" || $other_player_id == $player_id) {
      Utils::throwInvalidUserAction(
        fluxx::totranslate(
          "You must select a keeper or creeper card in front of another player"
        )
      );
    }

    // move this keeper to the current player
    $game->cards->moveCard($card["id"], "keepers", $player_id);

    $players = $game->loadPlayersBasicInfos();
    $other_player_name = $players[$other_player_id]["player_name"];

    $game->notifyAllPlayers(
      "keepersMoved",
      clienttranslate(
        '${player_name} stole <b>${card_name}</b> from <b>${other_player_name}</b>'
      ),
      [
        "player_name" => $game->getActivePlayerName(),
        "other_player_name" => $other_player_name,
        "card_name" => $card_definition->getName(),
        "destination_player_id" => $player_id,
        "origin_player_id" => $other_player_id,
        "cards" => [$card],
      ]
    );
  }
}