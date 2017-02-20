<?php

namespace Webmax\VidVerifyClient\Model;

use DateTime;
use DateInterval;

class Activity
{
    /**
     * Borrower id
     *
     * @var integer
     */
    protected $borrowerId;

    /**
     * Property id
     *
     * @var integer
     */
    protected $videoId;

    /**
     * Video title
     *
     * @var string
     */
    protected $videoTitle;

    /**
     * Video start time
     *
     * @var DateTime
     */
    protected $videoStartTime;

    /**
     * Video length (raw)
     *
     * @var string
     */
    protected $rawVideoLength;

    /**
     * Amount of video watched (raw)
     *
     * @var string
     */
    protected $rawWatchedVideoLength;


    public function getBorrowerId()
    {
        return $this->borrowerId;
    }

    public function getVideoId()
    {
        return $this->videoId;
    }

    public function getVideoTitle()
    {
        return $this->videoTitle;
    }

    public function getVideoStartTime()
    {
        return $this->videoStartTime;
    }

    public function getRawVideoLength()
    {
        return $this->rawVideoLength;
    }

    public function getVideoLength()
    {
        return $this->convertToInterval($this->rawVideoLength);
    }

    public function getRawWatchedVideoLength()
    {
        return $this->rawWatchedVideoLength;
    }

    public function getWatchedVideoLength()
    {
        return $this->convertToInterval($this->rawWatchedVideoLength);
    }

    protected function convertToInterval($string)
    {
        $parts = explode(":", $string, 3);
        $stdString = sprintf("PT%dH%dM%dS", $parts[0], $parts[1], $parts[2]);

        return new DateInterval($stdString);
    }
}
