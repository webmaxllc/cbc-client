<?php

use Webmax\CBCClient\Model\Activity;

class CBCTests extends ClientTestCase
{
    public function testCreditReportSuccessResponse()
    {
        $client = $this->createClient($this->getHandledConfig('credit-report'));
    }

    public function testCreditReportSuccessResponseIsCorrect()
    {
        $client = $this->createClient($this->getHandledConfig('credit-report'));

        $borrower = new stdClass();
        $borrower->ID = "Borrower";
        $borrower->FirstName = "Bronell";
        $borrower->LastName = "Bolton";
        $borrower->SSN = "660601234";
        $borrower->residence = new stdClass();
        $borrower->residence->StreetAddress = "131301 Test Rd";
        $borrower->residence->City = "Pittsburgh";
        $borrower->residence->State = "PA";
        $borrower->residence->PostalCode = "15220";
        $borrower->residence->BorrowerResidencyType = "Current";

        $response = $client->getCreditReport($year, $month);

        $this->assertInternalType('array', $response);
        $this->assertCount(2, $response);
        $this->assertInstanceOf('Webmax\CBCClient\Model\CreditReport', $response[0]);
        $this->assertInstanceOf('Webmax\CBCClient\Model\CreditReport', $response[1]);
    }

    public function testInvoiceReportSuccessResponseIsNull()
    {
        $client = $this->createClient($this->getHandledConfig('null'));
        $year = "2016";
        $month = "December";

        $response = $client->getInvoiceReport($year, $month);

        $this->assertNull($response);
    }

    public function testAllActivitiesSuccessResponseIsCorrect()
    {
        $client = $this->createClient($this->getHandledConfig('all-activity'));
        $to = new DateTime("1 year ago");
        $from = new DateTime();

        $response = $client->getAllActivities($to, $from);

        $this->assertInternalType('array', $response);
        $this->assertCount(2, $response);
        $this->assertInstanceOf('Webmax\CBCClient\Model\Activity', $response[0]);
        $this->assertInstanceOf('Webmax\CBCClient\Model\Activity', $response[1]);
    }

    public function testAllActivitiesEmptyResponseIsNull()
    {
        $client = $this->createClient($this->getHandledConfig('null'));
        $to = new DateTime("1 year ago");
        $from = new DateTime();

        $response = $client->getAllActivities($to, $from);

        $this->assertNull($response);
    }

    public function testBorrowerActivitiesSuccessResposeIsCorrect()
    {
        $client = $this->createClient($this->getHandledConfig('borrower-activity'));
        $borrowerNumber = 23;

        $response = $client->getBorrowerActivities($borrowerNumber);

        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);
        $this->assertInstanceOf('Webmax\CBCClient\Model\BorrowerActivity', $response[0]);
    }

    public function testBorrowerActivitiesSuccessResponseIsNull()
    {
        $client = $this->createClient($this->getHandledConfig('null'));
        $borrowerNumber = 23;

        $response = $client->getBorrowerActivities($borrowerNumber);

        $this->assertNull($response);
    }

    private function getHandledConfig($which)
    {
        switch ($which) {
            case 'credit-report':
                $response = $this->mockResponse(200, array(), $this->getData('credit-report'));
                break;
            case 'null':
                $response = $this->mockResponse(200, array(), 'null');
                break;
            default:
                throw new \InvalidArgumentException('Unable to create listing handler configuration');
        }

        return array(
            'handler' => $this->mockHandler($response)
        );
    }
}
