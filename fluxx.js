/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * fluxx implementation : © Alexandre Spaeth <alexandre.spaeth@hey.com> & Julien Rossignol <tacotaco.dev@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * fluxx.js
 *
 * fluxx user interface script
 *
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
  "dojo",
  "dojo/_base/declare",
  "ebg/core/gamegui",
  "ebg/counter",
  "ebg/stock",
], function (dojo, declare) {
  return declare("bgagame.fluxx", ebg.core.gamegui, {
    constructor: function () {
      this.CARD_WIDTH = 166;
      this.CARD_HEIGHT = 258;
      this.CARDS_SPRITES_PATH = g_gamethemeurl + "img/cards.png";
      this.CARDS_SPRITES_PER_ROW = 17;

      this.KEEPER_WIDTH = 83;
      this.KEEPER_HEIGHT = 129;
      this.KEEPERS_SPRITES_PATH = g_gamethemeurl + "img/keepers.png";
      this.KEEPERS_SPRITES_PER_ROW = 10;

      this.CARDS_TYPES = {
        keeper: { count: 19, spriteOffset: 0, materialOffset: 1 },
        goal: { count: 30, spriteOffset: 19, materialOffset: 101 },
        rule: { count: 27, spriteOffset: 19 + 30, materialOffset: 201 },
        action: { count: 23, spriteOffset: 19 + 30 + 27, materialOffset: 301 },
      };
    },

    /*
            setup:
            
            This method sets up the game user interface according to the current game 
            situation specified in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
    setup: function (gamedatas) {
      // Setup all stocks and restore existing state
      this.handStock = this.createCardStock("handStock", 2, [
        "keeper",
        "goal",
        "rule",
        "action",
      ]);
      this.addCardsToStock(this.handStock, this.gamedatas.hand);

      this.discardStock = this.createCardStock("discardStock", 0, [
        "keeper",
        "goal",
        "rule",
        "action",
      ]);
      if (this.gamedatas.discard) {
        this.addCardsToStock(this.discardStock, [this.gamedatas.discard]);
      }
      this.discardStock.setOverlap(0.00001, 0);
      this.discardStock.item_margin = 0;

      this.rulesStock = {};

      this.rulesStock.drawRule = this.createCardStock("drawRuleStock", 0, [
        "rule",
      ]);
      this.rulesStock.playRule = this.createCardStock("playRuleStock", 0, [
        "rule",
      ]);
      this.rulesStock.keepersLimit = this.createCardStock(
        "keepersLimitStock",
        0,
        ["rule"]
      );
      this.rulesStock.handLimit = this.createCardStock("handLimitStock", 0, [
        "rule",
      ]);
      this.rulesStock.others = this.createCardStock("othersStock", 0, ["rule"]);
      this.addCardsToStock(
        this.rulesStock.drawRule,
        this.gamedatas.rules.drawRule
      );
      this.addCardsToStock(
        this.rulesStock.playRule,
        this.gamedatas.rules.playRule
      );
      this.addCardsToStock(
        this.rulesStock.handLimit,
        this.gamedatas.rules.handLimit
      );
      this.addCardsToStock(
        this.rulesStock.keepersLimit,
        this.gamedatas.rules.keepersLimit
      );
      this.addCardsToStock(this.rulesStock.others, this.gamedatas.rules.others);

      this.goalsStock = this.createCardStock("goalsStock", 0, ["goal"]);
      this.addCardsToStock(this.goalsStock, this.gamedatas.goals);
      this.goalsStock.setOverlap(50, 0);

      this.keepersStock = {};
      for (var player_id in gamedatas.players) {
        // Setting up player keepers stocls
        this.keepersStock[player_id] = this.createKeepersStock(
          "keepersStock" + player_id,
          0
        );
        this.addCardsToStock(
          this.keepersStock[player_id],
          this.gamedatas.keepers[player_id]
        );

        // Setting up player boards
        var player_board_div = $("player_board_" + player_id);
        dojo.place(
          this.format_block("jstpl_player_board", {
            id: player_id,
          }),
          player_board_div
        );

        this.setDeckCount(this.gamedatas.deckCount);
        this.setDiscardCount(this.gamedatas.discardCount);
        this.setPlayerBoardHandCount(
          player_id,
          this.gamedatas.handsCount[player_id]
        );
        this.setPlayerBoardKeepersCount(
          player_id,
          this.keepersStock[player_id].count()
        );
      }

      dojo.connect(
        this.handStock,
        "onChangeSelection",
        this,
        "onSelectHandCard"
      );

      // Setup game notifications to handle (see "setupNotifications" method below)
      this.setupNotifications();

      console.log("Setup completed!");
    },

    ///////////////////////////////////////////////////
    //// Game & client states

    // onEnteringState: this method is called each time we are entering into a new game state.
    //                  You can use this method to perform some user interface changes at this moment.
    //
    onEnteringState: function (stateName, args) {
      this.currentState = stateName;

      console.log("Entering state: " + stateName);

      switch (stateName) {
        /* Example:
            
            case 'myGameState':
            
                // Show some HTML block at this game state
                dojo.style( 'my_html_block_id', 'display', 'block' );
                
                break;
           */

        case "dummmy":
          break;
      }
    },

    // onLeavingState: this method is called each time we are leaving a game state.
    //                 You can use this method to perform some user interface changes at this moment.
    //
    onLeavingState: function (stateName) {
      console.log("Leaving state: " + stateName);

      switch (stateName) {
        /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */

        case "dummmy":
          break;
      }
    },

    // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
    //                        action status bar (ie: the HTML links in the status bar).
    //
    onUpdateActionButtons: function (stateName, args) {
      console.log("onUpdateActionButtons: " + stateName);

      if (this.isCurrentPlayerActive()) {
        switch (stateName) {
          /*               
                 Example:
 
                 case 'myGameState':
                    
                    // Add 3 action buttons in the action status bar:
                    
                    this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' ); 
                    this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' ); 
                    this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' ); 
                    break;
*/
          case "handLimit":
            // Discard selected cards from hand
            this.addActionButton(
              "button_1",
              _("Discard selected"),
              "onRemoveCardsFromHand"
            );
            break;
          case "keeperLimit":
            // Remove keepers from play
            this.addActionButton(
              "button_1",
              _("Remove selected"),
              "onRemoveKeepersFromPlay"
            );
            break;
        }
      }
    },

    ////
    // Utility methods

    playCard: function (player_id, card, destinationStock) {
      if (this.isCurrentPlayerActive()) {
        destinationStock.addToStockWithId(
          card.type_arg,
          card.id,
          this.handStock.getItemDivId(card.id)
        );
        this.handStock.removeFromStockById(card.id);
      } else {
        destinationStock.addToStockWithId(
          card.type_arg,
          card.id,
          "player_board_" + player_id
        );
      }
    },

    discardCards: function (cards, stock) {
      for (var card_id in cards) {
        var card = cards[card_id];
        var previousDiscardIds = [];
        var that = this;
        for (var discard of this.discardStock.getAllItems()) {
          setTimeout(function () {
            that.discardStock.removeFromStock(discard.type);
          }, 1000);
        }
        this.discardStock.changeItemsWeight({ [card.type_arg]: 1000 });
        this.discardStock.addToStockWithId(
          card.type_arg,
          card.id,
          stock.getItemDivId(card.id)
        );
        stock.removeFromStockById(card.id);
        setTimeout(function () {
          that.discardStock.changeItemsWeight({ [card.type_arg]: 1 });
        }, 1000);
      }
    },

    _playCard: function (player_id, card_id) {
      var type = Math.floor(uniqueId / 100);
      var number = uniqueId % 100;
      var card_origin;

      if (player_id != this.player_id) {
        card_origin = "overall_player_board_" + player_id;
      } else {
        if ($("myhand_item_" + card_id)) {
          card_origin = $("myhand_item_" + card_id);
        }
      }

      if (type == this.cardtypes.keeper.id_type) {
        this.keepersStock[player_id].addToStockWithId(
          uniqueId,
          card_id,
          card_origin
        );
      }
      if (
        [
          this.cardtypes.baserule.id_type,
          this.cardtypes.goal.id_type,
          this.cardtypes.newrule.id_type,
        ].includes(type)
      ) {
        this.rulesSection.addToStockWithId(uniqueId, card_id, card_origin);
      }
      this.handStock.removeFromStockById(card_id);
    },

    createCardStock: function (elem, mode, types) {
      var stock = new ebg.stock();
      stock.create(this, $(elem), this.CARD_WIDTH, this.CARD_HEIGHT);
      stock.image_items_per_row = this.CARDS_SPRITES_PER_ROW;

      for (var type of types) {
        var count = this.CARDS_TYPES[type].count;
        var spriteOffset = this.CARDS_TYPES[type].spriteOffset;
        var materialOffset = this.CARDS_TYPES[type].materialOffset;

        for (var i = 0; i < count; i++) {
          stock.addItemType(
            materialOffset + i,
            materialOffset + i,
            this.CARDS_SPRITES_PATH,
            spriteOffset + i
          );
        }
      }

      stock.setSelectionMode(mode);
      return stock;
    },

    createKeepersStock: function (elem) {
      var stock = new ebg.stock();
      stock.create(this, $(elem), this.KEEPER_WIDTH, this.KEEPER_HEIGHT);
      stock.image_items_per_row = this.KEEPERS_SPRITES_PER_ROW;

      var count = this.CARDS_TYPES.keeper.count;
      var spriteOffset = this.CARDS_TYPES.keeper.spriteOffset;
      var materialOffset = this.CARDS_TYPES.keeper.materialOffset;

      for (var i = 0; i < count; i++) {
        stock.addItemType(
          materialOffset + i,
          materialOffset + i,
          this.KEEPERS_SPRITES_PATH,
          spriteOffset + i
        );
      }

      stock.setSelectionMode(2);
      return stock;
    },

    addCardsToStock: function (stock, cards) {
      for (var card_id in cards) {
        var card = cards[card_id];
        stock.addToStockWithId(card.type_arg, card.id);
      }
    },

    setDeckCount: function (count) {
      $("deckCount").innerHTML = count;
    },

    setDiscardCount: function (count) {
      $("discardCount").innerHTML = count;
    },

    setPlayerBoardHandCount: function (player_id, count) {
      $("handCount" + player_id).innerHTML = count;
    },

    setPlayerBoardKeepersCount: function (player_id, count) {
      $("keepersCount" + player_id).innerHTML = count;
    },

    ///////////////////////////////////////////////////
    //// Player's action

    /*
     * Convenience method for executing ajax actions
     * Ensures lock is always set
     */
    ajaxAction: function (action, args) {
      if (!args) {
        args = [];
      }
      if (!args.hasOwnProperty("lock")) {
        args.lock = true;
      }
      var name = this.game_name;
      this.ajaxcall(
        "/" + name + "/" + name + "/" + action + ".html",
        args,
        this,
        function (result) {},
        function (is_error) {}
      );
    },

    onSelectHandCard: function () {
      var items = this.handStock.getSelectedItems();
      var actionPlay = "playCard";
      var actionDiscard = "discardCards";

      console.log("onSelectHandCard", items, this.currentState);

      if (items.length == 0) {
        return;
      }

      if (this.checkAction(actionPlay, true)) {
        // Play a card
        this.ajaxAction(actionPlay, {
          card_id: items[0].id,
          card_definition_id: items[0].type,
        });

        this.handStock.unselectAll();
      } else if (this.checkAction(actionDiscard, true)) {
        // selecting cards to be discarded
      } else {
        // nothing can be done now, ignore selection
        this.handStock.unselectAll();
      }
    },

    onRuleSelectionChanged: function () {
      this.rulesSection.unselectAll();
    },

    onKeeperSelectionChanged: function () {
      var actionDiscard = "discardKeepers";
      if (this.checkAction(actionDiscard, true)) {
        // selecting cards to be discarded
      } else {
        // nothing can be done now, ignore selections
        for (const player_id in this.keepersStock) {
          this.keepersStock[player_id].unselectAll();
        }
      }
    },

    onRemoveCardsFromHand: function () {
      var items = this.handStock.getSelectedItems();
      var arg_card_ids = "";
      for (var i in items) {
        console.log(
          "discard from hand: " + items[i].id + ", type: " + items[i].type
        );
        arg_card_ids += items[i].id + ";";
      }
      var actionDiscard = "discardCards";
      this.ajaxAction(actionDiscard, {
        card_ids: arg_card_ids,
      });
    },

    onRemoveKeepersFromPlay: function () {
      var items = this.keepersStock[this.player_id].getSelectedItems();
      var arg_card_ids = "";
      for (var i in items) {
        console.log(
          "discard from keepers: " + items[i].id + ", type: " + items[i].type
        );
        arg_card_ids += items[i].id + ";";
      }
      var actionDiscard = "discardKeepers";
      this.ajaxAction(actionDiscard, {
        card_ids: arg_card_ids,
      });
    },

    ///////////////////////////////////////////////////
    //// Reaction to cometD notifications

    /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your fluxx.game.php file.
        
        */
    setupNotifications: function () {
      dojo.subscribe("cardsDrawn", this, "notif_cardsDrawn");
      dojo.subscribe("cardsDrawnOther", this, "notif_cardsDrawnOther");

      dojo.subscribe("keeperPlayed", this, "notif_keeperPlayed");
      dojo.subscribe("goalsDiscarded", this, "notif_goalsDiscarded");
      dojo.subscribe("goalPlayed", this, "notif_goalPlayed");
      dojo.subscribe("rulesDiscarded", this, "notif_rulesDiscarded");
      dojo.subscribe("rulePlayed", this, "notif_rulePlayed");

      dojo.subscribe("actionPlayed", this, "notif_actionPlayed");
      dojo.subscribe("discardedFromHand", this, "notif_discardedFromHand");
      dojo.subscribe(
        "discardedFromKeepers",
        this,
        "notif_discardedFromKeepers"
      );

      dojo.subscribe("newScores", this, "notif_newScores");
    },

    notif_cardsDrawn: function (notif) {
      for (var card of notif.args.cards) {
        this.handStock.addToStockWithId(card.type_arg, card.id, "deckCard");
      }
    },

    notif_cardsDrawnOther: function (notif) {
      this.setPlayerBoardHandCount(notif.args.player_id, notif.args.handCount);
      this.setDeckCount(notif.args.deckCount);
    },

    notif_keeperPlayed: function (notif) {
      var player_id = notif.args.player_id;
      this.playCard(player_id, notif.args.card, this.keepersStock[player_id]);
      this.setPlayerBoardHandCount(player_id, notif.args.handCount);
      this.setPlayerBoardKeepersCount(
        player_id,
        this.keepersStock[player_id].count()
      );
    },

    notif_goalsDiscarded: function (notif) {
      this.setDiscardCount(notif.args.discardCount);
      this.discardCards(notif.args.cards, this.goalsStock);
    },

    notif_goalPlayed: function (notif) {
      var player_id = notif.args.player_id;
      this.playCard(player_id, notif.args.card, this.goalsStock);
      this.setPlayerBoardHandCount(player_id, notif.args.handCount);
    },

    notif_rulesDiscarded: function (notif) {
      this.setDiscardCount(notif.args.discardCount);
      this.discardCards(notif.args.cards, this.rulesStock[notif.args.ruleType]);
    },

    notif_rulePlayed: function (notif) {
      var player_id = notif.args.player_id;
      this.playCard(
        player_id,
        notif.args.card,
        this.rulesStock[notif.args.ruleType]
      );
      this.setPlayerBoardHandCount(player_id, notif.args.handCount);
    },

    notif_actionPlayed: function (notif) {
      var player_id = notif.args.player_id;
      var card_id = notif.args.card;
      var handCount = notif.args.handCount;

      this.playCard(player_id, card_id, this.discardStock);
      this.setPlayerBoardHandCount(player_id, handCount);
    },

    notif_discardedFromHand: function (notif) {
      var player_id = notif.args.player_id;
      var discardedHandCards = notif.args.cards;
      var discardCount = notif.args.discardCount;
      var handCount = notif.args.handCount;

      this.setPlayerBoardHandCount(player_id, handCount);
      this.setDiscardCount(discardCount);
      this.discardCards(discardedHandCards, this.handStock);
    },

    notif_discardedFromKeepers: function (notif) {
      var player_id = notif.args.player_id;
      var discardedKeepers = notif.args.cards;
      var discardCount = notif.args.discardCount;

      this.setDiscardCount(discardCount);
      this.discardCards(discardedKeepers, this.handStock);
    },

    notif_newScores: function (notif) {
      // Update players' scores
      for (var player_id in notif.args.newScores) {
        this.scoreCtrl[player_id].toValue(notif.args.newScores[player_id]);
      }
    },
  });
});
