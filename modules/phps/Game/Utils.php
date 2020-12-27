<?php
namespace Fluxx\Game;
use fluxx;

class Utils
{
    public static function getGame()
    {
        return fluxx::get();
    }
}
