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
use OpenBooking\Exceptions\LoginException;
use OpenBooking\Exceptions\NullDatasException;
use OpenBooking\Exceptions\ValidDatasException;
use OpenBooking\Exceptions\UnknowErrorException;
use OpenBooking\Exceptions\SQLErrorException;
use OpenBooking\model\ModelParticipant;


include_once dirname(__FILE__) . "/../model/ModelParticipant.php";
include_once dirname(__FILE__) . "/../../_exceptions/LoginException.php";
include_once dirname(__FILE__) . "/../../_exceptions/NullDatasException.php";
include_once dirname(__FILE__) . "/../../_exceptions/SQLErrorException.php";
include_once dirname(__FILE__) . "/../../_exceptions/UnknowErrorException.php";
include_once dirname(__FILE__) . "/../../_exceptions/ValidDatasException.php";

/**
 * Class Participant
 *
 * @package OpenBooking\Participant
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
     * Participant registration timestamp
     * @var int Timestamp required
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
     * @throws UnknowErrorException
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
            throw new UnknowErrorException("Unknow error");
        }
    }

    /**
     * Get current participant datas
     * @return ModelParticipant
     */
    public function getParticipant()
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
     * Create a new user.
     *
     * If a new user was created , 0 is returned, otherwise an exception is thrown
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $password
     * @return int
     * @throws SQLErrorException
     * @throws UnknowErrorException
     * @throws ValidDatasException
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
                return 0;
            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknowErrorException();
            }

        } else {
            throw new ValidDatasException("Unexpected format");
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
     * @throws UnknowErrorException
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
                throw new UnknowErrorException();
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
     * @throws UnknowErrorException
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
                throw new UnknowErrorException();
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
     * @throws UnknowErrorException
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
                throw new UnknowErrorException();
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
     * @throws UnknowErrorException
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
                throw new UnknowErrorException();
            }
        } else {
            throw new NullDatasException("Password cannot be empty");
        }
    }

    /**
     * Get registration date. Format : Timestamp
     * @return int
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
     * @throws UnknowErrorException
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
            throw new UnknowErrorException();
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
     * @throws UnknowErrorException
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
                throw new UnknowErrorException();
            }
        } else {
            throw new ValidDatasException("Status should be verified', 'unverified' or 'ban'");
        }
    }
}
