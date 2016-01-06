<?php
/**
 * ModelEvent Class File.
 * @version 1.0
 * @author Elias Cédric Laouiti
 * @author Maxime Florile
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

namespace OpenBooking\_Class\Model;

/**
 * Class ModelEvent
 * @package OpenBooking\_Class\Model
 */

Class ModelEvent
{
    /**
     * Event ID
     * @var int
     */
    public $id;

    /**
     * Event name
     * @var string
     */
    public $name;

    /**
     * Event description
     * @var string
     */
    public $description;

    /**
     * Event localisation
     * @var string
     */
    public $localisation;

    /**
     * Event date
     * @var DateTime
     */
    public $date;

    /**
     * Max participants allowed for the Event
     * @var int
     */
    public $participants_max;

    /**
     * Event organizer
     * @var string
     */
    public $organizer;

    /**
     * Email address of Event organizer
     * @var string
     */
    public $organizer_email;

    /**
     * Event creation date
     * @var DateTime
     */
    public $creation_date;

    /**
     * Event registration status (1: opened or 0: closed)
     * @var int
     */
    public $open_to_registration;

    /**
     * Event status (1: cancelled or 0: not cancelled)
     * @var int
     */
    public $cancelled;
}
