<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

namespace OpenBooking\_Class\Model;

use \DateTime;

/**
 * Class ModelParticipant
 * @package OpenBooking\_Class\Model
 */
Class ModelParticipant{

    /**
     * Participant ID
     *
     * @var int $id
     */
    public $id;

    /**
     * Participant first name
     * @var string $first_name
     */
    public $first_name;

    /**
     * Participant last name
     * @var string $last_name
     */
    public $last_name;

    /**
     * Participant email
     * @var string $email
     */
    public $email;

    /**
     * Participant registration date
     * @var DateTime $registration_date
     */
    public $registration_date;

    /**
     * Comments about participant
     * @var string $comments
     */
    public $comments;

    /**
     * Participant status.
     *
     * If status == ban, user can connect but can't participate to an event. He need to contact an administrator.
     *
     * @var string $status Possible values  : 'verified', 'unverified', 'ban'
     */
    public $status;


}
