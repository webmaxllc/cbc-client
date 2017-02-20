<?php

use Webmax\VidVerifyClient\Model\Activity;

class ActivityTests extends ClientTestCase
{
    const BORROWER_ID = 1;
    const VIDEO_ID = 4;
    const VIDEO_TITLE = 'Video Title';
    const VIDEO_START_TIME = '0:0:0';
    const RAW_VIDEO_LENGTH = '0:1:52';
    const RAW_WATCHED_VIDEO_LENGTH = '0:0:10';

    public function testAcceptsBorrowerId ()
    {
        $activity = $this->createActivity();

        $this->assertSame(self::BORROWER_ID, $activity->getBorrowerId());
    }

    public function testAcceptsVideoId()
    {
        $activity = $this->createActivity();

        $this->assertSame(self::VIDEO_ID, $activity->getVideoId());
    }

    public function testAcceptsVideoTitle()
    {
        $activity = $this->createActivity();

        $this->assertSame(self::VIDEO_TITLE, $activity->getVideoTitle());
    }

    public function testAcceptsVideoStartTime()
    {
        $activity = $this->createActivity();

        $this->assertSame(self::VIDEO_START_TIME, $activity->getVideoStartTime());
    }

    public function testAcceptsRawVideoLength()
    {
        $activity = $this->createActivity();

        $this->assertSame(self::RAW_VIDEO_LENGTH, $activity->getRawVideoLength());
    }

    public function testAcceptsRawWatchedVideoLength()
    {
        $activity = $this->createActivity();

        $this->assertSame(self::RAW_WATCHED_VIDEO_LENGTH, $activity->getRawWatchedVideoLength());
    }

    public function testConvertsRawVideoLength()
    {
        $activity = $this->createActivity();
        $rawVideoLength = $activity->getVideoLength();

        $this->assertInstanceOf('DateInterval', $rawVideoLength);
        $this->assertSame(1, $rawVideoLength->i);
        $this->assertSame(52, $rawVideoLength->s);
    }

    public function testConvertsRawWatchedVideoLength()
    {
        $activity = $this->createActivity();
        $rawWatchedVideoLength = $activity->getWatchedVideoLength();

        $this->assertInstanceOf('DateInterval', $rawWatchedVideoLength);
        $this->assertSame(10, $rawWatchedVideoLength->s);
    }

    private function createActivity()
    {
        $activity = new Activity();

        $this->injectPropertyValue($activity, "borrowerId", self::BORROWER_ID);
        $this->injectPropertyValue($activity, "videoId", self::VIDEO_ID);
        $this->injectPropertyValue($activity, "videoTitle", self::VIDEO_TITLE);
        $this->injectPropertyValue($activity, "videoStartTime", self::VIDEO_START_TIME);
        $this->injectPropertyValue($activity, "rawVideoLength", self::RAW_VIDEO_LENGTH);
        $this->injectPropertyValue($activity, "rawWatchedVideoLength", self::RAW_WATCHED_VIDEO_LENGTH);

        return $activity;
    }
}
