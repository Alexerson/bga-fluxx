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
 * states.inc.php
 *
 * fluxx game states description
 *
 */

/*
Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
in a very easy way from this configuration file.

Please check the BGA Studio presentation about game state to understand this, and associated documentation.
 
Summary:

States types:
_ activeplayer: in this type of state, we expect some action from the active player.
_ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
_ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
_ manager: special type for initial and final state

Arguments of game states:
_ name: the name of the GameState, in order you can recognize it on your own code.
_ description: the description of the current game state is always displayed in the action status bar on
the top of the game. Most of the time this is useless for game state with "game" type.
_ descriptionmyturn: the description of the current game state when it's your turn.
_ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
_ action: name of the method to call when this game state become the current game state. Usually, the
action method is prefixed by "st" (ex: "stMyGameStateName").
_ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
_ transitions: the transitions are the possible paths to go from a game state to another. You must name
transitions in order to use transition names in "nextState" PHP method, and use IDs to
specify the next game state for each transition.
_ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
client side to be used on "onEnteringState" or to set arguments in the gamestate description.
_ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
method).
 */

//    !! It is not a good idea to modify this file when a game is running !!

// Define Constants
if (!defined("STATE_DRAWCARDS")) {
    define("GAME_SETUP", 1);
    define("GAME_END", 99);
    define("STATE_DRAWCARDS", 10);
    define("STATE_PLAYCARDS", 20);
    define("STATE_RESOLVEACTION", 30);
    define("STATE_HANDLIMIT", 40);
    define("STATE_KEEPERLIMIT", 50);
    define("STATE_NEXTPLAYER", 60);
}

$machinestates = [
    // The initial state. Please do not modify.
    GAME_SETUP => [
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => ["" => STATE_DRAWCARDS],
    ],

    STATE_DRAWCARDS => [
        "name" => "cardsDraw",
        "description" => "",
        "type" => "game",
        "action" => "stCardsDraw",
        // "args" => "argsCardsDraw",
        "transitions" => [
            "goPlayCards" => STATE_PLAYCARDS,
            "endGame" => GAME_END,
        ],
    ],

    STATE_PLAYCARDS => [
        "name" => "cardsPlay",
        "description" => clienttranslate(
            '${actplayer} must play ${nb} card(s)'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must play ${nb} card(s)'
        ),
        "type" => "activeplayer",
        "args" => "argsCardsPlay",
        "possibleactions" => ["playCard"],
        "transitions" => [
            "enforceLimits" => STATE_HANDLIMIT,
            "donePlayingCards" => STATE_HANDLIMIT,
            "resolveActionCard" => STATE_RESOLVEACTION,
            "continuePlay" => STATE_PLAYCARDS,
            "endGame" => GAME_END,
        ],
    ],

    STATE_RESOLVEACTION => [
        "name" => "actionResolve",
        "description" => clienttranslate(
            '${actplayer} must resolve their action'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must resolve your action'
        ),
        "type" => "activeplayer",
        "args" => "argResolveAction",
        "action" => "stResolveAction",
        "possibleactions" => ["resolveAction"],
        "transitions" => [
            "resolvedAction" => STATE_PLAYCARDS,
            "donePlayingCards" => STATE_HANDLIMIT,
            "endGame" => GAME_END,
        ],
    ],

    STATE_HANDLIMIT => [
        "name" => "handLimit",
        "description" => clienttranslate(
            'Other players must discard cards for Hand Limit ${limit}'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must discard ${nb} cards for Hand Limit ${limit}'
        ),
        "type" => "multipleactiveplayer",
        "args" => "argHandLimit",
        "action" => "stHandLimit",
        "possibleactions" => ["discardCards"],
        "transitions" => ["" => STATE_KEEPERLIMIT],
    ],

    STATE_KEEPERLIMIT => [
        "name" => "keeperLimit",
        "description" => clienttranslate(
            'Other players must remove keepers for Keeper Limit ${limit}'
        ),
        "descriptionmyturn" => clienttranslate(
            '${you} must remove ${nb} keepers for Keeper Limit ${limit}'
        ),
        "type" => "multipleactiveplayer",
        "args" => "argKeeperLimit",
        "action" => "stKeeperLimit",
        "possibleactions" => ["discardKeepers"],
        "transitions" => ["" => STATE_NEXTPLAYER],
    ],

    STATE_NEXTPLAYER => [
        "name" => "nextPlayer",
        "description" => "",
        "type" => "game",
        "action" => "stNextPlayer",
        "updateGameProgression" => true,
        "transitions" => ["nextPlayer" => STATE_DRAWCARDS],
    ],

    /*
Examples:

2 => array(
"name" => "nextPlayer",
"description" => '',
"type" => "game",
"action" => "stNextPlayer",
"updateGameProgression" => true,
"transitions" => array( "endGame" => 99, "nextPlayer" => 10 )
),

10 => array(
"name" => "playerTurn",
"description" => clienttranslate('${actplayer} must play a card or pass'),
"descriptionmyturn" => clienttranslate('${you} must play a card or pass'),
"type" => "activeplayer",
"possibleactions" => array( "playCard", "pass" ),
"transitions" => array( "playCard" => 2, "pass" => 2 )
),

 */

    // Final state.
    // Please do not modify (and do not overload action/args methods).
    99 => [
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd",
    ],
];
