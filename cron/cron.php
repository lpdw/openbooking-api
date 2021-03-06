<?php
/**
 * Cron File.
 * @use Edit crontab and paste "0 0 * * * /usr/bin/php LINK/TO/API/DIR/cron/cron.php >/dev/null 2>&1"
 * @version 1.0
 * @author Elias Cédric Laouiti
 * @author Maxime Florile
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

/**
 *
 * Cron file who send reminder mails
 *
 */

require_once dirname(__FILE__)."/../_class/metier/Email.php";
require_once dirname(__FILE__)."/../_class/metier/Event.php";
require_once dirname(__FILE__)."/../_class/metier/Participation.php";
require_once dirname(__FILE__)."/../_class/metier/Participant.php";

use OpenBooking\_Class\Metier\Event;
use OpenBooking\_Class\Metier\Email;

send7DaysReminder();
send1DaysReminder();

/**
 * Send reminder 7 days before
 * @throws \OpenBooking\_Exceptions\SQLErrorException
 * @throws \OpenBooking\_Exceptions\UnknowErrorException
 */
function send7DaysReminder(){
    $now = new DateTime('now');
    $now->modify('+7 day');
    $events = Event::getByDateTime($now);
    foreach ($events as $event) {
        $e = new Event($event->id);
        $participants = $e->getParticipants();
            $email = new Email();
            $email->prepareAndSendEmail("remind7day", $participants, $e);
    }
}

/**
 * Send reminder 1 day before
 * @throws \OpenBooking\_Exceptions\SQLErrorException
 * @throws \OpenBooking\_Exceptions\UnknowErrorException
 */
function send1DaysReminder(){
    $now = new DateTime('now');
    $now->modify('+1 day');
    $events = Event::getByDateTime($now);
    foreach ($events as $event) {
        $e = new Event($event->id);
        $participants = $e->getParticipants();
            $email = new Email();
            $email->prepareAndSendEmail("remind1day", $participants, $e);
    }
}