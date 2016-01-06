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
     * @var int $id
     */
    public $id;

    /**
     * Event name
     * @var string $name
     */
    public $name;

    /**
     * Event description
     * @var string $description
     */
    public $description;

    /**
     * Event localisation
     * @var string $localisation
     */
    public $localisation;

    /**
     * Event date
     * @var int $date DateTime
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
     * @var string $organizer_email
     */
    public $organizer_email;

    /**
     * Event creation date
     * @var int $creation_date Timestamp required
     */
    public $creation_date;

    /**
     * Event registration status (1: opened or 0: closed)
     * @var int $open_to_registration
     */
    public $open_to_registration;

    /**
     * Event status (1: canceled or 0: not canceled)
     * @var int $canceled
     */
    public $canceled;
}
