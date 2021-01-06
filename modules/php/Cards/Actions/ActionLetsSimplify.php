<?php
namespace Fluxx\Cards\Actions;

use Fluxx\Game\Utils;
use Fluxx\Cards\Rules;
use fluxx;

class ActionLetsSimplify extends ActionCard
{
  public function __construct($cardId, $uniqueId)
  {
    parent::__construct($cardId, $uniqueId);

    $this->name = clienttranslate("Let’s Simplify");
    $this->description = clienttranslate(
      "Discard your choice of up to half (rounded up) of the New Rule cards in play."
    );
  }

  public $interactionNeeded = "rulesSelection";

  public function immediateEffectOnPlay($player_id)
  {
    // nothing now, needs to go to resolve action state
    // TODO: check how many rules are in play: if none, skip
    return parent::immediateEffectOnPlay($player_id);
  }

  public function resolvedBy($player_id, $args)
  {
    $option = $args["option"];
    $cardIdsSelected = $args["cardIdsSelected"];
    // verify args has card ids, and it is all Rule in play
    // (or that no rules are in play and args is empty)
    $game = Utils::getGame();
    $rulesInPlay = $game->cards->countCardInLocation("rules");
    if ($rulesInPlay == 0) {
      // no rules in play anywhere, this action does nothing
      return;
    }

    if (count($cardIdsSelected) > ceil($rulesInPlay / 2)) {
      Utils::throwInvalidUserAction(
        fluxx::totranslate(
          "You must select up to half (rounded up) of the New Rule cards in play"
        )
      );
    }

    $cardsSelected = [];
    foreach ($cardIdsSelected as $cardId) {
      $cardSelected = $game->cards->getCard($cardId);
      if ($cardSelected == null || $cardSelected["location"] != "rules") {
        Utils::throwInvalidUserAction(
          fluxx::totranslate(
            "You must select up to half (rounded up) of the New Rule cards in play"
          )
        );
      }
      $cardsSelected[$cardId] = $cardSelected;
    }

    // discard these rules from play
    foreach ($cardsSelected as $cardId => $cardSelected) {
      $rule = RuleCardFactory::getCard($cardId, $cardSelected["type_arg"]);
      $rule->immediateEffectOnDiscard($player_id);

      $fromTarget = $cardSelected["location_arg"];
      $game->removeCardFromPlay(
        $player_id,
        $cardId,
        $cardSelected["type"],
        $fromTarget
      );
    }
  }
}
