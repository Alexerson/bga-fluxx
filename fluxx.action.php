<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * fluxx implementation : © Alexandre Spaeth <alexandre.spaeth@hey.com> & Iwan Tomlow <iwan.tomlow@gmail.com> & Julien Rossignol <tacotaco.dev@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 *
 * fluxx.action.php
 *
 * fluxx main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/fluxx/fluxx/myAction.html", ...)
 *
 */

class action_fluxx extends APP_GameAction
{
  // Constructor: please do not modify
  public function __default()
  {
    if (self::isArg("notifwindow")) {
      $this->view = "common_notifwindow";
      $this->viewArgs["table"] = self::getArg("table", AT_posint, true);
    } else {
      $this->view = "fluxx_fluxx";
      self::trace("Complete reinitialization of board game");
    }
  }

  public function playCard()
  {
    self::setAjaxMode();
    $card_id = self::getArg("card_id", AT_posint, true);
    $card_definition_id = self::getArg("card_definition_id", AT_posint, true);
    $this->game->action_playCard($card_id, $card_definition_id);
    self::ajaxResponse();
  }

  public function stripListOfCardIds($card_ids_raw)
  {
    // Removing last ';' if exists
    if (substr($card_ids_raw, -1) == ";") {
      $card_ids_raw = substr($card_ids_raw, 0, -1);
    }
    if ($card_ids_raw == "") {
      $card_ids = [];
    } else {
      $card_ids = explode(";", $card_ids_raw);
    }
    return $card_ids;
  }

  public function discardHandCards()
  {
    self::setAjaxMode();
    $card_ids_raw = self::getArg("card_ids", AT_numberlist, true); // ids of card to discard
    $result = $this->game->action_discardHandCards(
      $this->stripListOfCardIds($card_ids_raw)
    );
    self::ajaxResponse();
  }

  public function discardKeepers()
  {
    self::setAjaxMode();
    $card_ids_raw = self::getArg("card_ids", AT_numberlist, true); // ids of card to discard
    $result = $this->game->action_discardKeepers(
      $this->stripListOfCardIds($card_ids_raw)
    );
    self::ajaxResponse();
  }

  public function discardGoal()
  {
    self::setAjaxMode();
    $card_id = self::getArg("card_id", AT_posint, true);
    $card_definition_id = self::getArg("card_definition_id", AT_posint, true);
    $this->game->action_discardGoal($card_id, $card_definition_id);
    self::ajaxResponse();
  }

  public function resolveActionWithCards()
  {
    self::setAjaxMode();
    $option = self::getArg("option", AT_posint, true); // option button chosen
    $card_ids_raw = self::getArg("card_ids", AT_numberlist, true); // ids of card to use
    $result = $this->game->action_resolveActionWithCards(
      $option,
      $this->stripListOfCardIds($card_ids_raw)
    );
    self::ajaxResponse();
  }

  public function resolveActionPlayerSelection()
  {
    self::setAjaxMode();
    $player_id = self::getArg("player_id", AT_posint, true);
    $this->game->action_resolveActionPlayerSelection($player_id);
    self::ajaxResponse();
  }

  public function resolveActionCardSelection()
  {
    self::setAjaxMode();
    $card_id = self::getArg("card_id", AT_posint, true);
    $card_definition_id = self::getArg("card_definition_id", AT_posint, true);
    $this->game->action_resolveActionCardSelection(
      $card_id,
      $card_definition_id
    );
    self::ajaxResponse();
  }

  public function resolveActionDirection()
  {
    self::setAjaxMode();
    $direction = self::getArg("direction", AT_posint, true);
    $this->game->action_resolveActionDirection($direction);
    self::ajaxResponse();
  }
}
