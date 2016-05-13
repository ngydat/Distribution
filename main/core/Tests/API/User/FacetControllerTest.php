<?php

namespace Claroline\CoreBubdle\Tests\API\User;

use Claroline\CoreBundle\Entity\User;
use Claroline\CoreBundle\Library\Testing\TransactionalTestCase;
use Claroline\CoreBundle\Library\Testing\Persister;

/**
 * Specific tests for organizations
 * How to run:
 * - create database
 * - php app/console claroline:install --env=test
 * - bin/phpunit vendor/claroline/core-bundle/Tests/API/User/FacetControllerTest.php -c app/phpunit.xml.
 */
class FacetControllerTest extends TransactionalTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->persister = $this->client->getContainer()->get('claroline.library.testing.persister');
    }

    public function testControllerIsProtected()
    {
        $user = $this->persister->user('user');
        $this->login($user);
        $this->client->request('GET', '/api/facets.json');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $manager = $this->createManager();
        $this->login($manager);
        $this->client->request('GET', '/api/facets.json');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testGetFacetsAction()
    {
        $this->persister->facet('facet', true, true);
        $manager = $this->createManager();
        $this->login($manager);
        $this->client->request('GET', '/api/facets.json');
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data[0]['name'], 'facet');
    }

    public function testCreateFacetAction()
    {
        $manager = $this->createManager();
        $this->login($manager);

        $fields = array(
            'name' => 'facet',
            'force_creation_form' => false,
            'is_main' => true,
        );

        $form = array('facet' => $fields);
        $this->client->request('POST', '/api/facet/create', $form);
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data['name'], 'facet');
    }

    public function testEditFacetAction()
    {
        $facet = $this->persister->facet('facet', true, true);
        $manager = $this->createManager();
        $this->login($manager);

        $fields = array(
            'name' => 'newFacet',
            'force_creation_form' => false,
            'is_main' => false,
        );

        $form = array('facet' => $fields);
        $this->client->request('PUT', "/api/facet/{$facet->getId()}", $form);
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data['name'], 'newFacet');
        $this->assertEquals($data['force_creation_form'], false);
        $this->assertEquals($data['is_main'], false);
    }

    public function testDeleteFacetAction()
    {
        $facet = $this->persister->facet('facet', true, true);
        $manager = $this->createManager();
        $this->login($manager);
        $this->client->request('DELETE', "/api/facet/{$facet->getId()}");
        $this->client->request('GET', '/api/facets.json');
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data, []);
    }

    public function testSetFacetRolesAction()
    {
        $facet = $this->persister->facet('facet', true, true);
        $manager = $this->createManager();
        $roles[] = $this->persister->role('ROLE_1');
        $roles[] = $this->persister->role('ROLE_2');
        $this->persister->flush();
        $this->login($manager);
        $queryString = "?ids[]={$roles[0]->getId()}&ids[]={$roles[1]->getId()}";
        $this->client->request('PUT', "/api/facet/{$facet->getId()}/roles{$queryString}");
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals(count($data['roles']), 2);
    }

    public function testCreateFacetPanelAction()
    {
        $facet = $this->persister->facet('facet', true, true);
        $manager = $this->createManager();

        $fields = array(
            'name' => 'panel',
            'is_default_collapsed' => false,
        );

        $form = array('panel' => $fields);
        $this->login($manager);
        $this->client->request('POST', "/api/facet/{$facet->getId()}/panel/create", $form);
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data['name'], 'panel');
        $this->client->request('GET', '/api/facets.json');
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals(count($data[0]['panels']), 1);
    }

    public function testEditFacetPanelAction()
    {
        $facet = $this->persister->facet('facet', true, true);
        $panel = $this->persister->panelFacet($facet, 'panel', false);
        $manager = $this->createManager();

        $fields = array(
            'name' => 'panel_new',
            'is_default_collapsed' => false,
        );

        $form = array('panel' => $fields);
        $this->login($manager);
        $this->client->request('PUT', "/api/facet/panel/{$panel->getId()}", $form);
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data['name'], 'panel_new');
    }

    public function testDeletePanelFacetAction()
    {
        $manager = $this->createManager();
        $facet = $this->persister->facet('facet', true, true);
        $panel = $this->persister->panelFacet($facet, 'panel', false);
        $this->login($manager);
        $this->client->request('DELETE', "/api/facet/panel/{$panel->getId()}");
        $this->client->request('GET', '/api/facets.json');
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals(count($data[0]['panels']), 0);
    }

    public function testCreateFieldFacetAction()
    {
        $manager = $this->createManager();
        $facet = $this->persister->facet('facet', true, true);
        $panel = $this->persister->panelFacet($facet, 'panel', false);
        $this->login($manager);
        $form['field'] = array(
            'name' => 'text',
            'type' => 'text',
        );
        $this->client->request('POST', "/api/facet/panel/{$panel->getId()}/field/create", $form);
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data['name'], 'text');
        $this->client->request('GET', '/api/facets.json');
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data[0]['panels'][0]['fields'][0]['name'], 'text');
    }

    public function testEditFieldFacetAction()
    {
        $manager = $this->createManager();
        $facet = $this->persister->facet('facet', true, true);
        $panel = $this->persister->panelFacet($facet, 'panel', false);
        $field = $this->persister->fieldFacet($panel, 'myname', 'text');
        $this->login($manager);
        $form['field'] = array(
            'name' => 'new_text',
            'type' => 'text',
        );
        $this->client->request('PUT', "/api/facet/panel/field/{$field->getId()}", $form);
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data['name'], 'new_text');
        $this->client->request('GET', '/api/facets.json');
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data[0]['panels'][0]['fields'][0]['name'], 'new_text');
    }

    public function testDeleteFieldFacetAction()
    {
        $manager = $this->createManager();
        $facet = $this->persister->facet('facet', true, true);
        $panel = $this->persister->panelFacet($facet, 'panel', false);
        $field = $this->persister->fieldFacet($panel, 'myname', 'text');
        $this->login($manager);
    }

    public function testCreateFieldOptionsAction()
    {
        $manager = $this->createManager();
        $facet = $this->persister->facet('facet', true, true);
        $panel = $this->persister->panelFacet($facet, 'panel', false);
        $field = $this->persister->fieldFacet($panel, 'myname', 'text');
        $form['choice'] = array('label' => 'choice');
        $this->login($manager);
        $this->client->request('POST', "/api/facet/panel/field/{$field->getId()}/choice", $form);
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data['label'], 'choice');
        $this->client->request('GET', '/api/facets.json');
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data[0]['panels'][0]['fields'][0]['field_facet_choices'][0]['label'], 'choice');
    }

    public function testDeleteFieldOptionsAction()
    {
        $manager = $this->createManager();
        $facet = $this->persister->facet('facet', true, true);
        $panel = $this->persister->panelFacet($facet, 'panel', false);
        $field = $this->persister->fieldFacet($panel, 'myname', 'text', array('choice'));
        $this->login($manager);
        $choices = $field->getFieldFacetChoices();
        $this->client->request('DELETE', "/api/facet/field/choice/{$choices[0]->getId()}");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/api/facets.json');
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, true);
        $this->assertEquals($data[0]['panels'][0]['fields'][0]['field_facet_choices'], []);
    }

    public function testGetProfilePreferencesAction()
    {
    }

    public function testPutProfilePreferencesAction()
    {
    }

    public function testOrderPanelsAction()
    {
    }

    public function testOrderFieldsAction()
    {
    }

    private function createManager()
    {
        $manager = $this->persister->user('manager');
        $this->persister->grantAdminToolAccess($manager, 'user_management');

        return $manager;
    }
}
