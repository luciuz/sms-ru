<?php

namespace luciuz\smsru;

class Sms
{
    /**
     * @var string
     */
    public $to;

    /**
     * @var string
     */
    public $msg;

    /**
     * Sms constructor.
     * @param $to
     * @param $msg
     */
    public function __construct($to, $msg)
    {
        $this->to = $to;
        $this->msg = $msg;
    }
}
