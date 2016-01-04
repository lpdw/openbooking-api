<?php

/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

class Participant
{
    private $id;
    private $id_event;
    private $name;
    private $email;
    private $registration_date;
    private $cancelled;

    private $pdo;

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

    /**
     * Participant constructor.
     * @param array $param
     * @throws Exception
     */
    public function __construct($param)
    {
        try {
            $this->pdo = $GLOBALS['pdo'];
            $res = $this->getParticipant($param);

            $this->id = $res->id;
            $this->id_event = $res->id_event;
            $this->name = $res->name;
            $this->email = $res->email;
            $this->registration_date = $res->registration_date;
            $this->cancelled = $res->cancelled;

        } catch (Exception $e) {
            return array("code" => $e->getCode(), "message" => $e->getMessage());

        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function cancelParticipation()
    {
        try {
            $sql = "UPDATE ob_participant SET cancelled = 1 WHERE id = :id";
            $req = $this->pdo->prepare($sql);
            $req->bindParam(":id", $this->id);
            $req->execute();
            return array("code" => 0, "message" => "ok");

        } catch (Exception $e){
            return array("code" => $e->getCode(), "message" => $e->getMessage());
        }
    }

    /**
     * @param mixed $param
     * @return mixed
     * @throws Exception
     */
    private function getParticipant($param){

        $sql = "SELECT * FROM ob_participant";

        if (isset($param['id'])) {
            $sql .= " WHERE id = :id";
            $req = $this->pdo->prepare($sql);
            $req->bindParam(":id", $param['id']);

        } else if (isset($param['email']) && isset($param['id_event'])) {
            $sql .= " WHERE id_event = :id_event AND email = :email";

            $req = $this->pdo->prepare($sql);
            $req->bindParam(":email", $param['email']);
            $req->bindParam(":id_event", $param['id_event']);

        } else {
            return array("code" => -1, "message" => "Unknow participant");
        }

        $req->setFetchMode(pdo::FETCH_OBJ);
        $req->execute();
        $res = $req->fetch();

        if(count($res) == 0){
            return array("code" => -1, "message" => "Unknow participant");

        }
        return $res;
    }

}