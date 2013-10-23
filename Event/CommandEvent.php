<?php

namespace Scribe\PheanstalkBundle\Event;

use Symfony\Component\EventDispatcher\Event as EventBase;

use Pheanstalk_PheanstalkInterface;

class CommandEvent extends EventBase
{
    const BURY = "scribe.pheanstalk.event.bury";
    const DELETE = "scribe.pheanstalk.event.delete";
    const IGNORE = "scribe.pheanstalk.event.ignore";
    const KICK = "scribe.pheanstalk.event.kick";
    const KICK_JOB = "scribe.pheanstalk.event.kick_job";
    const LIST_TUBE_USED = "scribe.pheanstalk.event.list_tube_used";
    const LIST_TUBES = "scribe.pheanstalk.event.list_tubes";
    const LIST_TUBES_WATCHED = "scribe.pheanstalk.event.list_tubes_watched";
    const PAUSE_TUBE = "scribe.pheanstalk.event.pause_tube";
    const PEEK = "scribe.pheanstalk.event.peek";
    const PEEK_READY = "scribe.pheanstalk.event.peek_ready";
    const PEEK_DELAYED = "scribe.pheanstalk.event.peek_delayed";
    const PEEK_BURIED = "scribe.pheanstalk.event.peek_buried";
    const PUT = "scribe.pheanstalk.event.put";
    const PUT_IN_TUBE = "scribe.pheanstalk.event.put_in_tube";
    const RELEASE = "scribe.pheanstalk.event.release";
    const RESERVE = "scribe.pheanstalk.event.reserve";
    const RESERVE_FROM_TUBE = "scribe.pheanstalk.event.reserve_from_tube";
    const STATS = "scribe.pheanstalk.event.stats";
    const STATS_TUBE = "scribe.pheanstalk.event.stats_tube";
    const STATS_JOB = "scribe.pheanstalk.event.stats_job";
    const TOUCH = "scribe.pheanstalk.event.touch";
    const USE_TUBE = "scribe.pheanstalk.event.use_tube";
    const WATCH = "scribe.pheanstalk.event.watch";
    const WATCH_ONLY = "scribe.pheanstalk.event.watch_only";

    private $pheanstalk;
    private $payload;

    public function __construct(Pheanstalk_PheanstalkInterface $pheanstalk, array $payload = array())
    {
        $this->pheanstalk = $pheanstalk;
        $this->payload = $payload;
    }

    /**
     * @return \Scribe\PheanstalkBundle\Proxy\PheanstalkInterface
     */
    public function getPheanstalk()
    {
        return $this->pheanstalk;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
