<?php

namespace Framework\lib;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

//Load Composer's autoloader
require APP_PATH . 'vendor/autoload.php';


class MailModel
{
    public $connected = true;

    public $logger;

    private $smtp_server;
    private $smtp_backup_server;
    private $smtp_username;
    private $smtp_password;
    private $smtp_encryption;
    private $smtp_port;

    public $from_email;
    public $from_name;
    public $to_email;
    public $to_name;
    public $to;
    public $reply_to_email;
    public $reply_to_name;
    public $is_cc = false;
    public $cc;
    public $is_bcc = false;
    public $bcc;
    public $attachment = array();
    public $subject;
    public $message;
    public $alt_message;

    public function __construct()
    {
        // Get logger.
        $loggerModel = new LoggerModel('emails');
        $logger = $loggerModel->InitializeLogger();
        $this->logger = $logger['logger'];

        // Set smtp configurations
        $this->smtp_server = SMTP_SERVER;
        $this->smtp_backup_server = SMTP_BACKUP_SERVER;
        $this->smtp_username = SMTP_USERNAME;
        $this->smtp_password = SMTP_PASSWORD;
        $this->smtp_encryption = SMTP_ENCRYPTION;
        $this->smtp_port = SMTP_PORT;

        // Create a new SMTP instance
        $smtp = new SMTP;
        // Enable connection-level debug output
        $smtp->do_debug = SMTP::DEBUG_OFF;

        // Connect to an SMTP server
//        if (!$smtp->connect($this->smtp_server, 25)) {
//            if (!$smtp->connect($this->smtp_backup_server, 25)) {
//                $this->connected = false;
//                $this->logger->error('Connecting to SMTP servers failed: ' . $smtp->getError()['error']);
//                exit();
//            }
//        }
//
//        if (!$smtp->hello(gethostname())) {
//            $this->connected = false;
//            $this->logger->error('SMTP EHLO failed: ' . $smtp->getError()['error']);
//            exit();
//        }
//
//        // Get the list of ESMTP services the server offers
//        $e = $smtp->getServerExtList();
//
//        // If server supports authentication, try to connect.
//        if (is_array($e) && array_key_exists('AUTH', $e)) {
//            if (!$smtp->authenticate($this->smtp_username, $this->smtp_password)) {
//                $this->connected = false;
//                $this->logger->error('SMTP Authentication failed: ' . $smtp->getError()['error']);
//                exit();
//            }
//        }

        //Whatever happened, close the connection.
        $smtp->quit(true);
    }

    public function Send()
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            // Specify main and backup SMTP servers
            $mail->Host = "$this->smtp_server" . ";" . "$this->smtp_backup_server";
            // Enable SMTP authentication
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtp_username;
            $mail->Password = $this->smtp_password;
            // Enable TLS encryption, `ssl` also accepted
            $mail->SMTPSecure = $this->smtp_encryption;
            // TCP port to connect to
            $mail->Port = $this->smtp_port;

//            $mail->Debugoutput = function($str, $level) {
//                $this->logger->info("Mailer debug output. Level: ".$level. " Message:". $str);
//            };

            // Recipients
            // Set 'From' email & name
            if ($this->from_email && $this->from_name) {
                $mail->setFrom($this->from_email, $this->from_name);
            } elseif ($this->from_email && !$this->from_name) {
                $mail->setFrom($this->from_email);
            }

            // Set recipient's name & email
            if ($this->to && is_array($this->to)) {
                foreach ($this->to as $_to_email => $_to_name) {
                    $mail->addAddress($_to_email, $_to_name);
                }
            } else {
                if ($this->to_email && $this->to_name) {
                    $mail->addAddress($this->to_email, $this->to_name);
                } elseif ($this->to_email && !$this->to_name) {
                    $mail->addAddress($this->to_email);
                } else {
                    $this->logger->error("Can't send email, No recipient.");
                    throw new \Exception("Can't send email, No recipient.");
                }
            }

            // Set reply to email & email
            if ($this->reply_to_email && $this->reply_to_name) {
                $mail->addReplyTo($this->reply_to_email, $this->reply_to_name);
            } elseif ($this->reply_to_email && !$this->reply_to_name) {
                $mail->addReplyTo($this->reply_to_email);
            }

            // If CC, then set CC
            if ($this->is_cc) {
                if (is_array($this->cc)) {
                    foreach ($this->cc as $key => $value) {
                        $mail->addCC($key, $value);
                    }
                }
            }

            // If BCC, then set BCC
            if ($this->is_bcc) {
                if (is_array($this->bcc)) {
                    foreach ($this->bcc as $key => $value) {
                        $mail->addBCC($key, $value);
                    }
                }
            }

            // If attachment, then set attachment's path
            if (!empty($this->attachment)) {
                foreach ($this->attachment as $item) {
                    $mail->addAttachment($item);
                }
            }

            //Content
            // Set email format to HTML
            $mail->isHTML(true);

            // If subject, Set subject
            if ($this->subject) {
                $mail->Subject = $this->subject;
            }

            // If message, Set body
            if ($this->message) {
                $mail->Body = $this->message;
            } else {
                $this->logger->error("Can't send email, No message.");
                throw new \Exception("Can't send email, No message.");
            }

            // If alt message, Set AltBody
            if ($this->alt_message) {
                $mail->AltBody = $this->alt_message;
            }

            // If sent, log email info then return true.
            if (!$mail->send()) {
                throw new \Exception("Failed to send email!");
            }

            $logMessage = "Email sent successfully. ";
            $logMessage .= "From: ".$this->from_name." <".$this->from_email."> ";
            if ($this->to) {
                foreach ($this->to as $_to_email => $_to_name) {
                    $logMessage .= "To: ".$_to_name." <".$_to_email."> ";
                }
            } else {
                $logMessage .= "To: ".$this->to_name." <".$this->to_email."> ";
            }
            if ($this->is_cc) {
                if (is_array($this->cc)) {
                    foreach ($this->cc as $_cc_email => $_cc_name) {
                        $logMessage .= "CC: ".$_cc_name." <".$_cc_email."> ";
                    }
                } else {
                    $logMessage .= "CC: <".$this->cc."> ";
                }
            }
            if ($this->is_bcc) {
                $logMessage .= "BCC: <".$this->bcc."> ";
            }
            $with_attachment = !empty($this->attachment) ? "Yes" : "No";
            $logMessage .= "Includes attachment: ".$with_attachment;

            $this->logger->info($logMessage);
            return true;
        } catch (Exception $e) {
            $message = 'Message could not be sent. Log Error: '.$e->errorMessage();
            $this->logger->error($message);
            return false;
        } catch (\Exception $e) {
            $message = 'Message could not be sent. Log Error: '.$e->getMessage().', Mailer Error: ' . $mail->ErrorInfo;
            $this->logger->error($message);
            return false;
        }
    }
}