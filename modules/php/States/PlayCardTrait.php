<?php
namespace Fluxx\States;

use Fluxx\Game\Utils;
use fluxx;
use Fluxx\Cards\Keepers\KeeperCardFactory;
use Fluxx\Cards\Goals\GoalCardFactory;
use Fluxx\Cards\Rules\RuleCardFactory;
use Fluxx\Cards\Actions\ActionCardFactory;
use Fluxx\Cards\Creepers\CreeperCardFactory;

trait PlayCardTrait
{
  public function st_playCard()
  {
    $game = Utils::getGame();

    // If any card is a force move, play it
    $forcedCardId = $game->getGameStateValue("forcedCard");

    if ($forcedCardId != -1) {
      $game->setGameStateValue("forcedCard", -1);
      // But forced play cards should not really be counted for play rule
      self::action_forced_playCard($forcedCardId);
      return;
    }

    $player_id = $game->getActivePlayerId();

    // If any "free action" rule can be played, we cannot end turn automatically
    // Player must finish its turn by explicitly deciding not to use any of the free rules
    $freeRulesAvailable = $this->getFreeRulesAvailable($player_id);
    if (count($freeRulesAvailable) > 0) {
      return;
    }

    if (!$this->activePlayerMustPlayMoreCards($player_id)) {
      $game->gamestate->nextstate("endOfTurn");
    }
  }

  private function activePlayerMustPlayMoreCards($player_id)
  {
    $leftToPlay = Utils::calculateCardsLeftToPlayFor($player_id);

    return $leftToPlay > 0;
  }

  public function arg_playCard()
  {
    $game = Utils::getGame();
    $player_id = $game->getActivePlayerId();

    $alreadyPlayed = $game->getGameStateValue("playedCards");
    $mustPlay = Utils::calculateCardsMustPlayFor($player_id, true);

    $leftToPlay = Utils::calculateCardsLeftToPlayFor($player_id);
    
    if ($mustPlay >= PLAY_COUNT_ALL) {
      $countLabel = clienttranslate("All");
    } elseif ($mustPlay < 0) {
      $countLabel = clienttranslate("All but") . " " . -$mustPlay;
    } else {
      $countLabel = $leftToPlay;
    }

    $freeRulesAvailable = $this->getFreeRulesAvailable($player_id);
    
    return [
      "countLabel" => $countLabel,
      "count" => $leftToPlay,
      "freeRules" => $freeRulesAvailable,
    ];
  }

  private function getFreeRulesAvailable($player_id)
  {
    $freeRulesAvailable = [];

    $game = Utils::getGame();
    $rulesInPlay = $game->cards->getCardsInLocation("rules", RULE_OTHERS);
    foreach ($rulesInPlay as $card_id => $rule) {
      $ruleCard = RuleCardFactory::getCard($rule["id"], $rule["type_arg"]);

      if ($ruleCard->canBeUsedInPlayerTurn($player_id)) {
        $freeRulesAvailable[] = [
          "card_id" => $card_id,
          "name" => $ruleCard->getName(),
        ];
      }
    }

    return $freeRulesAvailable;
  }

  public function action_finishTurn()
  {
    $game = Utils::getGame();
    // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
    $game->checkAction("finishTurn");

    $player_id = $game->getActivePlayerId();
    if ($this->activePlayerMustPlayMoreCards($player_id)) {
      Utils::throwInvalidUserAction(
        fluxx::totranslate(
          "You cannot finish your turn if you still need to play cards"
        )
      );
    }

    $game->gamestate->nextstate("endOfTurn");
  }

  public function action_playFreeRule($card_id)
  {
    $game = Utils::getGame();

    // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
    $game->checkAction("playFreeRule");

    $player_id = $game->getActivePlayerId();
    $card = $game->cards->getCard($card_id);

    if ($card["location"] != "rules") {
      Utils::throwInvalidUserAction(
        fluxx::totranslate("This is not an active Rule")
      );      
    }

    $ruleCard = RuleCardFactory::getCard($card_id, $card["type_arg"]);

    $game->notifyAllPlayers(
      "freeRulePlayed",
      clienttranslate('${player_name} uses free rule <b>${card_name}</b>'),
      [
        "i18n" => ["card_name"],
        "player_name" => $game->getActivePlayerName(),
        "player_id" => $player_id,
        "card_name" => $ruleCard->getName(),
      ]
    );    

    $stateTransition = $ruleCard->freePlayInPlayerTurn($player_id);
    if ($stateTransition != null) {
      // player must resolve something before continuing to play more cards
      $game->gamestate->nextstate($stateTransition);
    } else {
      // else: just let player continue playing cards
      // but explicitly set state again to force args refresh
      $game->gamestate->nextstate("continuePlay");
    }
  }

  public function action_playCard($card_id)
  {
    // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
    $game = Utils::getGame();
    $game->checkAction("playCard");

    // and Check that the active player is actually allowed to play more cards!
    // (maybe turn is still active only because they have free rules left to play)
    $player_id = $game->getActivePlayerId();
    if (!$this->activePlayerMustPlayMoreCards($player_id)) {
      Utils::throwInvalidUserAction(
        fluxx::totranslate("You don't have any card plays left")
      );
    }

    // play the card from active player's hand
    self::_action_playCard($card_id, true);
  }

  public function action_forced_playCard($card_id)
  {
    // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
    $game = Utils::getGame();
    $game->checkAction("playCard");
    // play the card from active player's hand, but don't count it for nr played cards    
    self::_action_playCard($card_id, false);
  }

  private function _action_playCard($card_id, $incrementPlayedCards)
  {
    $game = Utils::getGame();

    $player_id = $game->getActivePlayerId();
    $card = $game->cards->getCard($card_id);

    if ($card["location"] != "hand" or $card["location_arg"] != $player_id) {
      Utils::throwInvalidUserAction(
        fluxx::totranslate("You do not have this card in hand")
      );
    }

    $card_type = $card["type"];
    $stateTransition = null;
    $continuePlayTransition = "continuePlay";
    switch ($card_type) {
      case "keeper":
        $this->playKeeperCard($player_id, $card);
        break;
      case "goal":
        $stateTransition = $this->playGoalCard($player_id, $card);
        break;
      case "rule":
        $stateTransition = $this->playRuleCard($player_id, $card);
        break;
      case "action":
        $stateTransition = $this->playActionCard($player_id, $card);
        break;
      case "creeper":
        $this->playCreeperCard($player_id, $card);
        // Creepers are played automatically when drawn in any state,
        // so we must stay in whatever the current state is
        $continuePlayTransition = null;
        break;
      default:
        die("Not implemented: Card type $card_type does not exist");
        break;
    }

    if ($incrementPlayedCards) {
      $game->incGameStateValue("playedCards", 1);
    }

    // A card has been played: do we have a new winner?
    $game->checkWinConditions();

    // if not, maybe the card played had effect for any of the bonus conditions?
    $game->checkBonusConditions($player_id);

    if ($stateTransition != null) {
      // player must resolve something before continuing to play more cards
      $game->gamestate->nextstate($stateTransition);
    } else if ($continuePlayTransition != null) {
      // else: just let player continue playing cards
      // but explicitly set state again to force args refresh
      $game->gamestate->nextstate($continuePlayTransition);
    }
  }

  public function playKeeperCard($player_id, $card)
  {
    $game = Utils::getGame();

    $game->cards->moveCard($card["id"], "keepers", $player_id);

    // Notify all players about the keeper played
    $keeperCard = KeeperCardFactory::getCard($card["id"], $card["type_arg"]);
    $game->notifyAllPlayers(
      "keeperPlayed",
      clienttranslate('${player_name} plays keeper <b>${card_name}</b>'),
      [
        "i18n" => ["card_name"],
        "player_name" => $game->getActivePlayerName(),
        "player_id" => $player_id,
        "card_name" => $keeperCard->getName(),
        "card" => $card,
        "handCount" => $game->cards->countCardInLocation("hand", $player_id),
        "creeperCount" => Utils::getPlayerCreeperCount($player_id),
      ]
    );
  }

  public function playCreeperCard($player_id, $card)
  {
    $game = Utils::getGame();

    // creepers go to table on same location as keepers
    $game->cards->moveCard($card["id"], "keepers", $player_id);

    // Notify all players about the creeper played
    $creeperCard = CreeperCardFactory::getCard($card["id"], $card["type_arg"]);
    $game->notifyAllPlayers(
      "creeperPlayed",
      clienttranslate('${player_name} must place creeper <b>${card_name}</b>'),
      [
        "i18n" => ["card_name"],
        "player_name" => $game->getActivePlayerName(),
        "player_id" => $player_id,
        "card_name" => $creeperCard->getName(),
        "card" => $card,
        "handCount" => $game->cards->countCardInLocation("hand", $player_id),
        "creeperCount" => Utils::getPlayerCreeperCount($player_id),
      ]
    );
  }

  public function playGoalCard($player_id, $card)
  {
    $game = Utils::getGame();

    // Notify all players about the goal played
    $goalCard = GoalCardFactory::getCard($card["id"], $card["type_arg"]);
    
    // this goal card is still in hand at this time
    $handCount = $game->cards->countCardInLocation("hand", $player_id) - 1;

    $game->notifyAllPlayers(
      "goalPlayed",
      clienttranslate('${player_name} sets a new goal <b>${card_name}</b>'),
      [
        "i18n" => ["card_name"],
        "player_name" => $game->getActivePlayerName(),
        "player_id" => $player_id,
        "card_name" => $goalCard->getName(),
        "card" => $card,
        "handCount" => $handCount,
      ]
    );

    $existingGoalCount = $game->cards->countCardInLocation("goals");
    $hasDoubleAgenda = Utils::getActiveDoubleAgenda();

    // No double agenda: we simply discard the oldest goal
    if (!$hasDoubleAgenda) {
      $goals = $game->cards->getCardsInLocation("goals");
      foreach ($goals as $goal_id => $goal) {
        $game->cards->playCard($goal_id);
      }

      if ($goals) {
        $game->notifyAllPlayers("goalsDiscarded", "", [
          "cards" => $goals,
          "discardCount" => $game->cards->countCardInLocation("discard"),
        ]);
      }
    }

    // We play the new goal
    $game->cards->moveCard($card["id"], "goals");

    if ($hasDoubleAgenda && $existingGoalCount > 1) {
      $game->setGameStateValue("lastGoalBeforeDoubleAgenda", $card["id"]);
      return "doubleAgendaRule";
    }
  }

  public function playRuleCard($player_id, $card)
  {
    $game = Utils::getGame();

    $game->setGameStateValue("freeRuleToResolve", -1);
    $ruleCard = RuleCardFactory::getCard($card["id"], $card["type_arg"]);
    $ruleType = $ruleCard->getRuleType();

    // Notify all players about the new rule
    // (this needs to be done before the effect, otherwise the history is confusing)
    // and so the hand count must be corrected accordingly
    $handCount = $game->cards->countCardInLocation("hand", $player_id) - 1;

    $game->notifyAllPlayers(
      "rulePlayed",
      clienttranslate('${player_name} placed a new rule: <b>${card_name}</b>'),
      [
        "i18n" => ["card_name"],
        "player_name" => $game->getActivePlayerName(),
        "card_name" => $ruleCard->getName(),
        "player_id" => $player_id,
        "ruleType" => $ruleType,
        "card" => $card,
        "handCount" => $handCount,
      ]
    );

    $location_arg = $game->getLocationArgForRuleType($ruleType);

    // Execute the immediate rule effect
    $stateTransition = $ruleCard->immediateEffectOnPlay($player_id);    

    $game->cards->moveCard($card["id"], "rules", $location_arg);

    return $stateTransition;
  }

  public function playActionCard($player_id, $card)
  {
    $game = Utils::getGame();

    $game->setGameStateValue("actionToResolve", -1);
    $actionCard = ActionCardFactory::getCard($card["id"], $card["type_arg"]);

    // Notify all players about the action played
    // (this needs to be done before the effect, otherwise the history is confusing)
    // and so the hand + discard count must be corrected accordingly
    $handCount = $game->cards->countCardInLocation("hand", $player_id) - 1;
    $discardCount = $game->cards->countCardInLocation("discard") + 1;

    $game->notifyAllPlayers(
      "actionPlayed",
      clienttranslate('${player_name} plays an action: <b>${card_name}</b>'),
      [
        "i18n" => ["card_name"],
        "player_name" => $game->getActivePlayerName(),
        "player_id" => $player_id,
        "card_name" => $actionCard->getName(),
        "card" => $card,
        "handCount" => $handCount,
        "discardCount" => $discardCount,
      ]
    );

    // We play the new action card
    $game->cards->playCard($card["id"]);

    // execute the action immediate effect
    $stateTransition = $actionCard->immediateEffectOnPlay($player_id);

    return $stateTransition;
  }
}
