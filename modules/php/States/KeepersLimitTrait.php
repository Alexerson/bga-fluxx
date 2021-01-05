<?php
namespace Fluxx\States;

use Fluxx\Game\Utils;

trait KeepersLimitTrait
{
  private function getKeepersLimit()
  {
    return self::getGameStateValue("keepersLimit");
  }

  private function getKeepersInfractions($players_id = null)
  {
    $keepersLimit = $this->getKeepersLimit();

    // no active Keeper Limit, nothing to do
    if ($keepersLimit < 0) {
      return [];
    }

    if ($players_id == null) {
      $players_id = array_keys(self::loadPlayersBasicInfos());
    }
    $playersInfraction = [];

    $cards = Utils::getGame()->cards;

    foreach ($players_id as $player_id) {
      $keepersInPlay = $cards->countCardInLocation("keepers", $player_id);
      if ($keepersInPlay > $keepersLimit) {
        $playersInfraction[$player_id] = [
          "count" => $keepersInPlay - $keepersLimit,
        ];
      }
    }

    return $playersInfraction;
  }

  public function st_enforceKeepersLimitForOthers()
  {
    $playersInfraction = $this->getKeepersInfractions();

    // The keepers limit doesn't apply to the active player.
    $active_player_id = self::getActivePlayerId();

    if (array_key_exists($active_player_id, $playersInfraction)) {
      unset($playersInfraction[$active_player_id]);
    }

    $gamestate = Utils::getGame()->gamestate;

    // Activate all players that need to remove keepers (if any)
    $gamestate->setPlayersMultiactive(array_keys($playersInfraction), "", true);
  }

  public function st_enforceKeepersLimitForSelf()
  {
    $player_id = self::getActivePlayerId();
    $playersInfraction = $this->getKeepersInfractions([$player_id]);

    $gamestate = Utils::getGame()->gamestate;

    if (count($playersInfraction) == 0) {
      // Player is not in the infraction with the rule
      $gamestate->nextstate("");
      return;
    }
  }

  public function arg_enforceKeepersLimitForOthers()
  {
    return [
      "limit" => $this->getKeepersLimit(),
      "_private" => $this->getKeepersInfractions(),
    ];
  }

  public function arg_enforceKeepersLimitForSelf()
  {
    $player_id = self::getActivePlayerId();
    $playersInfraction = $this->getKeepersInfractions([$player_id]);

    return [
      "limit" => $this->getKeepersLimit(),
      "_private" => [
        "active" => $playersInfraction[$player_id] ?? ["count" => 0],
      ],
    ];
  }

  /*
   * Player discards a nr of cards for keeper limit
   */
  function action_discardKeepers($cards_id)
  {
    $game = Utils::getGame();

    // possible multiple active state, so use currentPlayer rather than activePlayer
    $game->gamestate->checkPossibleAction("discardKeepers");
    $player_id = self::getCurrentPlayerId();

    $playersInfraction = $this->getKeepersInfractions([$player_id]);
    $expectedCount = $playersInfraction[$player_id]["count"];
    if (count($cards_id) != $expectedCount) {
      Utils::throwInvalidUserAction(
        self::_("Wrong number of cards. Expected: ") . $expectedCount
      );
    }

    $cards = self::discardCardsFromLocation($cards_id, "keepers", $player_id);

    self::notifyAllPlayers("keepersDiscarded", "", [
      "player_id" => $player_id,
      "cards" => $cards,
      "discardCount" => $game->cards->countCardInLocation("discard"),
    ]);

    $state = $game->gamestate->state();

    if ($state["type"] == "multipleactiveplayer") {
      // Multiple active state: this player is done
      $game->gamestate->setPlayerNonMultiactive($player_id, "");
    } else {
      $game->gamestate->nextstate("");
    }
  }
}