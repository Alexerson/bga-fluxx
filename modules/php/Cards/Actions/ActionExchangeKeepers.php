<?php
namespace Fluxx\Cards\Actions;

use Fluxx\Game\Utils;
use fluxx;

class ActionExchangeKeepers extends ActionCard
{
  public function __construct($cardId, $uniqueId)
  {
    parent::__construct($cardId, $uniqueId);

    $this->name = clienttranslate("Exchange Keepers");
    $this->description = clienttranslate(
      "Pick any Keeper another player has on the table and exchange it for one you have on the table. <be/> If you have no Keepers in play, or if no one else has a Keeper, nothing happens."
    );
  }

  public $interactionNeeded = "keepersExchange";

  public function immediateEffectOnPlay($player_id)
  {
    $game = Utils::getGame();
    $keepersInPlay = $game->cards->countCardInLocation("keepers");
    $playersKeepersInPlay = $game->cards->countCardInLocation(
      "keepers",
      $player_id
    );
    if (
      $playersKeepersInPlay == 0 ||
      $keepersInPlay - $playersKeepersInPlay == 0
    ) {
      // no keepers on my side or
      // no keepers on the table for others, this action does nothing
      return;
    }

    return parent::immediateEffectOnPlay($player_id);
  }

  public function resolvedBy($player_id, $args)
  {
    $game = Utils::getGame();

    $myKeeperId = $args["myKeeperId"];
    $otherKeeperId = $args["otherKeeperId"];

    $myKeeper = $game->cards->getCard($myKeeperId);
    $otherKeeper = $game->cards->getCard($otherKeeperId);
    $other_player_id = $otherKeeper["location_arg"];

    if (
      $myKeeper["location"] != "keepers" ||
      $otherKeeper["location"] != "keepers" ||
      $myKeeper["location_arg"] != $player_id ||
      $other_player_id == $player_id
    ) {
      Utils::throwInvalidUserAction(
        fluxx::totranslate(
          "You must select exactly 2 Keeper cards, 1 of yours and 1 of another player"
        )
      );
    }

    // switch the keeper locations
    $game->cards->moveCard($myKeeper["id"], "keepers", $other_player_id);

    $game->notifyAllPlayers("keepersMoved", "", [
      "origin_player_id" => $player_id,
      "destination_player_id" => $other_player_id,
      "cards" => [$myKeeper],
    ]);

    $game->cards->moveCard($otherKeeper["id"], "keepers", $player_id);
    $game->notifyAllPlayers("keepersMoved", "", [
      "origin_player_id" => $other_player_id,
      "destination_player_id" => $player_id,
      "cards" => [$otherKeeper],
    ]);

    $players = $game->loadPlayersBasicInfos();
    $other_player_name = $players[$other_player_id]["player_name"];
    $myKeeperCard = $game->getCardDefinitionFor($myKeeper);
    $otherKeeperCard = $game->getCardDefinitionFor($otherKeeper);

    $game->notifyAllPlayers(
      "actionResolved",
      clienttranslate(
        '${player_name} got <b>${other_keeper_name}</b> from <b>${other_player_name}</b> in exchange for <b>${my_keeper_name}</b>'
      ),
      [
        "player_name" => $game->getActivePlayerName(),
        "other_player_name" => $other_player_name,
        "other_keeper_name" => $otherKeeperCard->getName(),
        "my_keeper_name" => $myKeeperCard->getName(),
      ]
    );
  }
}
