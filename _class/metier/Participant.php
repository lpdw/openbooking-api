<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

class Participant{
    private $id;
    private $id_event;
    private $name;
    private $email;
    private $registration_date;
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
    public function getIdEvent()
    {
        return $this->id_event;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getRegistrationDate()
    {
        return $this->registration_date;
    }

    /**
     * @param mixed $registration_date
     */
    public function setRegistrationDate($registration_date)
    {
        $this->registration_date = $registration_date;
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