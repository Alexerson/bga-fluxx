<?php
namespace Fluxx\Cards;

/*
 * Card: base class for all playable Card types
 */
abstract class Card extends \APP_GameClass
{
    public function __construct($cardId, $uniqueId)
    {
        $this->cardId = $cardId;
        $this->uniqueId = $uniqueId;
    }

    /*
     * Attributes
     */
    protected $cardId;
    protected $uniqueId;

    /*
     * Getters
     */
    public function getCardId()
    {
        return $this->cardId;
    }
    public function getUniqueId()
    {
        return $this->uniqueId;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getSubtitle()
    {
        return $this->subtitle;
    }
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * playFromHand : default function to execute when a Card is played from hand.
     * return: null if the game should continue the play loop,
     * or "stateName" if another state need to be called
     */
    public function playFromHand($player)
    {
        return null;
    }

    /**
     * removeFromPlay : default function to execute when card is removed from play.
     * return: null if the game should continue the play loop,
     * or "stateName" if another state need to be called
     */
    public function removeFromPlay()
    {
        return null;
    }
}
