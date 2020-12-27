<?php

namespace Fluxx\Cards\NewRules;
use Fluxx\Cards\CardFactory;
/*
 * RuleCardFactory: how to create Rule Cards
 */
class RuleCardFactory extends CardFactory
{
    public static function getCardFullClassName($uniqueId)
    {
        $name = "Fluxx\Cards\NewRules\\" . self::$classes[$uniqueId];
        return $name;
    }

    /*
     * cardClasses : for each card Id, the corresponding class name
     */
    public static $classes = [
        201 => "RulePlay2",
        202 => "RulePlay3",
        203 => "RulePlay4",
        204 => "RulePlayAll",
        205 => "RulePlayAllBut1",
        206 => "RuleDraw2",
        207 => "RuleDraw3",
        208 => "RuleDraw4",
        209 => "RuleDraw5",
        210 => "RuleKeeperLimit2",
        211 => "RuleKeeperLimit3",
        212 => "RuleKeeperLimit4",
        213 => "RuleHandLimit0",
        214 => "RuleHandLimit1",
        215 => "RuleHandLimit2",
        216 => "RuleCard",
        217 => "RuleCard",
        218 => "RuleCard",
        219 => "RuleCard",
        220 => "RuleCard",
        221 => "RuleCard",
        222 => "RuleCard",
        223 => "RuleCard",
        224 => "RuleCard",
        225 => "RuleCard",
        226 => "RuleCard",
        227 => "RuleCard",
    ];
}
