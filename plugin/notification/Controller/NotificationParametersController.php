<?php
/**
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * Author: Panagiotis TSAVDARIS
 *
 * Date: 4/8/15
 */

namespace Icap\NotificationBundle\Controller;

use Claroline\CoreBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotificationParametersController extends Controller
{
    /**
     * @Route("/parameters/user", name="icap_notification_user_parameters")
     * @Method({"GET"})
     * @Template("IcapNotificationBundle:UserParameters:config.html.twig")
     * @ParamConverter("user", options={"authenticatedUser" = true})
     */
    public function getUserParametersAction(User $user)
    {
        $parametersManager = $this->getParametersManager();
        $parameters = $parametersManager->getParametersByUserId($user->getId());
        $types = $parametersManager->allTypesList($parameters);

        return [
            'types' => $types,
            'parameters' => $parameters,
            'allowPhoneAndMail' => $this->get('claroline.config.platform_config_handler')->getParameter('notification_allow_phone_and_mail'),
         ];
    }

    /**
     * @Route("/parameters/admin", name="icap_notification_admin_parameters")
     * @Method({"GET"})
     * @Template("IcapNotificationBundle:AdminParameters:admin.config.html.twig")
     * @ParamConverter("user", options={"authenticatedUser" = true})
     */
    public function getAdminParametersAction()
    {
        $parametersManager = $this->getParametersManager();
        $parameters = $parametersManager->getAdminParameters();
        $types = $parametersManager->allTypesList($parameters);

        return [
            'types' => $types,
            'parameters' => $parameters,
            'allowPhoneAndMail' => $this->get('claroline.config.platform_config_handler')->getParameter('notification_allow_phone_and_mail'),
            'lockedDisplay' => $this->get('claroline.config.platform_config_handler')->getParameter('notification_locked_display_enabled_types'),
            'lockedPhone'=> $this->get('claroline.config.platform_config_handler')->getParameter('notification_locked_phone_enabled_types'),
            'lockedMail' => $this->get('claroline.config.platform_config_handler')->getParameter('notification_locked_mail_enabled_types'),
            'lockedRss' => $this->get('claroline.config.platform_config_handler')->getParameter('notification_locked_rss_enabled_types'),

        ];
    }

    /**
     * @Route("/regenerate_rss", name="icap_notification_regenerate_rss_url")
     * @Template("IcapNotificationBundle:Parameters:config.html.twig")
     * @ParamConverter("user", options={"authenticatedUser" = true})
     */
    public function regenerateRssUrlAction(User $user)
    {
        $parametersManager = $this->getParametersManager();
        $parameters = $parametersManager->regenerateRssId($user->getId());
        $types = $parametersManager->allTypesList($parameters);

        return ['types' => $types, 'rssId' => $parameters->getRssId(), 'parameters' => $parameters];
    }

    /**
     * @return \Icap\NotificationBundle\Manager\NotificationParametersManager
     */
    private function getParametersManager()
    {
        return $this->get('icap.notification.manager.notification_parameters');
    }
}