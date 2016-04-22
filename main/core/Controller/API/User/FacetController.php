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

use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use Claroline\CoreBundle\Manager\FacetManager;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\Request;
use Claroline\CoreBundle\Entity\Facet\Facet;
use Claroline\CoreBundle\Entity\Facet\PanelFacet;

/**
 * @NamePrefix("api_")
 */
class FacetController extends FOSRestController
{
    /**
     * @DI\InjectParams({
     *     "facetManager" = @DI\Inject("claroline.manager.facet_manager"),
     *     "request"      = @DI\Inject("request")
     * })
     */
    public function __construct(
        FacetManager $facetManager,
        Request $request
    ) {
        $this->facetManager = $facetManager;
        $this->request = $request;
    }

    /**
     * @View(serializerGroups={"api_facet_admin"})
     */
    public function getFacetsAction()
    {
        return $this->facetManager->getFacets();
    }

    /**
     * @View(serializerGroups={"api_facet_admin"})
     * @Post("facet/create", name="post_facet", options={ "method_prefix" = false })
     */
    public function createFacetAction()
    {
        $facet = $this->request->request->get('facet');

        //there should be a facet validation here

        return $this->facetManager->createFacet($facet['name'], isset($facet['force_creation_form']));
    }

    /**
     * @View(serializerGroups={"api_facet_admin"})
     * @Put("facet/{facet}", name="put_facet", options={ "method_prefix" = false })
     */
    public function editFacetAction(Facet $facet)
    {
        $data = $this->request->request->get('facet');

        //there should be a facet validation here

        return $this->facetManager->editFacet($facet, $data['name'], isset($data['force_creation_form']));
    }

    /**
     * @View(serializerGroups={"api_facet_admin"})
     * @Delete("facet/{facet}", name="delete_facet", options={ "method_prefix" = false })
     */
    public function deleteFacetAction(Facet $facet)
    {
        $this->facetManager->removeFacet($facet);

        return [];
    }

    /**
     * @View(serializerGroups={"api_facet_admin"})
     * @Post("facet/{facet}/panel/create", name="post_panel_facet", options={ "method_prefix" = false })
     */
    public function createFacetPanelAction(Facet $facet)
    {
        $panel = $this->request->request->get('panel');

        //there should be a facet validation here

        return $this->facetManager->addPanel($facet, $panel['name'], isset($panel['collapse']));
    }

    /**
     * @View(serializerGroups={"api_facet_admin"})
     * @Post("facet/panel/{panel}/field/create", name="post_field_facet", options={ "method_prefix" = false })
     */
    public function createFieldFacetAction(PanelFacet $panel)
    {
        $field = $this->request->request->get('field');

        //there should be a facet validation here

        return $this->facetManager->addField(
            $panel,
            $field['name'],
            $field['type'],
            isset($field['visible']),
            isset($field['editable'])
        );
    }

    public function getAvailableFieldTypesAction()
    {
    }
}
