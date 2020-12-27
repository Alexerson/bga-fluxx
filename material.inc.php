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
 * material.inc.php
 *
 * fluxx game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */

$this->typesDefinitions = [
    [
        "label" => "keeper",
        "name" => self::_("keeper"),
        "nbCards" => 19,
    ],
    [
        "label" => "goal",
        "name" => self::_("goal"),
        "nbCards" => 30,
    ],
    [
        "label" => "rule",
        "name" => self::_("rule"),
        "nbCards" => 27,
    ],
    [
        "label" => "action",
        "name" => self::_("action"),
        "nbCards" => 23,
    ],
];

$this->cardsDefinitions = [
    ////// Keepers
    1 => [
        "type" => "keeper",
        "name" => clienttranslate("Sleep"),
    ],
    2 => [
        "type" => "keeper",
        "name" => clienttranslate("The Brain"),
    ],
    3 => [
        "type" => "keeper",
        "name" => clienttranslate("Bread"),
    ],
    4 => [
        "type" => "keeper",
        "name" => clienttranslate("Chocolate"),
    ],
    5 => [
        "type" => "keeper",
        "name" => clienttranslate("Cookies"),
    ],
    6 => [
        "type" => "keeper",
        "name" => clienttranslate("Milk"),
    ],
    7 => [
        "type" => "keeper",
        "name" => clienttranslate("Money"),
    ],
    8 => [
        "type" => "keeper",
        "name" => clienttranslate("The Eye"),
    ],
    9 => [
        "type" => "keeper",
        "name" => clienttranslate("The Moon"),
    ],
    10 => [
        "type" => "keeper",
        "name" => clienttranslate("The Rocket"),
    ],
    11 => [
        "type" => "keeper",
        "name" => clienttranslate("The Toaster"),
    ],
    12 => [
        "type" => "keeper",
        "name" => clienttranslate("Television"),
    ],
    13 => [
        "type" => "keeper",
        "name" => clienttranslate("Time"),
    ],
    14 => [
        "type" => "keeper",
        "name" => clienttranslate("Dreams"),
    ],
    15 => [
        "type" => "keeper",
        "name" => clienttranslate("Music"),
    ],
    16 => [
        "type" => "keeper",
        "name" => clienttranslate("The Party"),
    ],
    17 => [
        "type" => "keeper",
        "name" => clienttranslate("The Sun"),
    ],
    18 => [
        "type" => "keeper",
        "name" => clienttranslate("Love"),
    ],
    19 => [
        "type" => "keeper",
        "name" => clienttranslate("Peace"),
    ],

    ////// Goals
    101 => [
        "type" => "goal",
        "name" => clienttranslate("10 Cards in Hand"),
        "description" => clienttranslate(
            "If someone has 10 or more cards in his or her hand, then the player with the most cards in hand wins. In the event of a tie, continue playing until a clear winner emerges."
        ),
    ],
    102 => [
        "type" => "goal",
        "name" => clienttranslate("5 Keepers"),
        "description" => clienttranslate(
            "If someone has 5 or more Keepers on the table, then the player with the most Keepers in play wins. In the event of a tie, continue playing until a clear winner emerges."
        ),
    ],
    103 => [
        "type" => "goal",
        "name" => clienttranslate("The Appliances"),
        "subtitle" => clienttranslate("The Toaster + Television"),
    ],
    104 => [
        "type" => "goal",
        "name" => clienttranslate("Baked Goods"),
        "subtitle" => clienttranslate("Bread + Cookies"),
    ],
    105 => [
        "type" => "goal",
        "name" => clienttranslate("Bed Time"),
        "subtitle" => clienttranslate("Sleep + Time"),
    ],
    106 => [
        "type" => "goal",
        "name" => clienttranslate("The Brain (No TV)"),
        "description" => clienttranslate(
            "If no one has Television on the table, the player with The Brain on the table wins."
        ),
    ],
    107 => [
        "type" => "goal",
        "name" => clienttranslate("Bread & Chocolate"),
        "subtitle" => clienttranslate("Bread + Chocolate"),
    ],
    108 => [
        "type" => "goal",
        "name" => clienttranslate("Can’t Buy Me Love"),
        "subtitle" => clienttranslate("Money + Love"),
    ],
    109 => [
        "type" => "goal",
        "name" => clienttranslate("Chocolate Cookies"),
        "subtitle" => clienttranslate("Chocolate + Cookies"),
    ],
    110 => [
        "type" => "goal",
        "name" => clienttranslate("Chocolate Milk"),
        "subtitle" => clienttranslate("Chocolate + Milk"),
    ],
    111 => [
        "type" => "goal",
        "name" => clienttranslate("Day Dreams"),
        "subtitle" => clienttranslate("The Sun + Dreams"),
    ],
    112 => [
        "type" => "goal",
        "name" => clienttranslate("Dreamland"),
        "subtitle" => clienttranslate("Sleep + Dreams"),
    ],
    113 => [
        "type" => "goal",
        "name" => clienttranslate("The Eye of the Beholder"),
        "subtitle" => clienttranslate("The Eye + Love"),
    ],
    114 => [
        "type" => "goal",
        "name" => clienttranslate("Great Theme Song"),
        "subtitle" => clienttranslate("Music + Television"),
    ],
    115 => [
        "type" => "goal",
        "name" => clienttranslate("Hearts & Minds"),
        "subtitle" => clienttranslate("Love + The Brain"),
    ],
    116 => [
        "type" => "goal",
        "name" => clienttranslate("Hippyism"),
        "subtitle" => clienttranslate("Peace + Love"),
    ],
    117 => [
        "type" => "goal",
        "name" => clienttranslate("Lullaby"),
        "subtitle" => clienttranslate("Sleep + Music"),
    ],
    118 => [
        "type" => "goal",
        "name" => clienttranslate("Milk & Cookies"),
        "subtitle" => clienttranslate("Milk + Cookies"),
    ],
    119 => [
        "type" => "goal",
        "name" => clienttranslate("The Mind’s Eye"),
        "subtitle" => clienttranslate("The Brain + The Eye"),
    ],
    120 => [
        "type" => "goal",
        "name" => clienttranslate("Night & Day"),
        "subtitle" => clienttranslate("The Sun + The Moon"),
    ],
    121 => [
        "type" => "goal",
        "name" => clienttranslate("Party Snacks"),
        "subtitle" => clienttranslate("The Party + at least 1 food Keeper"),
    ],
    122 => [
        "type" => "goal",
        "name" => clienttranslate("Party Time!"),
        "subtitle" => clienttranslate("The Party + Time"),
    ],
    123 => [
        "type" => "goal",
        "name" => clienttranslate("Rocket Science"),
        "subtitle" => clienttranslate("The Rocket + The Brain"),
    ],
    124 => [
        "type" => "goal",
        "name" => clienttranslate("Rocket to the Moon"),
        "subtitle" => clienttranslate("The Rocket + The Moon"),
    ],
    125 => [
        "type" => "goal",
        "name" => clienttranslate("Squishy Chocolate"),
        "subtitle" => clienttranslate("Chocolate + The Sun"),
    ],
    126 => [
        "type" => "goal",
        "name" => clienttranslate("Time is Money"),
        "subtitle" => clienttranslate("Time + Money"),
    ],
    127 => [
        "type" => "goal",
        "name" => clienttranslate("Toast"),
        "subtitle" => clienttranslate("Bread + The Toaster"),
    ],
    128 => [
        "type" => "goal",
        "name" => clienttranslate("Turn it Up!"),
        "subtitle" => clienttranslate("Music + The Party"),
    ],
    129 => [
        "type" => "goal",
        "name" => clienttranslate("Winning the Lottery"),
        "subtitle" => clienttranslate("Dreams + Money"),
    ],
    130 => [
        "type" => "goal",
        "name" => clienttranslate("World Peace"),
        "subtitle" => clienttranslate("Dreams + Peace"),
    ],

    ////// New Rules
    201 => [
        "type" => "rule",
        "ruleType" => "playRule",
        "name" => clienttranslate("Play 2"),
        "subtitle" => clienttranslate("Replaces Play Rule"),
        "description" => clienttranslate(
            "Play 2 cards per turn. If you have fewer than that, play all your cards."
        ),
    ],
    202 => [
        "type" => "rule",
        "ruleType" => "playRule",
        "name" => clienttranslate("Play 3"),
        "subtitle" => clienttranslate("Replaces Play Rule"),
        "description" => clienttranslate(
            "Play 3 cards per turn. If you have fewer than that, play all your cards."
        ),
    ],
    203 => [
        "type" => "rule",
        "ruleType" => "playRule",
        "name" => clienttranslate("Play 4"),
        "subtitle" => clienttranslate("Replaces Play Rule"),
        "description" => clienttranslate(
            "Play 4 cards per turn. If you have fewer than that, play all your cards."
        ),
    ],
    204 => [
        "type" => "rule",
        "ruleType" => "playRule",
        "name" => clienttranslate("Play All"),
        "subtitle" => clienttranslate("Replaces Play Rule"),
        "description" => clienttranslate("Play all your cards per turn."),
    ],
    205 => [
        "type" => "rule",
        "ruleType" => "playRule",
        "name" => clienttranslate("Play All But 1"),
        "subtitle" => clienttranslate("Replaces Play Rule"),
        "description" => clienttranslate(
            "Play all but 1 of your cards. If you started with no cards in your hand and only drew 1, draw an extra card."
        ),
    ],
    206 => [
        "type" => "rule",
        "ruleType" => "drawRule",
        "name" => clienttranslate("Draw 2"),
        "subtitle" => clienttranslate("Replaces Draw Rule"),
        "description" => clienttranslate(
            "Draw 2 cards per turn. If you just played this card, draw extra cards as needed to reach 2 cards drawn."
        ),
    ],
    207 => [
        "type" => "rule",
        "ruleType" => "drawRule",
        "name" => clienttranslate("Draw 3"),
        "subtitle" => clienttranslate("Replaces Draw Rule"),
        "description" => clienttranslate(
            "Draw 3 cards per turn. If you just played this card, draw extra cards as needed to reach 3 cards drawn."
        ),
    ],
    208 => [
        "type" => "rule",
        "ruleType" => "drawRule",
        "name" => clienttranslate("Draw 4"),
        "subtitle" => clienttranslate("Replaces Draw Rule"),
        "description" => clienttranslate(
            "Draw 4 cards per turn. If you just played this card, draw extra cards as needed to reach 4 cards drawn."
        ),
    ],
    209 => [
        "type" => "rule",
        "ruleType" => "drawRule",
        "name" => clienttranslate("Draw 5"),
        "subtitle" => clienttranslate("Replaces Draw Rule"),
        "description" => clienttranslate(
            "Draw 5 cards per turn. If you just played this card, draw extra cards as needed to reach 5 cards drawn."
        ),
    ],
    210 => [
        "type" => "rule",
        "ruleType" => "keepersLimit",
        "name" => clienttranslate("Keeper Limit 2"),
        "subtitle" => clienttranslate("Replaces Keeper Limit"),
        "description" => clienttranslate(
            'If it isn\'t your turn, you can only have 2 Keepers in play. Discard extras immediately. You may acquire new Keepers during your turn as long as you discard down to 2 when your turn ends.'
        ),
    ],
    211 => [
        "type" => "rule",
        "ruleType" => "keepersLimit",
        "name" => clienttranslate("Keeper Limit 3"),
        "subtitle" => clienttranslate("Replaces Keeper Limit"),
        "description" => clienttranslate(
            'If it isn\'t your turn, you can only have 3 Keepers in play. Discard extras immediately. You may acquire new Keepers during your turn as long as you discard down to 3 when your turn ends.'
        ),
    ],
    212 => [
        "type" => "rule",
        "ruleType" => "keepersLimit",
        "name" => clienttranslate("Keeper Limit 4"),
        "subtitle" => clienttranslate("Replaces Keeper Limit"),
        "description" => clienttranslate(
            'If it isn\'t your turn, you can only have 4 Keepers in play. Discard extras immediately. You may acquire new Keepers during your turn as long as you discard down to 4 when your turn ends.'
        ),
    ],
    213 => [
        "type" => "rule",
        "ruleType" => "handLimit",
        "name" => clienttranslate("Hand Limit 0"),
        "subtitle" => clienttranslate("Replaces Hand Limit"),
        "description" => clienttranslate(
            'If it isn\'t your turn, you can only have 0 cards in your hand. Discard extras immediately. During your turn, this rule does not apply to you, after your turn ends, discard down to 0 cards'
        ),
    ],
    214 => [
        "type" => "rule",
        "ruleType" => "handLimit",
        "name" => clienttranslate("Hand Limit 1"),
        "subtitle" => clienttranslate("Replaces Hand Limit"),
        "description" => clienttranslate(
            'If it isn\'t your turn, you can only have 1 card in your hand. Discard extras immediately. During your turn, this rule does not apply to you, after your turn ends, discard down to 1 card'
        ),
    ],
    215 => [
        "type" => "rule",
        "ruleType" => "handLimit",
        "name" => clienttranslate("Hand Limit 2"),
        "subtitle" => clienttranslate("Replaces Hand Limit"),
        "description" => clienttranslate(
            'If it isn\'t your turn, you can only have 2 cards in your hand. Discard extras immediately. During your turn, this rule does not apply to you, after your turn ends, discard down to 2 cards'
        ),
    ],
    216 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("No-Hand Bonus"),
        "subtitle" => clienttranslate("Start-of-Turn Event"),
        "description" => clienttranslate(
            "If empty handed, draw 3 cards before observing the current draw rule."
        ),
    ],
    217 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("Party Bonus"),
        "subtitle" => clienttranslate("Takes Instant Effect"),
        "description" => clienttranslate(
            "If one player has the Party on the table, all players draw 1 extra card and play 1 extra card during their turn."
        ),
    ],
    218 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("Poor Bonus"),
        "subtitle" => clienttranslate("Takes Instant Effect"),
        "description" => clienttranslate(
            "If one player has fewer Keepers in play than anyone else, the number of cards drawn by this player is increased by 1. In the event of a tie, no player receives the bonus."
        ),
    ],
    219 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("Rich Bonus"),
        "subtitle" => clienttranslate("Takes Instant Effect"),
        "description" => clienttranslate(
            "If one player has more Keepers in play than anyone else, the number of cards drawn by this player is increased by 1. In the event of a tie, no player receives the bonus."
        ),
    ],
    220 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("Double Agenda"),
        "subtitle" => clienttranslate("Takes Instant Effect"),
        "description" => clienttranslate(
            "A second Goal can now be played. After this, whoever plays a new Goal must choose which of the current Goal to discard. You win if you satisfy either Goal."
        ),
    ],
    221 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("First Play Random"),
        "subtitle" => clienttranslate("Takes Instant Effect"),
        "description" => clienttranslate(
            "The first card you play must be chosen at random from your hand by the player on your left. Ignore this rule if the current Rule card allow you to play only one card."
        ),
    ],
    222 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("Get On With It!"),
        "subtitle" => clienttranslate("Free action"),
        "description" => clienttranslate(
            "Before your final play, if you are not empty handed, you may discard your entire hand and draw 3 cards. Your turn then ends immediately."
        ),
    ],
    223 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("Goal Mill"),
        "subtitle" => clienttranslate("Free action"),
        "description" => clienttranslate(
            "Once during your turn, discard as many of your Goal cards as you choose, then draw that many cards."
        ),
    ],
    224 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("Inflation"),
        "subtitle" => clienttranslate("Takes Instant Effect"),
        "description" => clienttranslate(
            "Any time a numeral is seen on another card, add one to that numeral. For example, 1 becomes 2, while one remains one. Yes, this affects the Basic Rules."
        ),
    ],
    225 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("Mystery Play"),
        "subtitle" => clienttranslate("Free action"),
        "description" => clienttranslate(
            "Once during your turn you may take the top card from the draw pile and play it immediately."
        ),
    ],
    226 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("Recycling"),
        "subtitle" => clienttranslate("Free action"),
        "description" => clienttranslate(
            "Once during your turn, you may discard one of your Keepers from the table and draw 3 extra cards."
        ),
    ],
    227 => [
        "type" => "rule",
        "ruleType" => "others",
        "name" => clienttranslate("Swap Plays for Draws"),
        "subtitle" => clienttranslate("Takes Instant Effect"),
        "description" => clienttranslate(
            "During your turn, you may decide to play no more cards and instead draw as many cards as you have plays remaining. If Play All, draw as many cards as you hold."
        ),
    ],

    ////// Actions
    301 => [
        "type" => "action",
        "name" => clienttranslate("Trash a Keeper"),
        "description" => clienttranslate(
            "Take a Keeper from in front of any player and put it on the discard pile. If no one has any Keepers in play, nothing happends when you play this card."
        ),
    ],
    302 => [
        "type" => "action",
        "name" => clienttranslate("Rotate Hands"),
        "description" => clienttranslate(
            "All players pass their hands to the player next to them. You decide which direction."
        ),
    ],
    303 => [
        "type" => "action",
        "name" => clienttranslate("Rules Reset"),
        "description" => clienttranslate(
            "Reset to the Basic Rules. Discard all New Rule cards, and leave only the Basic Rules in play. Do not discard the current Goal."
        ),
    ],
    304 => [
        "type" => "action",
        "name" => clienttranslate("Random Tax"),
        "description" => clienttranslate(
            "Take 1 card at random from the hand of each other player and add these cardsto your own hand."
        ),
    ],
    305 => [
        "type" => "action",
        "name" => clienttranslate("Rock-Paper-Scissors Showdown"),
        "description" => clienttranslate(
            'Challenge another player to a 3-round Rock-Paper-Scissors tournament. Winner takes loser\'s entire hand of cards.'
        ),
    ],
    306 => [
        "type" => "action",
        "name" => clienttranslate("Trash A New Rule"),
        "description" => clienttranslate(
            "Select one of the New Rule cards in play and place it in the discard pile."
        ),
    ],
    307 => [
        "type" => "action",
        "name" => clienttranslate("Use What You Take"),
        "description" => clienttranslate(
            'Take a card at random form another player\'s hand, and play it.'
        ),
    ],
    308 => [
        "type" => "action",
        "name" => clienttranslate("Zap a Card!"),
        "description" => clienttranslate(
            "Choose any card in play, anywhere on the table (except for the Basic Rules) and add it to your hand."
        ),
    ],
    309 => [
        "type" => "action",
        "name" => clienttranslate("Discard and Draw"),
        "description" => clienttranslate(
            "Discard your entire hand, then draw as many cards as you discarded. Do not count this card when determining how many cards to draw."
        ),
    ],
    310 => [
        "type" => "action",
        "name" => clienttranslate("Draw 2 and Use ‘Em"),
        "description" => clienttranslate(
            "Set your hand aside. Draw 2 cards, play them in any order you choose, then pick up your hand and continue with your turn. This card, and all cards played because of it, are counted as a single play."
        ),
    ],
    311 => [
        "type" => "action",
        "name" => clienttranslate("Draw 3, Play 2 of Them"),
        "description" => clienttranslate(
            "Set your hand aside. Draw 3 cards and play 2 them. Discard the last card, then pick up your hand and continue with your turn. This card, and all cards played because of it, are counted as a single play."
        ),
    ],
    312 => [
        "type" => "action",
        "name" => clienttranslate("Empty the Trash"),
        "description" => clienttranslate(
            "Start a new discard pile with this card and shuffle the rest of the discard pile back into the draw pile."
        ),
    ],
    313 => [
        "type" => "action",
        "name" => clienttranslate("Everybody gets 1"),
        "description" => clienttranslate(
            "Set your hand aside. Count the number of players in the game (including yourself). Draw enough cards to give 1 to each player, and then distribute them evenly amongst all the players. You decide who gets what."
        ),
    ],
    314 => [
        "type" => "action",
        "name" => clienttranslate("Exchange Keepers"),
        "description" => clienttranslate(
            "Pick any Keeper another player has on the table and exchange it for one you have on the table. <be/> If you have no Keepers in play, or if no one else has a Keeper, nothing happens."
        ),
    ],
    315 => [
        "type" => "action",
        "name" => clienttranslate("Jackpot!"),
        "description" => clienttranslate("Draw 3 extra cards!"),
    ],
    316 => [
        "type" => "action",
        "name" => clienttranslate("Let’s Do That Again!"),
        "description" => clienttranslate(
            'Seatch through the discard pile. Take any Action or New Rule card you wish and immediately play it. Anyone may look through the discard pile at any time, but the order of what\'s in the pile should never be changed.'
        ),
    ],
    317 => [
        "type" => "action",
        "name" => clienttranslate("Let’s Simplify"),
        "description" => clienttranslate(
            "Discard your choice of up to half (rounded up) of the New Rule cards in play."
        ),
    ],
    318 => [
        "type" => "action",
        "name" => clienttranslate("No Limits"),
        "description" => clienttranslate(
            "Discard all Hand and Keeper Limits currently in play."
        ),
    ],
    319 => [
        "type" => "action",
        "name" => clienttranslate("Trade Hands"),
        "description" => clienttranslate(
            "Trade your hand for the hand of one of your opponents. This is one of those times when you can get something for nothing!"
        ),
    ],
    320 => [
        "type" => "action",
        "name" => clienttranslate("Share the Wealth"),
        "description" => clienttranslate(
            "Gather up all the Keepers on the table, shuffle them together, and deal them back out to all players, starting with yourself. These go immediately into play in front of their new owners. Everyone will probably end up with a different number of Keepers in play than they started with."
        ),
    ],
    321 => [
        "type" => "action",
        "name" => clienttranslate("Steal a Keeper"),
        "description" => clienttranslate(
            "Steal a Keeper from in front of another player, and add it to your collenction of Keepers on the table."
        ),
    ],
    322 => [
        "type" => "action",
        "name" => clienttranslate("Take Another Turn"),
        "description" => clienttranslate(
            "Take another tuen as soon as you finish this one. The maximum number of turns you can take in a row using this card is two."
        ),
    ],
    323 => [
        "type" => "action",
        "name" => clienttranslate("Today’s Special!"),
        "description" => clienttranslate(
            'Set your hand aside and draw 3 cards. If today is your birthday, play all 3 cards. If today is a holiday or special anniversary, play 2 of the cards. If it\'s just another day, play only 1 card. Discard the remainder.'
        ),
    ],
];
