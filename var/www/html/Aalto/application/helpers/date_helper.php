<?php



if (!function_exists("genericSendEmail")) {

    function genericSendEmail($to, $cc = '', $from,$fromName, $subject, $content, $attachments = array(),$bcc='')

    {

        $CI =& get_instance();

        $CI->load->library('email');

        $config['mailtype'] = 'html';

        $CI->email->initialize($config);

        $CI->email->from($from, $fromName);

        $CI->email->to($to);

        if ($cc != '') {

            $CI->email->cc($cc);

        }

        if ($bcc != '') {

            $CI->email->bcc($bcc);

        }

        foreach($attachments as $attachment)

        {

            if(file_exists($attachment))

            {

                $CI->email->attach($attachment);

            }

        }

        $CI->email->subject($subject);

        $CI->email->message($content);

        return $CI->email->send();

    }

}

?>