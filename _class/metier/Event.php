<?php
/**
 * Participant Class File.
 * @version 1.0
 * @author Elias Cédric Laouiti
 * @author Maxime Florile
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 *
 */

namespace OpenBooking;

use \PDO;
use \PDOException;
use \Exception;
use OpenBooking\Exceptions\NullDatasException;
use OpenBooking\Exceptions\UnknowErrorException;
use OpenBooking\Exceptions\SQLErrorException;
use OpenBooking\model\ModelEvent;
use OpenBooking\model\ModelParticipant;


include_once dirname(__FILE__) . "/../model/ModelEvent.php";
include_once dirname(__FILE__) . "/../model/ModelParticipant.php";
include_once dirname(__FILE__) . "/../../_exceptions/NullDatasException.php";
include_once dirname(__FILE__) . "/../../_exceptions/SQLErrorException.php";
include_once dirname(__FILE__) . "/../../_exceptions/UnknowErrorException.php";

/**
 * Class Event
 * @package OpenBooking\Event
 */
Class Event
{
    /**
     * Event ID
     * @var int
     */
    private $id;

    /**
     * Event name
     * @var string
     */
    private $name;

    /**
     * Event description
     * @var string
     */
    private $description;

    /**
     * Event localisation
     * @var string
     */
    private $localisation;

    /**
     * Event date
     * @var int Timestamp required
     */
    private $date;

    /**
     * Max participants allowed for the Event
     * @var int
     */
    private $participants_max;

    /**
     * Event organizer
     * @var string
     */
    private $organizer;

    /**
     * Email address of Event organizer
     * @var string
     */
    private $organizer_email;

    /**
     * Event creation date
     * @var int Timestamp required
     */
    private $creation_date;

    /**
     * Event registrations status (1: opened or 0: closed)
     * @var int
     */
    private $open_to_registration;

    /**
     * Event status (1: cancelled or 0: not cancelled)
     * @var int
     */
    private $cancelled;

    /**
     * @ignore
     */
    private $pdo;


    /**
     * Event constructor.
     * @param $id
     * @throws SQLErrorException
     * @throws UnknowErrorException
     */
    public function __construct($id)
    {
        try{
            $this->pdo = $GLOBALS["pdo"];
            $sql = "SELECT * FROM ob_event WHERE id = :id";
            $req = $this->pdo->prepare($sql);
            $req->bindParam("id", $id);
            $req->execute();
            $req->setFetchMode(PDO::FETCH_OBJ);
            $res = $req->fetch();
            if (isset($res->id) && $res->id > 0) {
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
            } else{
                throw new UnknowErrorException("Unknow event");
            }
        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknowErrorException();
        }
    }

    /**
     * Get current Event datas
     * @return ModelEvent
     */
    public function getEvent()
    {
        $res = new ModelEvent();
        $res->id = $this->id;
        $res->name = $this->name;
        $res->description = $this->description;
        $res->localisation = $this->localisation;
        $res->date = $this->date;
        $res->participants_max = $this->participants_max;
        $res->organizer = $this->organizer;
        $res->organizer_email = $this->organizer_email;
        $res->creation_date = $this->creation_date;
        $res->open_to_registration = $this->open_to_registration;
        $res->cancelled = $this->cancelled;
        return $res;
    }

    /**
     * Adding a new Event in database
     * @param $name
     * @param $description
     * @param $localisation
     * @param $date
     * @param $participants_max
     * @param $organizer
     * @param $organizer_email
     * @param $open_to_registration
     * @return int
     * @throws NullDatasException
     * @throws SQLErrorException
     * @throws UnknowErrorException
     */
    public static function add($name, $description, $localisation, $date, $participants_max, $organizer, $organizer_email, $open_to_registration)
    {
        if(strlen(trim($name)) > 0
            && strlen(trim($description)) > 0
            && strlen(trim($localisation)) > 0
            && strlen(trim($date)) > 0
            && strlen(trim($participants_max)) > 0
            && strlen(trim($organizer)) > 0
            && strlen(trim($organizer_email)) > 0
            && strlen(trim($open_to_registration)) > 0
        ) {
            try{
                $pdo = $GLOBALS["pdo"];
                $sql = "INSERT INTO ob_event (name,
                                          description,
                                          localisation,
                                          date,
                                          participants_max,
                                          organizer,
                                          organizer_email,
                                          open_to_registration)
                    VALUES (:name,
                            :description,
                            :localisation,
                            :date,
                            :participants_max,
                            :organizer,
                            :organizer_email,
                            :open_to_registration)";

                $req = $pdo->prepare($sql);

                $req->bindParam(':name', $name);
                $req->bindParam(':description', $description);
                $req->bindParam(':localisation', $localisation);
                $req->bindParam(':date', $date);
                $req->bindParam(':participants_max', $participants_max);
                $req->bindParam(':organizer', $organizer);
                $req->bindParam(':organizer_email', $organizer_email);
                $req->bindParam('open_to_registration', $open_to_registration);

                $req->execute();
                return 0;

            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknowErrorException();
            }
        } else {
            throw new NullDatasException("All fields must be filled");
        }
    }

    /**
     * @param $name
     * @param $description
     * @param $localisation
     * @param $date
     * @param $participants_max
     * @param $organizer
     * @param $organizer_email
     * @param $open_to_registration
     * @return int
     * @throws NullDatasException
     * @throws SQLErrorException
     * @throws UnknowErrorException
     */
    public function updateEvent($name, $description, $localisation, $date, $participants_max, $organizer, $organizer_email, $open_to_registration)
    {
        if(strlen(trim($name)) > 0
            && strlen(trim($description)) > 0
            && strlen(trim($localisation)) > 0
            && strlen(trim($date)) > 0
            && strlen(trim($participants_max)) > 0
            && strlen(trim($organizer)) > 0
            && strlen(trim($organizer_email)) > 0
            && strlen(trim($open_to_registration)) > 0
        ) {
            try {
                $sql = "UPDATE ob_event SET name = :name,
                                            description = :description,
                                            localisation = :localisation,
                                            date = :date,
                                            participants_max = :participants_max,
                                            organizer = :organizer,
                                            organizer_email = :organizer_email,
                                            open_to_registration = :open_to_registration
                      WHERE id = :id";

                $req = $this->pdo->prepare($sql);

                $req->bindParam(':id', $this->id);
                $req->bindParam(':name', $name);
                $req->bindParam(':description', $description);
                $req->bindParam(':localisation', $localisation);
                $req->bindParam(':date', $date);
                $req->bindParam(':participants_max', $participants_max);
                $req->bindParam(':organizer', $organizer);
                $req->bindParam(':organizer_email', $organizer_email);
                $req->bindParam('open_to_registration', $open_to_registration);

                //Todo : Envoyer un mail

                $req->execute();
                return 0;

            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknowErrorException();
            }
        }else{
            throw new NullDatasException("All fields must be filled");
        }    
    }

    /**
     * Get all the Event participants
     * @return array of Participant
     * @throws SQLErrorException
     * @throws UnknowErrorException
     */
    public function getParticipants()
    {
        try{
            $sql = "SELECT *
                    FROM ob_participant
                    INNER JOIN ob_participation
                    ON ob_participation.id_participant = ob_participant.id
                    WHERE ob_participation.id_event = :id";

            $req = $this->pdo->prepare($sql);
            $req->bindParam(':id', $this->id);
            $req->setFetchMode(pdo::FETCH_OBJ);
            $req->execute();
            $participants = $req->fetchall();
            $res = array();

            foreach($participants as $key => $participant)
            {
                $res[$key] = new ModelParticipant();

                foreach($participant as $attribut => $value)
                {
                    $field = ($attribut == "password" ? null : $value);
                    $res[$key]->{$attribut}= $field;
                }
            }
            return $res;

        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknowErrorException();
        }
    }


    /**
     * Get Event ID
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Event name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Event description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get Event localisation
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Get Event date. Format: Timestamp
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get Event max participants number
     * @return int
     */
    public function getParticipantsMax()
    {
        return $this->participants_max;
    }

    /**
     * Get Event organizer name
     * @return string
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * Get Event organizer email address
     * @return string
     */
    public function getOrganizerEmail()
    {
        return $this->organizer_email;
    }

    /**
     * Get Event creation date. Format: Timestamp
     * @return int
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Get Event registration status. Possible values : 1 (opened), 0 (closed)
     * @return int
     */
    public function getOpenToRegistration()
    {
        return $this->open_to_registration;
    }

    /**
     * Get Event status. Possible values : 1 (cancelled), 0 (not cancelled)
     * @return int
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }

    /**
     * Set Event status and save it into database
     * @param mixed $cancelled
     * @throws SQLErrorException
     * @throws UnknowErrorException
     */
    public function setCancelled($cancelled)
    {
        try{
            $this->cancelled = $cancelled;
            $sql = "UPDATE ob_event SET cancelled = :cancelled WHERE id = :id";
            $req = $this->pdo->prepare($sql);
            $req->bindParam(":cancelled", $cancelled);
            $req->bindParam("id", $this->id);
            $req->execute();

        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknowErrorException();
        }
        //Todo : Envoyer un mail
    }
}
