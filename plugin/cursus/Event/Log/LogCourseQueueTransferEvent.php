<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\CursusBundle\Event\Log;

use Claroline\CoreBundle\Event\Log\LogGenericEvent;
use Claroline\CursusBundle\Entity\CourseRegistrationQueue;
use Claroline\CursusBundle\Entity\CourseSession;

class LogCourseQueueTransferEvent extends LogGenericEvent
{
    const ACTION = 'cursusbundle-course-queue-transfer';

    public function __construct(CourseRegistrationQueue $queue, CourseSession $session)
    {
        $course = $queue->getCourse();
        $user = $queue->getUser();
        $details = [];
        $details['userId'] = $user->getId();
        $details['username'] = $user->getUsername();
        $details['firsName'] = $user->getFirstName();
        $details['lastName'] = $user->getLastName();
        $details['courseId'] = $course->getId();
        $details['courseTitle'] = $course->getTitle();
        $details['courseCode'] = $course->getCode();
        $details['sessionId'] = $session->getId();
        $details['sessionName'] = $session->getName();
        $details['sessionStatus'] = $session->getSessionStatus();
        $details['sessionType'] = $session->getType();

        parent::__construct(
            self::ACTION,
            $details,
            $user
        );
    }

    /**
     * @return array
     */
    public static function getRestriction()
    {
        return [self::DISPLAYED_ADMIN];
    }
}
