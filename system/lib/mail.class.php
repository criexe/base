<?php


class mail
{

    public static function send($to = null, $subject = null, $content = null, $layout = false)
    {
        try
        {
            $smtp_host       = cx::option('mail.smtp.host');
            $smtp_username   = cx::option('mail.smtp.username');
            $smtp_password   = cx::option('mail.smtp.password');
            $smtp_port       = cx::option('mail.smtp.port');

            $sender_name  = cx::option('mail.sender.name');
            $sender_email = cx::option('mail.sender.email');

            $data            = [];
            $data['subject'] = $subject;

            // Inckude
            require_once SYSTEM_LIB_PATH . DS . 'PHPMailer' . DS . 'PHPMailerAutoload.php';

            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;

            $mail->isSMTP();
            $mail->SMTPAuth   = true;
            $mail->Host       = $smtp_host;
            $mail->Username   = $smtp_username;
            $mail->Password   = $smtp_password;
            $mail->Port       = $smtp_port;
            $mail->SMTPSecure = 'tls';

            $mail->setFrom($sender_email, $sender_name);
            $mail->addReplyTo($sender_email, $sender_name);

            if(is_array($to))
            {
                foreach($to as $addr) $mail->addAddress($addr);
            }
            else
            {
                $mail->addAddress($to);
            }

            $mail->isHTML(true);

            $mail->Subject = $subject;
            $mail->Body    = cx::render([$content], $data, ['layout' => $layout]);

            if( ! $mail->send())
            {
                throw_exception($mail->ErrorInfo);
            }
            else
            {
                return true;
            }
        }
        catch(Exception $e)
        {
            logger::add('mail::send() : ' . $e->getMessage(), 'mail');
            return false;
        }
    }

}