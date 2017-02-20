<?php

namespace Webmax\VidVerifyClient\Model;

class InvoiceRecord
{
    /**
     * Borrower id
     *
     * @var integer
     */
    protected $borrowerId;

    /**
     * Amount of videos watched
     *
     * @var integer
     */
    protected $watchedVideoCount;


    public function getBorrowerId()
    {
        return $this->borrowerId;
    }

    public function getWatchedVideoCount()
    {
        return $this->watchedVideoCount;
    }
}
