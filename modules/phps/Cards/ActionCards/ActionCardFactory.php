<?php

namespace Fluxx\Cards\ActionCards;
use Fluxx\Cards\CardFactory;
/*
 * ActionCardFactory: how to create Action Cards
 */
class ActionCardFactory extends CardFactory
{
    public static function getCardFullClassName($uniqueId)
    {
        $name = "Fluxx\Cards\ActionCards\\" . self::$classes[$uniqueId];
        return $name;
    }

    /*
     * cardClasses : for each card Id, the corresponding class name
     */
    public static $classes = [
        301 => "ActionTrashAKeeper",
        302 => "ActionDummy",
        303 => "ActionDummy",
        304 => "ActionDummy",
        305 => "ActionDummy",
        306 => "ActionDummy",
        307 => "ActionDummy",
        308 => "ActionDummy",
        309 => "ActionDummy",
        310 => "ActionDummy",
        311 => "ActionDummy",
        312 => "ActionDummy",
        313 => "ActionDummy",
        314 => "ActionDummy",
        315 => "ActionJackpot",
        316 => "ActionDummy",
        317 => "ActionDummy",
        318 => "ActionDummy",
        319 => "ActionDummy",
        320 => "ActionDummy",
        321 => "ActionDummy",
        322 => "ActionDummy",
        323 => "ActionDummy",
    ];
}
