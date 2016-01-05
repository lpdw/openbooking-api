<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

namespace OpenBooking;

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
        //Todo : Envoyer un mail
    }

    // Todo : Supprimer les setters car inutiles vu qu'on a une function update. Laisser juste setCanelled et enregistrer en bdd.
    /**
     * Event constructor.
     * @param $id
     * @throw Exception
     */
    public function __construct($id)
    {
        try{
            $this->pdo = $GLOBALS["pdo"];
            $res = $this->getEvent($id);

            $this->id = $res->id;
            $this->name = $res->name;
            $this->description = $res->description;
            $this->localisation = $res->localisation;
            $this->date = $res->date;
            $this->participants_max = $res->participants_max;
            $this->organizer = $res->organizer;
            $this->organizer_email = $res->organizer_email;
            $this->creation_date = $res->creation_date;
            $this->open_to_registration = $res->open_to_registration;
            $this->cancelled = $res->cancelled;
        } catch (Exception $e) {
            return array("code" => $e->getCode(), "message" => $e->getMessage());
        }
    }

    /**
     * Get an existing event in database.
     * @param $id
     * @return mixed
     * @throws Exception
     */
    private function getEvent($id)
    {
        $sql = "SELECT * FROM ob_event WHERE id=:id";
        $req = $this->pdo->prepare($sql);
        $req->bindParam(":id", $id);
        $req->setFetchMode(pdo::FETCH_OBJ);
        $req->execute();
        $res = $req->fetch();

        if(!isset($res->id)){
            throw new Exception("Exception : Unknown participant");
        }
        return $res;
    }

    /**
     * Adding a new event in database.
     * @param $param
     * @return array
     */
    public static function add($param) //Todo : passer parametres plutot qu'un tableau, sachant que le param cancelled est par defaut à 0. donc pas besoin de le passer
    {
        try{
            //Todo : vérifier que les parametres ne sont pas vide ou = ""
            $pdo = $GLOBALS["pdo"];
            $sql = "INSERT INTO ob_event (name,
                                          description,
                                          localisation,
                                          date,
                                          participants_max,
                                          organizer,
                                          organizer_email,
                                          open_to_registration,
                                          cancelled)
                    VALUES (:name,
                            :description,
                            :localisation,
                            :date,
                            :participants_max,
                            :organizer,
                            :organizer_email,
                            :open_to_registration,
                            :cancelled)";

            $req = $pdo->prepare($sql);

            $req->bindParam(':name', $param["name"]);
            $req->bindParam(':description', $param["description"]);
            $req->bindParam(':localisation', $param["localisation"]);
            $req->bindParam(':date', $param["date"]);
            $req->bindParam(':participants_max', $param["participants_max"]);
            $req->bindParam(':organizer', $param["organizer"]);
            $req->bindParam(':organizer_email', $param["organizer_email"]);
            $req->bindParam('open_to_registration', $param["open_to_registration"]);
            $req->bindParam('cancelled', $param["cancelled"]);

            $req->execute();
            return array("code" => 0, "message" => "ok");

        } catch (Exception $e) {
            return array("code" => $e->getCode(), "message" => $e->getMessage());
        }

    }

    /**
     * Update an existing event in database.
     * @param $param
     * @return array
     */
    public function updateEvent($param)
    {
        try{
            $pdo = $GLOBALS["pdo"];
            $sql = "UPDATE ob_event SET name = :name,
                                        description = :description,
                                        localisation = :localisation,
                                        date = :date,
                                        participants_max = :participants_max,
                                        organizer = :organizer,
                                        organizer_email = :organizer_email,
                                        open_to_registration = :open_to_registration,
                                        cancelled = :cancelled
                  WHERE id = :id";

            $req = $pdo->prepare($sql);

            $req->bindParam(':id', $this->id);
            $req->bindParam(':name', $param["name"]);
            $req->bindParam(':description', $param["description"]);
            $req->bindParam(':localisation', $param["localisation"]);
            $req->bindParam(':date', $param["date"]); //Todo : Convert date format mm/dd/YYYY HH:mm:ss to timestamp
            $req->bindParam(':participants_max', $param["participants_max"]);
            $req->bindParam(':organizer', $param["organizer"]);
            $req->bindParam(':organizer_email', $param["organizer_email"]);
            $req->bindParam('open_to_registration', $param["open_to_registration"]);
            $req->bindParam('cancelled', $param["cancelled"]);

            //Todo : Envoyer un mail

            $req->execute();
            return array("code" => 0, "message" => "ok");

        } catch (Exception $e){
            return array("code" => $e->getCode(), "message" => $e->getMessage());
        }
    }
}