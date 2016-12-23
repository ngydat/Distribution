<?php
/**
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 */

namespace Claroline\SurveyBundle\Listener;

use Claroline\CoreBundle\Event\Notification\NotificationParametersEvent;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class NotificationParametersListener.
 *
 * @DI\Service()
 */
class NotificationParametersListener
{
    /**
     * @param NotificationParametersEvent $event
     *
     * @DI\Observe("icap_notification_parameters_event")
     */
    public function onGetTypesForParameters(NotificationParametersEvent $event)
    {
        $children = [
            'create_survey',
            'delete_survey',
            'closed_survey',
            'answers_to_survey',
        ];
        $event->addTypes('claroline_survey', false, 'claroline_survey', $children);
        $event->addTypes($children, true, 'claroline_survey');
    }
}