<?php

use Webmax\VidVerifyClient\Model\BorrowerActivity;

class BorrowerActivityTests extends ClientTestCase
{
    const BORROWER_ID = 3;
    const VIDEO_ID = 7;
    const VIDEO_TITLE = 'Video Title';
    const VIDEO_START_TIME = '0:0:0';
    const RAW_VIDEO_LENGTH = '0:1:5';
    const RAW_WATCHED_VIDEO_LENGTH = '0:0:3';
    const TIMES_WATCHED = 2;

    public function testAcceptsBorrowerId ()
    {
        $borrowerActivity = $this->createBorrowerActivity();

        $this->assertSame(self::BORROWER_ID, $borrowerActivity->getBorrowerId());
    }

    public function testAcceptsVideoId()
    {
        $borrowerActivity = $this->createBorrowerActivity();

        $this->assertSame(self::VIDEO_ID, $borrowerActivity->getVideoId());
    }

    public function testAcceptsVideoTitle()
    {
        $borrowerActivity = $this->createBorrowerActivity();

        $this->assertSame(self::VIDEO_TITLE, $borrowerActivity->getVideoTitle());
    }

    public function testAcceptsVideoStartTime()
    {
        $borrowerActivity = $this->createBorrowerActivity();

        $this->assertSame(self::VIDEO_START_TIME, $borrowerActivity->getVideoStartTime());
    }

    public function testAcceptsRawVideoLength()
    {
        $borrowerActivity = $this->createBorrowerActivity();

        $this->assertSame(self::RAW_VIDEO_LENGTH, $borrowerActivity->getRawVideoLength());
    }

    public function testAcceptsRawWatchedVideoLength()
    {
        $borrowerActivity = $this->createBorrowerActivity();

        $this->assertSame(self::RAW_WATCHED_VIDEO_LENGTH, $borrowerActivity->getRawWatchedVideoLength());
    }

    public function testAcceptsTimesWatched()
    {
        $borrowerActivity = $this->createBorrowerActivity();

        $this->assertSame(self::TIMES_WATCHED, $borrowerActivity->getTimesWatched());
    }

    public function testConvertsRawVideoLength()
    {
        $borrowerActivity = $this->createBorrowerActivity();
        $rawVideoLength = $borrowerActivity->getVideoLength();

        $this->assertInstanceOf('DateInterval', $rawVideoLength);
        $this->assertSame(1, $rawVideoLength->i);
        $this->assertSame(5, $rawVideoLength->s);
    }

    public function testConvertsRawWatchedVideoLength()
    {
        $borrowerActivity = $this->createBorrowerActivity();
        $rawWatchedVideoLength = $borrowerActivity->getWatchedVideoLength();

        $this->assertInstanceOf('DateInterval', $rawWatchedVideoLength);
        $this->assertSame(3, $rawWatchedVideoLength->s);
    }

    private function createBorrowerActivity()
    {
        $activity = new BorrowerActivity();

        $this->injectPropertyValue($activity, "borrowerId", self::BORROWER_ID);
        $this->injectPropertyValue($activity, "videoId", self::VIDEO_ID);
        $this->injectPropertyValue($activity, "videoTitle", self::VIDEO_TITLE);
        $this->injectPropertyValue($activity, "videoStartTime", self::VIDEO_START_TIME);
        $this->injectPropertyValue($activity, "rawVideoLength", self::RAW_VIDEO_LENGTH);
        $this->injectPropertyValue($activity, "rawWatchedVideoLength", self::RAW_WATCHED_VIDEO_LENGTH);
        $this->injectPropertyValue($activity, "timesWatched", self::TIMES_WATCHED);

        return $activity;
    }
}
