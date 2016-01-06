<?php
/**
 * ModelEvent Class File.
 * @version 1.0
 * @author Elias Cédric Laouiti
 * @author Maxime Florile
 * Date: 06/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

namespace OpenBooking\_Class\Model;

/**
 * Class ModelEmailType
 * @package OpenBooking\_Class\Model
 */

Class ModelEmailType
{
    /**
     * EmailType ID
     * @var int
     */
    public $id;

    /**
     * EmailType type. Possible values:
     * 'remind1day',
     * 'remind7day',
     * 'waiting_list',
     * 'participant_registration',
     * 'participant_annulation',
     * 'event_annulation',
     * 'participant_waiting_list_place_available',
     * 'event_modification'
     * @var string
     */
    public $type;

    /**
     * EmailType object
     * @var string
     */
    public $object;

    /**
     * EmailType body
     * @var string
     */
    public $body;

    /**
     * EmailType last edit date
     * @var DateTime
     */
    public $last_edit;
}