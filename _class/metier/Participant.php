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

namespace OpenBooking\_Class\Metier;

use \PDO;
use \PDOException;
use \Exception;
use \DateTime;
use OpenBooking\_Exceptions\LoginException;
use OpenBooking\_Exceptions\NullDatasException;
use OpenBooking\_Exceptions\ValidDatasException;
use OpenBooking\_Exceptions\UnknownErrorException;
use OpenBooking\_Exceptions\SQLErrorException;
use OpenBooking\_Exceptions\DataAlreadyExistInDatabaseException;
use OpenBooking\_Class\Model\ModelParticipant;
use OpenBooking\_Class\Model\ModelParticipation;

include_once dirname(__FILE__) . "/../model/ModelParticipant.php";
include_once dirname(__FILE__) . "/../../_exceptions/LoginException.php";
include_once dirname(__FILE__) . "/../../_exceptions/NullDatasException.php";
include_once dirname(__FILE__) . "/../../_exceptions/SQLErrorException.php";
include_once dirname(__FILE__) . "/../../_exceptions/UnknownErrorException.php";
include_once dirname(__FILE__) . "/../../_exceptions/ValidDatasException.php";
include_once dirname(__FILE__) . "/../../_exceptions/DataAlreadyExistInDatabaseException.php";

/**
 * Class Participant
 * @package OpenBooking\_Class\Metier
 */
class Participant
{
    /**
     * Participant ID
     *
     * @var int
     */
    private $id;

    /**
     * Participant first name
     * @var string
     */
    private $first_name;

    /**
     * Participant last name
     * @var string
     */
    private $last_name;

    /**
     * Participant email
     * @var string
     */
    private $email;

    /**
     * Participant registration date
     * @var DateTime
     */
    private $registration_date;

    /**
     * Comments about participant
     * @var string
     */
    private $comments;

    /**
     * Participant status.
     *
     * If status == ban, user can connect but can't participate to an event. He need to contact an administrator.
     *
     * @var string Possible values  : 'verified', 'unverified', 'ban'
     */
    private $status;

    /**
     * @ignore
     */
    private $pdo;

    /**
     * Participant constructor.
     * @param string $email
     * @param string $password
     * @throws LoginException
     * @throws UnknownErrorException
     */
    public function __construct($email, $password)
    {
        try {
            $password = hash("sha512", $password);
            $this->pdo = $GLOBALS['pdo'];
            $sql = "SELECT * FROM ob_participant WHERE email = :email AND password = :password";
            $req = $this->pdo->prepare($sql);
            $req->bindParam(":email", $email);
            $req->bindParam(":password", $password);
            $req->execute();
            $req->setFetchMode(PDO::FETCH_OBJ);
            $res = $req->fetch();
            if (isset($res->id) && $res->id > 0) {
                $this->id = $res->id;
                $this->first_name = $res->first_name;
                $this->last_name = $res->last_name;
                $this->email = $res->email;
                $this->registration_date = $res->registration_date;
                $this->comments = $res->comments;
                $this->status = $res->status;
            }else{
                throw new LoginException("Access denied, bad credentials");
            }
        } catch (LoginException $e) {
            throw new LoginException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException("Unknown error");
        }
    }

    /**
     * Get current participant datas
     * @return ModelParticipant
     */
    public function get()
    {
        $res = new ModelParticipant();
        $res->id = $this->id;
        $res->first_name = $this->first_name;
        $res->last_name = $this->last_name;
        $res->email = $this->email;
        $res->registration_date = $this->registration_date;
        $res->comments = $this->comments;
        $res->status = $this->status;
        return $res;
    }

    /**
     * Get all participants in database, password field excluded
     * @return ModelParticipant[]
     * @param null | int $limit
     * @param null | int $offset
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public static function getAll($limit = null, $offset = null)
    {
        try {
            $allParticipants = array();
            $pdo = $GLOBALS['pdo'];
            $sql = "SELECT id, first_name, last_name, email, registration_date, comments, status from ob_participant";
            $sql .= ((isset($limit) && isset($offset)) ? " LIMIT ".$limit." OFFSET ".$offset : "" );

            $req = $pdo->prepare($sql);
            $req->execute();
            $req->setFetchMode((PDO::FETCH_OBJ));
            $res = $req->fetchall();

            foreach ($res as $key => $participant) {
                $allParticipants[$key] = new ModelParticipant();
                foreach ($participant as $field => $value) {
                    $allParticipants[$key]->{$field} = $value;
                }
            }
            return $allParticipants;

        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException();
        }
    }

    /**
     * Create a new user.
     *
     * If a new user was created , array("code" => 0, "message" => "ok") is returned, otherwise an exception is thrown
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $password
     * @return array
     * @throws SQLErrorException
     * @throws UnknownErrorException
     * @throws ValidDatasException
     * @throws DataAlreadyExistInDatabaseException
     */
    static function add($first_name, $last_name, $email, $password)
    {
        if (strlen(trim($first_name)) > 0
            && strlen(trim($last_name)) > 0
            && filter_var($email, FILTER_VALIDATE_EMAIL)
            && strlen(trim($password)) > 0
        ) {

            try {
                $password = hash("sha512", $password);

                $sql = "INSERT INTO ob_participant (first_name, last_name, email, password)
                        VALUES (:first_name, :last_name, :email, :password)";
                $req = $GLOBALS['pdo']->prepare($sql);

                $req->bindParam(":first_name", $first_name);
                $req->bindParam(":last_name", $last_name);
                $req->bindParam(":email", $email);
                $req->bindParam(":password", $password);
                $req->execute();
                return array("code" => 0, "message" => "ok");
            } catch (PDOException $e) {
                if($e->getCode() == 23000){
                    throw new DataAlreadyExistInDatabaseException("Duplicate entry for this user.");
                } else {
                    throw new SQLErrorException($e->getMessage());
                }
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }

        } else {
            throw new ValidDatasException("Unexpected format");
        }
    }

    /**
     * Get list of all participations for a participant
     *
     * @return ModelParticipation[]
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function getParticipations(){

        try{
            $sql = "SELECT participation.* FROM ob_participation AS participation
                JOIN ob_participant AS people ON people.id = participation.id_participant
                WHERE people.id = :id";
            $req = $this->pdo->prepare($sql);
            $req->bindParam(":id", $this->id);
            $req->execute();
            $req->setFetchMode(PDO::FETCH_OBJ);
            $rows = $req->FetchAll();
            $return = [];
            if(isset($rows[0]->id)){
                foreach ($rows as $row) {
                    $tmp = new ModelParticipation();
                    $tmp->id                = $row->id;
                    $tmp->id_event          = $row->id_event;
                    $tmp->id_participant    = $row->id_participant;
                    $tmp->cancelled         = $row->cancelled;
                    $tmp->comments          = $row->comments;
                    $tmp->present           = $row->present;
                    $return[] = $tmp;
                }
                return $return;
            }
            return [];
        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException();
        }
    }

    /**
     * Get participant ID
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get participant first name
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set participant first name and save it into database
     * @param string $first_name
     * @throws NullDatasException
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function setFirstName($first_name)
    {
        if (strlen(trim($first_name)) > 0) {
            try {
                $sql = "UPDATE ob_participant SET first_name = :first_name WHERE id = :id";
                $req = $this->pdo->prepare($sql);
                $req->bindParam(":first_name", $first_name);
                $req->bindParam(":id", $this->id);
                $req->execute();
                $this->first_name = $first_name;
            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }
        } else {
            throw new NullDatasException("First name cannot be empty");
        }
    }

    /**
     * Get participant last name
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set participant last name and save it into database
     * @param string $last_name
     * @throws NullDatasException
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function setLastName($last_name)
    {
        if (strlen(trim($last_name)) > 0) {
            try {
                $sql = "UPDATE ob_participant SET last_name = :last_name WHERE id = :id";
                $req = $this->pdo->prepare($sql);
                $req->bindParam(":last_name", $last_name);
                $req->bindParam(":id", $this->id);
                $req->execute();
                $this->last_name = $last_name;
            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }
        } else {
            throw new NullDatasException("Last name cannot be empty");
        }
    }

    /**
     * Get participant email
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set participant email and save it into database
     * @param string $email
     * @throws NullDatasException
     * @throws ValidDatasException
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function setEmail($email)
    {
        if (strlen(trim($email)) > 0) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new ValidDatasException("Email format is not valid");
            }
            try {
                $sql = "UPDATE ob_participant SET email = :email WHERE id = :id";
                $req = $this->pdo->prepare($sql);
                $req->bindParam(":email", $email);
                $req->bindParam(":id", $this->id);
                $req->execute();
            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }
            $this->email = $email;
        } else {
            throw new NullDatasException("Email cannot be empty");
        }
    }

    /**
     * Save new password into database
     * @param string $password
     * @throws NullDatasException
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function setPassword($password)
    {
        if (strlen($password) > 0) {
            try {
                $sql = "UPDATE ob_participant SET password = :password WHERE id = :id";
                $req = $this->pdo->prepare($sql);
                $req->bindParam(":password", hash("sha512", $password));
                $req->bindParam(":id", $this->id);
                $req->execute();
            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }
        } else {
            throw new NullDatasException("Password cannot be empty");
        }
    }

    /**
     * Get registration date. Format : DateTime
     * @return DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registration_date;
    }

    /**
     * Get participant comment
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set participant comments and save it into database
     * @param $comments
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function setComments($comments)
    {
        try {
            $sql = "UPDATE ob_participant SET comments = :comments WHERE id = :id";
            $req = $this->pdo->prepare($sql);
            $req->bindParam(":comments", $comments);
            $req->bindParam(":id", $this->id);
            $req->execute();
            $this->comments = $comments;
        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException();
        }
    }

    /**
     * Get the participant status. Possible values  : 'verified', 'unverified', 'ban'
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the participant status and save it into the database
     * @param  string $status Possible values  : 'verified', 'unverified', 'ban'
     * @throws SQLErrorException
     * @throws UnknownErrorException
     * @throws ValidDatasException
     */
    public function setStatus($status)
    {
        if ($status == "verified" || $status == "unverified" || $status == "ban") {
            try {
                $sql = "UPDATE ob_participant SET status = :status WHERE id = :id";
                $req = $this->pdo->prepare($sql);
                $req->bindParam(":status", $status);
                $req->bindParam(":id", $this->id);
                $req->execute();
                $this->status = $status;
            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }
        } else {
            throw new ValidDatasException("Status should be verified', 'unverified' or 'ban'");
        }
    }
}
