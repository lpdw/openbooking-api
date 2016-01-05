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

namespace OpenBooking\_Class\Metier\EmailType;
use \PDO;
use \Exception;
use OpenBooking\_Exceptions\SQLErrorException;
use OpenBooking\_Exceptions\UnknowErrorException;
use OpenBooking\_Exceptions\NullDatasException;

/**
 * Class EmailType
 * @package OpenBooking\_Class\Metier\EmailType
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
     * @var int Timestamp required
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
     * @throws UnknowErrorException
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
                throw new UnknowErrorException("Unknow type");
            }
        } catch (SQLErrorException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknowErrorException("Unknow error");
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
     * Set EmailType type and save it into database
     * @param string $type
     * @throws SQLErrorException
     * @throws UnknowErrorException
     * @throws NullDatasException
     */
    public function setType($type)
    {
        if(strlen(trim($type)) > 0) {
            try {
                $sql = "UPDATE ob_email_type SET type = :type WHERE id = :id";
                $req = $this->pdo->prepare($sql);
                $req->bindParam(":type", $type);
                $req->bindParam(":id", $this->id);
                $req->execute();
                $this->type = $type;
            } catch (SQLErrorException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknowErrorException();
            }
        } else {
            throw new NullDatasException("You must chose a type");
        }
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
     * @throws UnknowErrorException
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
            } catch (SQLErrorException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknowErrorException();
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
     * @throws UnknowErrorException
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
            } catch (SQLErrorException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknowErrorException();
            }
        } else {
            throw new NullDatasException("You must write a body");
        }
    }

    /**
     * Get EmailType last edit date
     * @return int
     */
    public function getLastEdit()
    {
        return $this->last_edit;
    }

    /**
     * Set EmailType last edit date and save it into database
     * @param int $last_edit
     * @throws SQLErrorException
     * @throws UnknowErrorException
     * @throws NullDatasException
     */
    public function setLastEdit($last_edit)
    {
        if(strlen(trim($last_edit)) > 0) {
            try {
                $sql = "UPDATE ob_email_type SET last_edit = :last_edit WHERE id = :id";
                $req = $this->pdo->prepare($sql);
                $req->bindParam(":last_edit", $last_edit);
                $req->bindParam(":id", $this->id);
                $req->execute();
                $this->last_edit = $last_edit;
            } catch (SQLErrorException $e) {
                throw new SQLErrorException($e->getMessage());
            } catch (Exception $e) {
                throw new UnknowErrorException();
            }
        } else {
            throw new NullDatasException("Last edit date can't be blank");
        }
    }
}