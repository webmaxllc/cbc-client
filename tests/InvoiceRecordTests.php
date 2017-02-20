<?php

use Webmax\VidVerifyClient\Model\InvoiceRecord;

class InvoiceRecordTests extends ClientTestCase
{
    const BORROWER_ID = 123;
    const WATCHED_VIDEO_COUNT = 5;

    public function testAcceptsBorrowerId ()
    {
    $invoiceRecord = $this->createInvoiceRecord();

    $this->assertSame(self::BORROWER_ID, $invoiceRecord->getBorrowerId());
    }

    public function testAcceptsWatchedVideoCount()
    {
    $invoiceRecord = $this->createInvoiceRecord();

    $this->assertSame(self::WATCHED_VIDEO_COUNT, $invoiceRecord->getWatchedVideoCount());
    }

    private function createInvoiceRecord()
    {
      $invoiceRecord = new InvoiceRecord();
      $this->injectPropertyValue($invoiceRecord, "borrowerId", self::BORROWER_ID);
      $this->injectPropertyValue($invoiceRecord, "watchedVideoCount", self::WATCHED_VIDEO_COUNT);

      return $invoiceRecord;
    }
}
