<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * fluxx implementation : © Alexandre Spaeth <alexandre.spaeth@hey.com> & Julien Rossignol <tacotaco.dev@gmail.com>
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
}
