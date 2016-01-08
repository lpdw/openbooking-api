<?php
/**
 * Event Class File.
 * @version 1.0
 * @author Elias Cédric Laouiti
 * @author Maxime Florile
 * Date: 04/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 *
 */

namespace OpenBooking\_Class\Metier;

use \PDO;
use \PDOException;
use \Exception;
use \DateTime;
use OpenBooking\_Exceptions\NullDatasException;
use OpenBooking\_Exceptions\UnknownErrorException;
use OpenBooking\_Exceptions\SQLErrorException;
use OpenBooking\_Exceptions\DataAlreadyExistInDatabaseException;
use OpenBooking\_Class\Model\ModelEvent;
use OpenBooking\_Class\Model\ModelParticipant;

include_once dirname(__FILE__) . "/../model/ModelEvent.php";
include_once dirname(__FILE__) . "/../model/ModelParticipant.php";
include_once dirname(__FILE__) . "/../metier/Email.php";
include_once dirname(__FILE__) . "/../../_exceptions/NullDatasException.php";
include_once dirname(__FILE__) . "/../../_exceptions/SQLErrorException.php";
include_once dirname(__FILE__) . "/../../_exceptions/UnknownErrorException.php";
include_once dirname(__FILE__) . "/../../_exceptions/DataAlreadyExistInDatabaseException.php";

require_once dirname(__FILE__) . "/../../_include/bddConnect.php";

/**
 * Class Event
 * @package OpenBooking\_Class\Metier
 */
Class Event
{
    /**
     * Event ID
     * @var int $id
     */
    private $id;

    /**
     * Event name
     * @var string $name
     */
    private $name;

    /**
     * Event description
     * @var string $description
     */
    private $description;

    /**
     * Event localisation
     * @var string $localisation
     */
    private $localisation;

    /**
     * Event date
     * @var DateTime $date
     */
    private $date;

    /**
     * Max participants allowed for the Event
     * @var int $participant_max
     */
    private $participants_max;

    /**
     * Event organizer
     * @var string $organizer
     */
    private $organizer;

    /**
     * Email address of Event organizer
     * @var string $organizer_email
     */
    private $organizer_email;

    /**
     * Event creation date
     * @var DateTime $creation_date
     */
    private $creation_date;

    /**
     * Event registrations status (1: opened or 0: closed)
     * @var int $open_to_registration
     */
    private $open_to_registration;

    /**
     * Event status (1: cancelled or 0: not cancelled)
     * @var int $cancelled
     */
    private $cancelled;

    /**
     * @ignore
     */
    private $pdo;


    /**
     * Event constructor.
     * @param int $id
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function __construct($id)
    {
        try {
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
            } else {
                throw new UnknownErrorException("Unknown event");
            }
        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException($e->getMessage());
        }
    }

    /**
     * Get current Event datas
     * @return ModelEvent
     */
    public function get()
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
     * Create a new Event in database
     *
     * If a new Event was created , array("code" => 0, "message" => "ok") is returned, otherwise an exception is thrown
     * @param string $name
     * @param string $description
     * @param string $localisation
     * @param DateTime $date
     * @param int $participants_max
     * @param string $organizer
     * @param string $organizer_email
     * @param boolean $open_to_registration
     * @return mixed array
     * @throws NullDatasException
     * @throws SQLErrorException
     * @throws UnknownErrorException
     * @throws DataAlreadyExistInDatabaseException
     */
    public static function add($name, $description, $localisation, $date, $participants_max, $organizer, $organizer_email, $open_to_registration = true)
    {
        if (strlen(trim($name)) > 0
            && strlen(trim($localisation)) > 0
            && strlen(trim($date->format("Y-m-d H:i:s"))) > 0
            && strlen(trim($participants_max)) > 0
            && strlen(trim($organizer)) > 0
            && strlen(trim($organizer_email)) > 0
        ) {
            try {
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
                $req->bindParam(':date', $date->format("Y-m-d H:i:s"));
                $req->bindParam(':participants_max', $participants_max);
                $req->bindParam(':organizer', $organizer);
                $req->bindParam(':organizer_email', $organizer_email);
                $req->bindParam(':open_to_registration', $open_to_registration);

                $req->execute();
                return array("code" => 0, "message" => "ok");

            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    throw new DataAlreadyExistInDatabaseException("Duplicate entry for this user.");
                } else {
                    throw new SQLErrorException($e->getMessage());
                }
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }
        } else {
            throw new NullDatasException("All fields must be filled");
        }
    }

    /**
     * Get all events "Open to registration"
     * To force getAll without filter, turn $all to true
     * @param boolean $all
     * @param null|int $limit
     * @param null|int $offset
     * @return ModelEvent[]
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public static function getAll($all = false, $limit = null, $offset = null)
    {
        try {
            $pdo = $GLOBALS["pdo"];
            $sql = ($all ? "SELECT * FROM ob_event" : "SELECT * FROM ob_event WHERE open_to_registration = 1 AND cancelled != 1");
            $sql .= (isset($limit) ? " LIMIT ".$limit : "" );
            $sql .= (isset($offset) && isset($limit) ? " OFFSET ".($offset-1)*$limit : "");
            $req = $pdo->prepare($sql);
            $req->execute();
            $req->setFetchMode(PDO::FETCH_OBJ);
            $rows = $req->fetchAll();
            $return = [];
            foreach ($rows AS $row) {
                $tmp = new ModelEvent();
                $tmp->id = $row->id;
                $tmp->name = $row->name;
                $tmp->description = $row->description;
                $tmp->localisation = $row->localisation;
                $tmp->date = $row->date;
                $tmp->participants_max = $row->participants_max;
                $tmp->organizer = $row->organizer;
                $tmp->organizer_email = $row->organizer_email;
                $tmp->creation_date = $row->creation_date;
                $tmp->open_to_registration = $row->open_to_registration;
                $tmp->cancelled = $row->cancelled;
                $return[] = $tmp;
            }
            return $return;
        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException();
        }
    }

    /**
     * Get all events which take place on given date
     * @param DateTime $date
     * @return ModelEvent[]
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public static function getByDateTime(DateTime $date)
    {
        try {
            $pdo = $GLOBALS["pdo"];
            $sql = "SELECT *
                    FROM ob_event
                    WHERE date between
                    STR_TO_DATE(CONCAT(:date, ' ', '00:00:00'), '%Y-%m-%d %H:%i:%s')
                    AND
                    STR_TO_DATE(CONCAT(:date, ' ', '23:59:59'), '%Y-%m-%d %H:%i:%s')
                    AND cancelled !=1";
            $req = $pdo->prepare($sql);
            $date = $date->format('Y-m-d');
            $req->bindParam(":date", $date);
            $req->execute();
            $req->setFetchMode(PDO::FETCH_OBJ);
            $rows = $req->fetchAll();
            $return = [];
            foreach ($rows AS $row) {
                print_r($row);
                $tmp = new ModelEvent();
                $tmp->id = $row->id;
                $tmp->name = $row->name;
                $tmp->description = $row->description;
                $tmp->localisation = $row->localisation;
                $tmp->date = $row->date;
                $tmp->participants_max = $row->participants_max;
                $tmp->organizer = $row->organizer;
                $tmp->organizer_email = $row->organizer_email;
                $tmp->creation_date = $row->creation_date;
                $tmp->open_to_registration = $row->open_to_registration;
                $tmp->cancelled = $row->cancelled;
                $return[] = $tmp;
            }
            return $return;
        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException();
        }
    }

    /**
     * Update an event and save it into database , array("code" => 0, "message" => "ok") is returned, otherwise an exception is thrown
     * @param string $name
     * @param string $description
     * @param string $localisation
     * @param DateTime $date
     * @param int $participants_max
     * @param string $organizer
     * @param string $organizer_email
     * @param boolean $open_to_registration
     * @return mixed array
     * @throws NullDatasException
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function update($name, $description, $localisation, $date, $participants_max, $organizer, $organizer_email, $open_to_registration)
    {
        if (strlen(trim($name)) > 0
            && strlen(trim($description)) > 0
            && strlen(trim($localisation)) > 0
            && strlen(trim($date->format("Y-m-d H:i:s"))) > 0
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
                $req->bindParam(':date', $date->format("Y-m-d H:i:s"));
                $req->bindParam(':participants_max', $participants_max);
                $req->bindParam(':organizer', $organizer);
                $req->bindParam(':organizer_email', $organizer_email);
                $req->bindParam('open_to_registration', $open_to_registration);

                $email = new Email();
                $email->prepareAndSendEmail("event_modification", $this->getParticipants(), $this);

                $req->execute();
                return array("code" => 0, "message" => "ok");

            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }
        } else {
            throw new NullDatasException("All fields must be filled");
        }
    }

    /**
     * Get all the Event participants
     * @return ModelParticipant[]
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function getParticipants()
    {
        try {
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
            if (isset($participants[0]->id)) {
                foreach ($participants as $key => $participant) {
                    $res[$key] = new ModelParticipant();

                    foreach ($participant as $attribut => $value) {
                        $field = ($attribut == "password" ? null : $value);
                        $res[$key]->{$attribut} = $field;
                    }
                }
            }
            return $res;

        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException();
        }
    }

    /**
     * Get the next recipients from a Participation, by date of registration, for waiting list
     * @param DateTime $cancelled_registration_date
     * @return ModelParticipant
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function getNextRecipient(DateTime $cancelled_registration_date)
    {
        try {
            $sql = "SELECT *
                    FROM ob_participant
                    INNER JOIN ob_participation
                    ON ob_participation.id_participant = ob_participant.id
                    WHERE ob_participation.id_event = :id
                    AND ob_participation.registration_date > :date
                    AND ob_participation.cancelled = 0
                    ORDER BY ob_participation.registration_date
                    LIMIT 1";

            $req = $this->pdo->prepare($sql);
            $req->bindParam(':id', $this->id);
            $cancelled_registration_date = $cancelled_registration_date->format('Y-m-d H:i:s');
            $req->bindParam(':date', $cancelled_registration_date);
            $req->setFetchMode(pdo::FETCH_OBJ);

            $req->execute();
            $res = $req->fetch();
            if (isset($res->id) && $res->id > 0) {

                $nextRecipient = new ModelParticipant();
                $nextRecipient->id = $res->id;
                $nextRecipient->first_name = $res->first_name;
                $nextRecipient->last_name = $res->last_name;
                $nextRecipient->email = $res->email;
                $nextRecipient->registration_date = $res->registration_date;
                $nextRecipient->comments = $res->comments;
                $nextRecipient->status = $res->status;

                return $nextRecipient;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException('tralala');
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
     * Get Event date. Format: DateTime
     * @return DateTime
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
     * Get Event creation date. Format: DateTime
     * @return DateTime
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
     * @return boolean
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }

    /**
     * Set Event status and save it into database
     * If event is cancelled, open_to_registration is turned to false
     * @param bool|true $cancelled
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function setCancelled($cancelled = true)
    {
        try {
            $this->cancelled = $cancelled;
            $sql = "UPDATE ob_event SET cancelled = :cancelled"
                    .($cancelled ? ", open_to_registration = 0 " : " " ).
                    "WHERE id = :id";
            $req = $this->pdo->prepare($sql);
            $req->bindParam(":cancelled", $cancelled);
            $req->bindParam("id", $this->id);
            $req->execute();

            $email = new Email();
            $email->prepareAndSendEmail('event_annulation', $this->getParticipants(), $this);

        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException();
        }
    }
}
