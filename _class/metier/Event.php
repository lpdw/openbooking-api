<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

Class Event{
    private $id;
    private $name;
    private $description;
    private $localisation;
    private $date;
    private $participants_max;
    private $organizer;
    private $organizer_email;
    private $creation_date;
    private $open_to_registration;
    private $cancelled;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * @param mixed $localisation
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getParticipantsMax()
    {
        return $this->participants_max;
    }

    /**
     * @param mixed $participants_max
     */
    public function setParticipantsMax($participants_max)
    {
        $this->participants_max = $participants_max;
    }

    /**
     * @return mixed
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * @param mixed $organizer
     */
    public function setOrganizer($organizer)
    {
        $this->organizer = $organizer;
    }

    /**
     * @return mixed
     */
    public function getOrganizerEmail()
    {
        return $this->organizer_email;
    }

    /**
     * @param mixed $organizer_email
     */
    public function setOrganizerEmail($organizer_email)
    {
        $this->organizer_email = $organizer_email;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * @param mixed $creation_date
     */
    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;
    }

    /**
     * @return mixed
     */
    public function getOpenToRegistration()
    {
        return $this->open_to_registration;
    }

    /**
     * @param mixed $open_to_registration
     */
    public function setOpenToRegistration($open_to_registration)
    {
        $this->open_to_registration = $open_to_registration;
    }

    /**
     * @return mixed
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }

    /**
     * @param mixed $cancelled
     */
    public function setCancelled($cancelled)
    {
        $this->cancelled = $cancelled;
    }

    public function __construct(){

    }
}