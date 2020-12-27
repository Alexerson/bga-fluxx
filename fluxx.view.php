<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * fluxx implementation : © Alexandre Spaeth <alexandre.spaeth@hey.com> & Julien Rossignol <tacotaco.dev@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * fluxx.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in fluxx_fluxx.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */

require_once APP_BASE_PATH . "view/common/game.view.php";

class view_fluxx_fluxx extends game_view
{
    public function getGameName()
    {
        return "fluxx";
    }
    public function build_page($viewArgs)
    {
        $template = self::getGameName() . "_" . self::getGameName();

        // Get current player ID & all players info
        global $g_user;
        $current_player_id = $g_user->get_id();
        $players = $this->game->loadPlayersBasicInfos();

        // Translations
        $this->tpl["MY_HAND"] = clienttranslate("My hand");
        $this->tpl["MY_KEEPERS"] = clienttranslate("My keepers");
        $this->tpl["HAND_COUNT"] = clienttranslate("# cards in hand");
        $this->tpl["KEEPERS_COUNT"] = clienttranslate("# keepers on table");
        $this->tpl["RULES"] = clienttranslate("Rules");
        $this->tpl["GOAL"] = clienttranslate("Goal");

        // This will inflate players keepers block
        $player_info = $players[$current_player_id];
        $this->tpl["CURRENT_PLAYER_ID"] = $current_player_id;
        $this->tpl["CURRENT_PLAYER_COLOR"] = $player_info["player_color"];

        $this->page->begin_block($template, "keepers");
        foreach ($players as $player_id => $player_info) {
            if ($player_id != $current_player_id) {
                $this->page->insert_block("keepers", [
                    "PLAYER_ID" => $player_id,
                    "PLAYER_NAME" => $player_info["player_name"],
                    "PLAYER_COLOR" => $player_info["player_color"],
                ]);
            }
        }
    }
}
