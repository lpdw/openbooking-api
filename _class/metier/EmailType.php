<?php

/**
 * Email Class File.
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
use \Exception;
use \PDOException;
use \DateTime;
use OpenBooking\_Exceptions\SQLErrorException;
use OpenBooking\_Exceptions\UnknownErrorException;
use OpenBooking\_Exceptions\NullDatasException;
use OpenBooking\_Exceptions\DataAlreadyExistInDatabaseException;


/**
 * Class EmailType
 * @package OpenBooking\_Class\Metier
 */
Class EmailType
{
    /**
     * EmailType ID
     * @var int
     */
    private $id;

    /**
     * Type of mail. Possible values:
     * 'remind1day',
     * 'remind7day',
     * 'waiting_list',
     * 'participant_registration',
     * 'participant_annulation',
     * 'event_annulation',
     * 'participant_waiting_list_place_available',
     * 'event_modification'
     * @var string
     */
    private $type;

    /**
     * EmailType object
     * @var string
     */
    private $object;

    /**
     * EmailType body
     * @var string
     */
    private $body;

    /**
     * EmailType last edit date
     * @var DateTime
     */
    private $last_edit;

    /**
     * @ignore
     */
    private $pdo;

    /**
     * EmailType constructor.
     * @param $type
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    public function __construct($type)
    {
        try {
            $this->pdo = $GLOBALS['pdo'];
            $sql = "SELECT * FROM ob_email_type WHERE type = :type";
            $req = $this->pdo->prepare($sql);
            $req->bindParam(":type", $type);
            $req->execute();
            $req->setFetchMode(PDO::FETCH_OBJ);
            $res = $req->fetch();
            if (isset($res->id) && $res->id > 0) {
                $this->id = $res->id;
                $this->type = $res->type;
                $this->object = $res->object;
                $this->body = $res->body;
                $this->last_edit = $res->last_edit;
            } else {
                throw new UnknownErrorException("Unknown type");
            }
        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException("Unknown error");
        }
    }

    /**
     * Create an EmailType and save it into databse
     *
     * If a new Event was created , array("code" => 0, "message" => "ok") is returned, otherwise an exception is thrown
     * @param string $type
     * @param string $object
     * @param string $body
     * @return mixed array
     * @throws NullDatasException
     * @throws SQLErrorException
     * @throws UnknownErrorException
     * @throws DataAlreadyExistInDatabaseException
     */
    public static function add($type, $object, $body)
    {
        if(strlen(trim($type)) > 0
            && strlen(trim($object)) > 0
            && strlen(trim($body)) > 0
        ) {
            try{
                $pdo = $GLOBALS["pdo"];
                $sql = "INSERT INTO ob_email_type (type,
                                                   object,
                                                   body)
                        VALUES (:type,
                                :object,
                                :body)";

                $req = $pdo->prepare($sql);

                $req->bindParam(':type', $type);
                $req->bindParam(':object', $object);
                $req->bindParam(':body', $body);

                $req->execute();
                return array("code" => 0, "message" => "ok");

            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    throw new DataAlreadyExistInDatabaseException("Duplicate entry for this type.");
                } else {
                    throw new SQLErrorException($e->getMessage());
                }
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }
        }else{
            throw new NullDatasException("All fields must be filled");
        }
    }

    /**
     * Get EmailType ID
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get EmailType type. Possible value:
     * 'remind1day',
     * 'remind7day',
     * 'waiting_list',
     * 'participant_registration',
     * 'participant_annulation',
     * 'event_annulation',
     * 'participant_waiting_list_place_available',
     * 'event_modification'
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get EmailType object
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set EmailType object and save it into database
     * @param string $object
     * @throws SQLErrorException
     * @throws UnknownErrorException
     * @throws NullDatasException
     */
    public function setObject($object)
    {
        if(strlen(trim($object)) > 0) {
            try {
                $sql = "UPDATE ob_email_type SET object = :object WHERE id = :id";
                $req = $this->pdo->prepare($sql);
                $req->bindParam(":object", $object);
                $req->bindParam(":id", $this->id);
                $req->execute();
                $this->object = $object;
                $this->last_edit = new DateTime('now');
            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }
        } else {
            throw new NullDatasException("You must chose an object");
        }
    }

    /**
     * Get EmailType body
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set EmailType body and save it into database
     * @param string $body
     * @throws SQLErrorException
     * @throws UnknownErrorException
     * @throws NullDatasException
     */
    public function setBody($body)
    {
        if(strlen(trim($body)) > 0) {
            try {
                $sql = "UPDATE ob_email_type SET body = :body WHERE id = :id";
                $req = $this->pdo->prepare($sql);
                $req->bindParam(":body", $body);
                $req->bindParam(":id", $this->id);
                $req->execute();
                $this->body = $body;
                $this->last_edit = new DateTime('now');
            } catch (PDOException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknownErrorException();
            }
        } else {
            throw new NullDatasException("You must write a body");
        }
    }

    /**
     * Get EmailType last edit date. Format : DateTime
     * @return DateTime
     */
    public function getLastEdit()
    {
        return $this->last_edit;
    }
}