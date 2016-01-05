<?php
/**
 * ModelParticipation Class File.
 * @version 1.0
 * @author Elias Cédric Laouiti
 * @author Maxime Florile
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 *
 */

namespace OpenBooking\model;

/**
 * Class ModelParticipation
 * @package OpenBooking\model
 */
Class ModelParticipation
{
    /**
     * Participation id
     * @var int $id
     */
    public $id;

    /**
     * Participant id
     * @var int $id_participant
     */
    public $id_participant;

    /**
     * Event id
     * @var int $id_event
     */
    public $id_event;

    /**
     * Comment about the participation
     * @var string $comments
     */
    public $comments;

    /**
     * Participation cancelled
     *
     * If cancelled == 0 => Participation NOT cancelled else if cancelled == 1, participation was cancelled
     * @var bool $cancelled
     */
    public $cancelled;

    /**
     * Participant present
     *
     * If present == 0 => Participant wasn't at the event else if present == 1, Participant was present at the event
     * @var bool $present
     */
    public $present;
}
