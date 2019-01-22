<?php

use Fuel\Core\Config,
    Fuel\Core\Log,
    Fuel\Core\View,
    Email\Email;

class Model_Service_Mail
{

    public static function send($mail_data)
    {
        try {
            $email = Email::forge();

            // set mail_from
            if (empty($mail_data['from'])) {
                $email->from(Config::get('email.defaults.from.email'), Config::get('email.defaults.from.name'));
            } else {
                $email->from($mail_data['from']);
            }
            // set mail_to
            if (is_string($mail_data['to'])) {
              $email->to($mail_data['to']);
            } elseif (is_array($mail_data['to']) && count($mail_data['to']) === 2) {
              $email->to($mail_data['to'][0]);
              $email->bcc($mail_data['to'][1]);
            } elseif (is_array($mail_data['to'])) {
              $email->to(Config::get('email.defaults.from.email'), Config::get('email.defaults.from.name'));
              $email->bcc($mail_data['to']);
            }
            
            // set mail cc
            if (!empty($mail_data['cc'])) {
              $email->cc($mail_data['cc']);
            }
            
            //set mail_subject
            $email->subject(html_entity_decode($mail_data['subject'], ENT_QUOTES));
            
            // set mail_attach
            if (!empty($mail_data['attach'])) {
              $email->attach(DOCROOT . $mail_data['attach']);
            }
            
            // set mail_body
            // $email->body(mb_convert_encoding(View::forge('email/' . $view, $body), 'jis'));
            if (!empty($mail_data['view']) && !empty($mail_data['body'])) {
              $email->html_body(View::forge('email/' . $mail_data['view'], $mail_data['body']));
            } else {
              $email->body($mail_data['body']);
            }

            // send mail
            $email->send();
            return true;
        } catch (\EmailValidationFailedException $e) {
            Log::error($e->getMessage().'/'. $e->getCode());
        } catch (\EmailSendingFailedException $e) {
            Log::error($e->getMessage().'/'. $e->getCode());
        } catch (Exception $e) {
            Log::error($e->getMessage().'/'. $e->getCode());
        }

        return false;
    }

}
