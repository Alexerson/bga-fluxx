<?php

namespace Fluxx\Cards;

/*
 * CardFactory: how to create Cards
 */
abstract class CardFactory extends \APP_GameClass
{
    public static function getCard($cardId, $cardDefinitionId)
    {
        return self::resToObject($cardId, $cardDefinitionId);
    }

    public static function getCardUniqueId($card)
    {
        //return ($card['type'] * 100) + ($card['type_arg']-0);
        return $card["type_arg"];
    }

    // to be set by derived factories for specific Card types
    public static function getCardFullClassName($uniqueId)
    {
        return null;
    }

    private static function resToObject($cardId, $cardDefinitionId)
    {
        //$uniqueId = self::getCardUniqueId($cardRow);
        $uniqueId = $cardDefinitionId;
        $name = static::getCardFullClassName($uniqueId);
        $card = new $name($cardId, $uniqueId);
        return $card;
    }
}
