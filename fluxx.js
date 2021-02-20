/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * fluxx implementation : © Alexandre Spaeth <alexandre.spaeth@hey.com> & Iwan Tomlow <iwan.tomlow@gmail.com> & Julien Rossignol <tacotaco.dev@gmail.com>
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

  g_gamethemeurl + "modules/js/game.js",

  g_gamethemeurl + "modules/js/cardTrait.js",

  g_gamethemeurl + "modules/js/states/playCard.js",
  g_gamethemeurl + "modules/js/states/enforceHandLimit.js",
  g_gamethemeurl + "modules/js/states/enforceKeepersLimit.js",
  g_gamethemeurl + "modules/js/states/goalCleaning.js",
  g_gamethemeurl + "modules/js/states/actionResolve.js",
  g_gamethemeurl + "modules/js/states/freeRuleResolve.js",
  g_gamethemeurl + "modules/js/states/creeperResolve.js",
  g_gamethemeurl + "modules/js/states/playRockPaperScissors.js",
], function (dojo, declare) {
  return declare(
    "bgagame.fluxx",
    [
      customgame.game,
      fluxx.cardTrait,
      fluxx.states.playCard,
      fluxx.states.enforceHandLimit,
      fluxx.states.enforceKeepersLimit,
      fluxx.states.goalCleaning,
      fluxx.states.actionResolve,
      fluxx.states.freeRuleResolve,
      fluxx.states.creeperResolve,
      fluxx.states.playRockPaperScissors,
    ],
    {
      constructor: function () {
        this.CARD_WIDTH = 150;
        this.CARD_HEIGHT = 233;
        this.CARDS_SPRITES_PATH = g_gamethemeurl + "img/cards.png";
        this.CARDS_SPRITES_PER_ROW = 17;

        this.KEEPER_WIDTH = 83;
        this.KEEPER_HEIGHT = 129;
        this.KEEPERS_SPRITES_PATH = g_gamethemeurl + "img/keepers.png";
        this.KEEPERS_SPRITES_PER_ROW = 10;

        var creeperPackOffset = 19 + 30 + 27 + 23 + 3; // all base game cards + Basic Rules / Back / Front
        this.CARDS_TYPES_BASEGAME = {
          keeper: { count: 19, spriteOffset: 0, materialOffset: 1 },
          creeper: { count: 0 },
          goal: { count: 30, spriteOffset: 19, materialOffset: 101 },
          rule: { count: 27, spriteOffset: 19 + 30, materialOffset: 201 },
          action: {
            count: 23,
            spriteOffset: 19 + 30 + 27,
            materialOffset: 301,
          },
        };
        this.CARDS_TYPES_CREEPERPACK = {
          keeper: { count: 0 },
          creeper: {
            count: 4,
            spriteOffset: creeperPackOffset,
            materialOffset: 51,
          },
          goal: {
            count: 6,
            spriteOffset: creeperPackOffset + 4,
            materialOffset: 151,
          },
          rule: {
            count: 2,
            spriteOffset: creeperPackOffset + 4 + 6,
            materialOffset: 251,
          },
          action: {
            count: 4,
            spriteOffset: creeperPackOffset + 4 + 6 + 2,
            materialOffset: 351,
          },
        };

        this._allStocks = [];
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
        console.log("GameDatas: ", gamedatas);

        this.players = gamedatas.players;

        // Save card metadata that we will use for UI & metadata
        this.cardTypesDefinitions = this.gamedatas.cardTypesDefinitions;
        this.cardsDefinitions = this.gamedatas.cardsDefinitions;
        //console.log("Cards definitions", this.cardsDefinitions);
        this.prepareKeeperPanelIcons(this.cardsDefinitions);

        // Setup all stocks and restore existing state
        this.handStock = this.createCardStock("handStock", [
          "keeper",
          "goal",
          "rule",
          "action",
          "creeper",
        ]);
        this.addCardsToStock(this.handStock, this.gamedatas.hand);

        this.discardStock = this.createCardStock("discardStock", [
          "keeper",
          "goal",
          "rule",
          "action",
          "creeper",
        ]);
        this.addCardsToStock(this.discardStock, this.gamedatas.discard, true);
        this.discardStock.setOverlap(0.00001);
        this.discardStock.item_margin = 0;
        dojo.connect($("discardToggleBtn"), "onclick", this, "onDiscardToggle");

        this.deckCounter = new ebg.counter();
        this.deckCounter.create("deckCount");
        this.discardCounter = new ebg.counter();
        this.discardCounter.create("discardCount");
        this.deckCounter.toValue(this.gamedatas.deckCount);
        if (this.gamedatas.deckCount == 0) {
          dojo.addClass("deckCard", "flx-deck-empty");
        }
        this.discardCounter.toValue(this.gamedatas.discardCount);

        this.rulesStock = {};

        this.rulesStock.drawRule = this.createCardStock("drawRuleStock", [
          "rule",
        ]);
        this.rulesStock.playRule = this.createCardStock("playRuleStock", [
          "rule",
        ]);
        this.rulesStock.limits = this.createCardStock("limitsStock", [
          "rule",
        ]);
        this.rulesStock.others = this.createCardStock("othersStock", ["rule"]);
        this.addCardsToStock(
          this.rulesStock.drawRule,
          this.gamedatas.rules.drawRule
        );
        this.addCardsToStock(
          this.rulesStock.playRule,
          this.gamedatas.rules.playRule
        );
        this.addCardsToStock(
          this.rulesStock.limits,
          this.gamedatas.rules.handLimit
        );
        this.addCardsToStock(
          this.rulesStock.limits,
          this.gamedatas.rules.keepersLimit
        );
        this.addCardsToStock(
          this.rulesStock.others,
          this.gamedatas.rules.others
        );

        this.goalsStock = this.createCardStock("goalsStock", ["goal"]);
        this.addCardsToStock(this.goalsStock, this.gamedatas.goals);

        this.keepersStock = {};
        this.handCounter = {};
        this.keepersCounter = {};
        this.creepersCounter = {};
        for (var player_id in gamedatas.players) {
          // Setting up player keepers stocks
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

          this.handCounter[player_id] = new ebg.counter();
          this.keepersCounter[player_id] = new ebg.counter();
          this.creepersCounter[player_id] = new ebg.counter();

          this.handCounter[player_id].create("handCount" + player_id);
          this.keepersCounter[player_id].create("keepersCount" + player_id);
          this.creepersCounter[player_id].create("creepersCount" + player_id);

          this.handCounter[player_id].toValue(
            this.gamedatas.handsCount[player_id]
          );
          this.keepersCounter[player_id].toValue(
            this.keepersStock[player_id].count() - this.gamedatas.creepersCount[player_id]
          );
          this.creepersCounter[player_id].toValue(
            this.gamedatas.creepersCount[player_id]
          );

          // add current keepers in player panel
          this.addToKeeperPanelIcons(player_id, this.gamedatas.keepers[player_id]);
        }

        // Determine card overlaps per number of cards in hand / stocks
        this.adaptForScreenSize();

        // Hide elements that are only used with Creeper pack expansion
        if (!gamedatas.creeperPack) {
          dojo.query(".flx-board-creeper").forEach(function(node, index, nodelist){
            dojo.addClass(node, "no-creepers");
            });
        }

        // Setup game notifications to handle (see "setupNotifications" method below)
        this.setupNotifications();

        console.log("Setup completed!");
      },

      onScreenWidthChange: function() {
        this.adaptForScreenSize();
      },

      adaptForScreenSize: function() {
        if($('game_play_area') && this.handStock !== undefined){
          var viewPortWidth = dojo.position('game_play_area')['w'];
          console.log("viewPortWidth: ", viewPortWidth);
          this.adaptCardOverlaps(viewPortWidth);
        }        
      },

      prepareKeeperPanelIcons: function(cardDefinitions) {
        var panelDivId = "tmpKeeperPanelIcons";
        var keeperCount = 19;
        for (var id in cardDefinitions) {
          var cardDefinition = cardDefinitions[id];
          if (cardDefinition.type == "keeper") {
            var params = {
              id: id, 
              name: cardDefinition.name, 
              offset: (id-1)*100
            };
            var panelKeeper = this.format_block("jstpl_panel_keeper", params);
            dojo.place(panelKeeper, panelDivId);
          } else if (cardDefinition.type == "creeper") {
            var params = {
              id: id, 
              name: cardDefinition.name, 
              offset: (keeperCount + (id - 50) - 1) * 100
            };
            var panelCreeper = this.format_block("jstpl_panel_keeper", params);
            dojo.place(panelCreeper, panelDivId);
          }
        }
      },

      addToKeeperPanelIcons(player_id, cards){
        var destinationPanelDivId = "keeperPanel"+player_id;

        for (var card_id in cards) {
          var card = cards[card_id];
          var keeperDivId = "flx-board-panel-keeper-"+card["type_arg"];
          dojo.place(keeperDivId, destinationPanelDivId);          
        }
      },

      removeFromKeeperPanelIcons(player_id, cards){
        var destinationPanelDivId = "tmpKeeperPanelIcons";

        for (var card_id in cards) {
          var card = cards[card_id];
          var keeperDivId = "flx-board-panel-keeper-"+card["type_arg"];
          dojo.place(keeperDivId, destinationPanelDivId);          
        }
      },

      adaptCardOverlaps: function(viewPortWidth) {
        var maxHandCardsInRow = viewPortWidth / (this.CARD_WIDTH + 5);
        var maxRuleCardsInRow = (viewPortWidth * 3/4 ) / (this.CARD_WIDTH + 5);
        var maxKeeperCardsInRow = 5;
        
        this.adaptCardOverlapsForStock(this.handStock, maxHandCardsInRow);
        this.adaptCardOverlapsForStock(this.rulesStock.others, maxRuleCardsInRow);

        for (var player_id in this.keepersStock) {
          var stock = this.keepersStock[player_id];
          this.adaptCardOverlapsForStock(stock, maxKeeperCardsInRow);
        }

        this.rulesStock.limits.setOverlap(0);
        if (viewPortWidth < 800) {
          this.rulesStock.limits.setOverlap(50);
        } else if (viewPortWidth < 1024) {
          this.rulesStock.limits.setOverlap(65);
        }
        this.rulesStock.limits.resetItemsPosition();

        this.goalsStock.setOverlap(80);
        if (viewPortWidth < 800) {
          this.goalsStock.setOverlap(45);
        } else if (viewPortWidth < 1024) {
          this.goalsStock.setOverlap(50);          
        }
        this.goalsStock.resetItemsPosition();
      },

      adaptCardOverlapsForStock(stock, maxCardsPerRow) {
        var cardsCount = stock.count();
          if (cardsCount > maxCardsPerRow * 3) {
            stock.setOverlap(50);
          } else if (cardsCount > maxCardsPerRow * 2) {
            stock.setOverlap(65);
          } else if (cardsCount > maxCardsPerRow * 1) {
            stock.setOverlap(80);
          } else {
            stock.setOverlap(0);
          }
          stock.resetItemsPosition();
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
          case "playCard":
            this.onEnteringStatePlayCard(args);
            break;

          case "enforceHandLimitForOthers":
          case "enforceHandLimitForSelf":
            this.onEnteringStateEnforceHandLimit(args);
            break;

          case "enforceKeepersLimitForOthers":
          case "enforceKeepersLimitForSelf":
            this.onEnteringStateEnforceKeepersLimit(args);
            break;

          case "goalCleaning":
            this.onEnteringStateGoalCleaning(args);
            break;

          case "playRockPaperScissors":
            this.onEnteringStatePlayRockPaperScissors(args);

          case "actionResolve":
            this.onEnteringStateActionResolve(args);
            break;

          case "freeRuleResolve":
            this.onEnteringStateFreeRuleResolve(args);
            break;

          case "creeperResolveInPlay":
          case "creeperResolveTurnStart":
            this.onEnteringStateCreeperResolve(args);
            break;

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
          case "playCard":
            this.onLeavingStatePlayCard();
            break;

          case "enforceHandLimitForOthers":
          case "enforceHandLimitForSelf":
            this.onLeavingStateEnforceHandLimit();
            break;

          case "enforceKeepersLimitForOthers":
          case "enforceKeepersLimitForSelf":
            this.onLeavingStateEnforceKeepersLimit();
            break;

          case "goalCleaning":
            this.onLeavingStateGoalCleaning();
            break;

          case "actionResolve":
            this.onLeavingStateActionResolve();

          case "playRockPaperScissors":
            this.onLeavingStatePlayRockPaperScissors();

          case "freeRuleResolve":
            this.onLeavingStateFreeRuleResolve();
            break;

          case "creeperResolveInPlay":
          case "creeperResolveTurnStart":
                this.onLeavingStateCreeperResolve();
            break;            

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
            case "playCard":
              this.onUpdateActionButtonsPlayCard(args);
              break;
            case "enforceHandLimitForOthers":
            case "enforceHandLimitForSelf":
              this.onUpdateActionButtonsEnforceHandLimit(args);
              break;
            case "enforceKeepersLimitForOthers":
            case "enforceKeepersLimitForSelf":
              this.onUpdateActionButtonsEnforceKeepersLimit(args);
              break;
            case "goalCleaning":
              this.onUpdateActionButtonsGoalCleaning(args);
              break;
            case "actionResolve":
              this.onUpdateActionButtonsActionResolve(args);
              break;
            case "playRockPaperScissors":
              this.onUpdateActionButtonsPlayRockPaperScissors(args);
              break;
            case "freeRuleResolve":
              this.onUpdateActionButtonsFreeRuleResolve(args);
              break;
            case "creeperResolveInPlay":
            case "creeperResolveTurnStart":
              this.onUpdateActionButtonsCreeperResolve(args);
              break;          }
        }
      },

      ////
      // Utility methods
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

      addCardsOfTypeFromGameSet: function (stock, type, gameSet) {
        var count = gameSet[type].count;
        var spriteOffset = gameSet[type].spriteOffset;
        var materialOffset = gameSet[type].materialOffset;

        for (var i = 0; i < count; i++) {
          stock.addItemType(
            materialOffset + i,
            materialOffset + i,
            this.CARDS_SPRITES_PATH,
            spriteOffset + i
          );
        }
      },

      createCardStock: function (elem, types) {
        var stock = new ebg.stock();
        this._allStocks[elem] = stock;
        stock.create(this, $(elem), this.CARD_WIDTH, this.CARD_HEIGHT);
        stock.image_items_per_row = this.CARDS_SPRITES_PER_ROW;

        for (var type of types) {
          this.addCardsOfTypeFromGameSet(
            stock,
            type,
            this.CARDS_TYPES_BASEGAME
          );
          this.addCardsOfTypeFromGameSet(
            stock,
            type,
            this.CARDS_TYPES_CREEPERPACK
          );
        }

        stock.setSelectionMode(0);
        stock.onItemCreate = dojo.hitch(this, "setupNewCard");
        return stock;
      },

      addCardsToKeeperStock: function (stock, cardType, spriteOffset) {
        var count = cardType.count;
        var spriteOffset = spriteOffset;
        var materialOffset = cardType.materialOffset;

        for (var i = 0; i < count; i++) {
          stock.addItemType(
            materialOffset + i,
            0, // all no weight > keep in order as played, like on panels
            this.KEEPERS_SPRITES_PATH,
            spriteOffset + i
          );
        }
      },

      createKeepersStock: function (elem) {
        var stock = new ebg.stock();
        this._allStocks[elem] = stock;
        stock.create(this, $(elem), this.KEEPER_WIDTH, this.KEEPER_HEIGHT);
        stock.image_items_per_row = this.KEEPERS_SPRITES_PER_ROW;

        // small version for keepers played
        this.addCardsToKeeperStock(stock, this.CARDS_TYPES_BASEGAME.keeper, 0);

        // small version for creepers played
        var smallCreeperSpriteOffset =
          1 + this.CARDS_TYPES_BASEGAME.keeper.count;
        this.addCardsToKeeperStock(
          stock,
          this.CARDS_TYPES_CREEPERPACK.creeper,
          smallCreeperSpriteOffset
        );

        stock.setSelectionMode(0);
        stock.onItemCreate = dojo.hitch(this, "setupNewCard");
        return stock;
      },

      setupNewCard: function (card_div, card_type_id, card_id) {
        var cardDefinition = this.cardsDefinitions[card_type_id];

        var card = {
          set: cardDefinition.set,
          name: cardDefinition.name,
          subtitle: cardDefinition.subtitle || "",
          description: cardDefinition.description || "",
          type: cardDefinition.type,
          typeName: this.cardTypesDefinitions[cardDefinition.type],
          id: card_type_id,
        };

        // Add a special tooltip on the card:
        this.addTooltipHtml(
          card_div.id,
          this.format_block("jstpl_cardTooltip", card)
        );
        // Overlay the card image with translated descriptions
        var cardOverlayTitle = this.format_block("jstpl_cardOverlay_title", card);
        var cardOverlay = this.format_block("jstpl_cardOverlay_text", card);
        dojo.place(cardOverlayTitle, card_div.id);
        dojo.place(cardOverlay, card_div.id);

        // Note that "card_type_id" contains the type of the item, so you can do special actions depending on the item type        

      },

      addCardsToStock: function (stock, cards, keepOrder) {
        for (var card_id in cards) {
          var card = cards[card_id];
          stock.addToStockWithId(card.type_arg, card.id);
          if (keepOrder) {
            stock.changeItemsWeight({
              [card.type_arg]: parseInt(card.location_arg),
            });
          }
        }
      },
      setupNotifications: function () {
        console.log("SETUP NOTIFICATIONS", this._notifications);
        this._notifications.forEach((notif) => {
          var functionName = "notif_" + notif[0];

          dojo.subscribe(notif[0], this, functionName);
          if (notif[1] != null) {
            this.notifqueue.setSynchronous(notif[0], notif[1]);
          }
        });

        dojo.subscribe("newScores", this, "notif_newScores");
      },

      onDiscardToggle: function (ev) {
        ev.preventDefault();

        if (dojo.hasClass("flxDeckBlock", "flx-discard-visible")) {
          this.discardStock.item_margin = 0;
          this.discardStock.setOverlap(0.00001);
          dojo.removeClass("flxDeckBlock", "flx-discard-visible");
          $("discardToggleBtn").innerHTML = _("Show discard pile");
          this.discardStock.resetItemsPosition();
        } else {
          this.discardStock.setOverlap(0);
          this.discardStock.item_margin = 5;
          dojo.addClass("flxDeckBlock", "flx-discard-visible");
          $("discardToggleBtn").innerHTML = _("Hide discard pile");
          this.discardStock.resetItemsPosition();
        }
      },

      notif_newScores: function (notif) {
        // Update players' scores
        for (var player_id in notif.args.newScores) {
          this.scoreCtrl[player_id].toValue(notif.args.newScores[player_id]);
        }
      },
    }
  );
});
