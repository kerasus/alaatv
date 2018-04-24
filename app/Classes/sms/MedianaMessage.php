<?php
namespace App\Classes\sms;

class MedianaMessage
{
    /**
     * The message content.
     *
     * @var string
     */
    public $content;

    /**
     * @var int
     */
    public $sendAt;

    public $from = null;

    public $op = null;
    /**
     * Create a new message instance.
     *
     * @param string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;

    }
    /**
     * Create a new message instance.
     *
     * @param string $content
     */
    public static function create($content = '')
    {
        new static($content);
    }
    /**
     * Set the message content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setFrom($from){
        $this->from = $from;
        return $this;
    }

    public function setOp($op){
        $this->op = $op;
        return $this;

    }
    /**
     * Set the message send at.
     *
     * @param \DateTime $sendAt
     *
     * @return $this
     */
    public function sendAt($sendAt)
    {
        $this->sendAt = $sendAt;
        return $this;
    }
}