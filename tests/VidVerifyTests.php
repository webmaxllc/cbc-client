<?php

use Webmax\VidVerifyClient\Model\Activity;

class VidVerifyTests extends ClientTestCase
{
    public function testInvoiceReportSuccessResponse()
    {
        $client = $this->createClient($this->getHandledConfig('invoice-report'));
    }

    public function testBorrowerActivitiesSuccessResponse()
    {
        $client = $this->createClient($this->getHandledConfig('borrower-activity'));
    }

    public function testAllActivitiesSuccessResponse()
    {
        $client = $this->createClient($this->getHandledConfig('all-activity'));
    }

    public function testInvoiceReportSuccessResponseIsCorrect()
    {
        $client = $this->createClient($this->getHandledConfig('invoice-report'));
        $year = "2016";
        $month = "December";

        $response = $client->getInvoiceReport($year, $month);

        $this->assertInternalType('array', $response);
        $this->assertCount(2, $response);
        $this->assertInstanceOf('Webmax\VidVerifyClient\Model\InvoiceRecord', $response[0]);
        $this->assertInstanceOf('Webmax\VidVerifyClient\Model\InvoiceRecord', $response[1]);
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
        $this->assertInstanceOf('Webmax\VidVerifyClient\Model\Activity', $response[0]);
        $this->assertInstanceOf('Webmax\VidVerifyClient\Model\Activity', $response[1]);
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
        $this->assertInstanceOf('Webmax\VidVerifyClient\Model\BorrowerActivity', $response[0]);
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
            case 'all-activity':
                $response = $this->mockResponse(200, array(), $this->getData('all-activity'));
                break;
            case 'borrower-activity':
                $response = $this->mockResponse(200, array(), $this->getData('borrower-activity'));
                break;
            case 'invoice-report':
                $response = $this->mockResponse(200, array(), $this->getData('invoice-report'));
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
