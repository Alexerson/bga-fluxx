<?php

namespace Fluxx\Cards\Actions;
use Fluxx\Cards\CardFactory;
/*
 * ActionCardFactory: how to create Action Cards
 */
class ActionCardFactory extends CardFactory
{
  public static function getCardFullClassName($uniqueId)
  {
    $name = "Fluxx\Cards\Actions\\" . self::$classes[$uniqueId];
    return $name;
  }

  /*
   * cardClasses : for each card Id, the corresponding class name
   */
  public static $classes = [
    301 => "ActionTrashAKeeper",
    302 => "ActionRotateHands",
    303 => "ActionRulesReset",
    304 => "ActionRandomTax",
    305 => "ActionRockPaperScissors",
    306 => "ActionTrashANewRule",
    307 => "ActionUseWhatYouTake",
    308 => "ActionZapACard",
    309 => "ActionDiscardAndDraw",
    310 => "ActionDraw2AndUseEm",
    311 => "ActionDraw3Play2",
    312 => "ActionEmptyTheTrash",
    313 => "ActionEverybodyGets1",
    314 => "ActionExchangeKeepers",
    315 => "ActionJackpot",
    316 => "ActionLetsDoThatAgain",
    317 => "ActionLetsSimplify",
    318 => "ActionNoLimits",
    319 => "ActionTradeHands",
    320 => "ActionShareTheWealth",
    321 => "ActionStealAKeeper",
    322 => "ActionTakeAnotherTurn",
    323 => "ActionTodaysSpecial",
  ];
}