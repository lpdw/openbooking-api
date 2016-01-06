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
use \PDOException;
use \Exception;
use OpenBooking\_Exceptions\SQLErrorException;
use OpenBooking\_Exceptions\UnknownErrorException;
use OpenBooking\_Class\Model\ModelParticipant;
use PHPMailer;

require dirname(__FILE__).'/../../vendor/autoload.php';

/**
 * Class Email
 * @package OpenBooking\_Class\Metier
 */
class Email{

    /**
     * Email object
     * @var string
     */
    private $object;

    /**
     * Email body
     * @var string
     */
    private $body;

    /**
     * @ignore
     */
    private $smtpHost;

    /**
     * @ignore
     */
    private $smtpUser;

    /**
     * @ignore
     */
    private $smtpPass;

    /**
     * @ignore
     */
    private $smtpPort;

    /**
     * @ignore
     */
    private $smtpType;

    /**
     * @ignore
     */
    private $smtpFrom;

    /**
     * @ignore
     */
    private $smtpFromName;

    /**
     * @ignore
     */
    private $pdo;

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $iniConf = parse_ini_file(dirname(__FILE__)."/../../conf.ini", true);
        $smtpConf  = $iniConf['SMTP'];

        $this->pdo          = $GLOBALS['pdo'];
        $this->smtpFrom     = $smtpConf['smtpFrom'];
        $this->smtpFromName = $smtpConf['smtpFromName'];
        $this->smtpHost     = $smtpConf['smtpHost'];
        $this->smtpUser     = $smtpConf['smtpUser'];
        $this->smtpPass     = $smtpConf['smtpPass'];
        $this->smtpPort     = $smtpConf['smtpPort'];
        $this->smtpType     = $smtpConf['smtpType'];
    }

    /**
     * Get the email template, format the template and send it
     * @param string $type Attempt value :
     * 'remind1day',
     * 'remind7day',
     * 'waiting_list',
     * 'participant_registration',
     * 'participant_annulation',
     * 'event_annulation',
     * 'participant_waiting_list_place_available',
     * 'event_modification'
     *
     * @param ModelParticipant[] $recipients
     * @param null | Event $event
     */
    public function prepareAndSendEmail($type, $recipients, $event = null){
        $this->getTemplate($type);
            foreach ($recipients as $recipient) {
                $body = $this->formatEmail($recipient, $event);
                $object = $this->object;
                $to = $recipient->email;
                $toName = $recipient->last_name." ". $recipient->first_name;
                $this->send($to, $toName, $object, $body);
            }

    }

    /**
     * Get the template of email.
     *
     * @param string $type Attempt value :
     * 'remind1day',
     * 'remind7day',
     * 'waiting_list',
     * 'participant_registration',
     * 'participant_annulation',
     * 'event_annulation',
     * 'participant_waiting_list_place_available',
     * 'event_modification'
     * @throws SQLErrorException
     * @throws UnknownErrorException
     */
    private function getTemplate($type) {
        try {
            $sql = "SELECT object, body FROM ob_email_type WHERE type = :type";
            $req = $this->pdo->prepare($sql);
            $req->bindParam(":type", $type);
            $req->execute();
            $req->setFetchMode(PDO::FETCH_OBJ);
            $res = $req->fetch();

        } catch (PDOException $e) {
            throw new SQLErrorException($e->getMessage());
        } catch (Exception $e) {
            throw new UnknownErrorException();
        }
        if(isset($res->body) && !empty($res->body)){
            $this->body = $res->body;
            $this->object = $res->object;
        }else{
            throw new UnknownErrorException("Unknown type. Please correct it or create it.");
        }
    }

    /**
     * Replace tags by customs values.
     *
     * Available tags =
     *
     * {{eventLocation}}
     * {{eventDate}}
     * {{eventName}}
     * {{recipientFirstName}}
     * {{recipientLastName}}
     *
     * @param ModelParticipant $recipient
     * @param Event $event
     * @return string
     */
    private function formatEmail($recipient, $event){
        $recipientFirstName = $recipient->first_name;
        $recipientLastName  = $recipient->last_name;
        $newBody = null;

        if(!is_null($event)){
            $eventName          = $event->getName();
            $eventDate          = $event->getDate();
            $eventLocalisation  = $event->getLocalisation();

            $newBody = str_replace("{{eventLocation}}", $eventLocalisation, $this->body);
            $newBody = str_replace("{{eventDate}}", $eventDate, $newBody);
            $newBody = str_replace("{{eventName}}", $eventName, $newBody);
        }

        $newBody  = str_replace("{{recipientFirstName}}", $recipientFirstName, $newBody);
        $newBody = str_replace("{{recipientLastName}}", $recipientLastName, $newBody);

        return $newBody;
    }

    /**
     * Send email
     * @param string $to
     * @param string $toName
     * @param string $object
     * @param string $body
     * @throws UnknownErrorException
     * @throws \phpmailerException
     */
    private function send($to, $toName, $object, $body){
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = $this->smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $this->smtpUser;
        $mail->Password = $this->smtpPass;
        $mail->SMTPSecure = $this->smtpType;
        $mail->Port = $this->smtpPort;
        $mail->setFrom($this->smtpFrom, $this->smtpFromName);
        $mail->addAddress($to, $toName);
        $mail->isHTML(true);

        $mail->Subject = $object;
        $mail->Body    = $body;

        if(!$mail->send()) {
            throw new UnknownErrorException("Cannot send email");
        }
    }
}