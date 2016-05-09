<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\CoreBundle\Controller\API\User;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use Claroline\CoreBundle\Manager\FacetManager;
use FOS\RestBundle\Controller\Annotations\Get;
use Claroline\CoreBundle\Event\Profile\ProfileLinksEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @NamePrefix("api_")
 */
class ProfileController extends FOSRestController
{
    /**
     * @DI\InjectParams({
     *     "facetManager" = @DI\Inject("claroline.manager.facet_manager"),
     *     "tokenStorage" = @DI\Inject("security.token_storage")
     * })
     */
    public function __construct(
        FacetManager $facetManager,
        TokenStorageInterface $tokenStorage
    ) {
        $this->facetManager = $facetManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Get("/profile/private/facets", name="get_profile_private_facet", options={ "method_prefix" = false })
     * @View(serializerGroups={"api_profile"})
     */
    public function getPrivateFacetsAction()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $facets = $this->facetManager->getPrivateFacets($user);
        $ffvs = $this->facetManager->getFieldValuesByUser($user);

        foreach ($facets as $facet) {
            foreach ($facet->getPanelFacets() as $panelFacet) {
                foreach ($panelFacet->getFieldsFacet() as $field) {
                    foreach ($ffvs as $ffv) {
                        if ($ffv->getFieldFacet()->getId() === $field->getId()) {
                            //for serialization
                            $field->setUserFieldValue($ffv);
                        }
                    }
                }
            }
        }

        return $facets;
    }

    /**
     * @Get("/profile/links", name="get_profile_links", options={ "method_prefix" = false })
     */
    public function getProfileLinksAction()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $request = $this->get('request');
        $profileLinksEvent = new ProfileLinksEvent($user, $request->getLocale());
        $this->get('event_dispatcher')->dispatch(
            'profile_link_event',
            $profileLinksEvent
        );

        return $profileLinksEvent->getLinks();
    }
}
