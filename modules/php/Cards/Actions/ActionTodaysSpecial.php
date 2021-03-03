<?php
namespace Fluxx\Cards\Actions;

use Fluxx\Game\Utils;
use fluxx;

class ActionTodaysSpecial extends ActionCard
{
  public function __construct($cardId, $uniqueId)
  {
    parent::__construct($cardId, $uniqueId);

    $this->name = clienttranslate("Today’s Special!");
    $this->description = clienttranslate(
      "Set your hand aside and draw 3 cards. If today is your birthday, play all 3 cards. If today is a holiday or special anniversary, play 2 of the cards. If it's just another day, play only 1 card. Discard the remainder."
    );
  }

  public $interactionNeeded = "buttons";

  public function resolveArgs()
  {
    return [
      ["value" => "birthday", "label" => clienttranslate("It's my Birthday!")],
      [
        "value" => "holiday",
        "label" => clienttranslate("Holiday or Anniversary"),
      ],
      ["value" => "none", "label" => clienttranslate("Just another day...")],
    ];
  }

  public function resolvedBy($player_id, $args)
  {
    $addInflation = Utils::getActiveInflation() ? 1 : 0;

    $value = $args["value"];
    $nrCardsToDraw = 3;

    switch ($value) {
      case "birthday":
        $nrCardsToPlay = 3;
        break;
      case "holiday":
        $nrCardsToPlay = 2;
        break;
      default:
        $nrCardsToPlay = 1;
    }

    $nrCardsToPlay;

    // determine temp hand to be used
    $tmpHandActive = Utils::getActiveTempHand();
    $tmpHandNext = $tmpHandActive + 1;

    $tmpHandLocation = "tmpHand" + $tmpHandNext;
    // Draw for temp hand
    $tmpCards = $game->performDrawCards($player_id, 
      $nrCardsToDraw + $addInflation,
      true, // $postponeCreeperResolve
      true); // $temporaryDraw
    $tmpCardIds = array_column($tmpCards, "id");
    // Must Play a certain nr of them, depending on the choice made
    $game->setGameStateValue($tmpHandLocation + "ToPlay", $nrCardsToPlay + $addInflation);
    $game->setGameStateValue($tmpHandLocation + "Card", $this->getUniqueId());

    // move cards to temporary hand location
    $game->cards->moveCards($tmpCardIds, $tmpHandLocation, $player_id);

    // done: next play run will detect temp hand active
  
  }
}
