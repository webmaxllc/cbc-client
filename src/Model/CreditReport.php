<?php

namespace Webmax\CBCClient\Model;

class CreditReport
{
    /**
     * Loan id
     *
     * @var integer
     */
    protected $loanId;

    /**
     * Borrower id
     *
     * @var integer
     */
    protected $borrowerId;

    /**
     * Equifax Scred
     *
     * @var integer
     */
    protected $equifaxScore;



    public function getBorrowerId()
    {
        return $this->borrowerId;
    }

    public function getLoanId()
    {
        return $this->loanId;
    }

}
