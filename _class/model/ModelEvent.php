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

namespace OpenBooking\model;
/**
 * Class ModelEvent
 * @package OpenBooking\model
 */

Class ModelEvent
{
    public $id;
    public $name;
    public $description;
    public $localisation;
    public $date;
    public $participants_max;
    public $organizer;
    public $organizer_email;
    public $creation_date;
    public $open_to_registration;
    public $cancelled;
}