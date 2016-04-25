export default class FacetController {
    constructor($http, $uibModal, FormBuilderService, ClarolineAPIService) {
        this.$http = $http
        this.$uibModal = $uibModal
        this.FormBuilderService = FormBuilderService
        this.ClarolineAPIService = ClarolineAPIService
        this.facets = []
        this.platformRoles = []
        $http.get(Routing.generate('api_get_facets')).then(d => this.facets = d.data)
        $http.get(Routing.generate('api_get_platform_roles')).then(d => this.platformRoles = d.data)
        this.newFacet = {}
        this.newPanel = {}
        this.newField = {}

        this.formFacet = {
            name: 'form-facet',
            translation_domain: 'platform',
            fields: [
                { type: 'text', name: 'name', label: 'name'},
                { type: 'checkbox', name: 'force_creation_form', label: 'display_at_registration'}
            ]
        }

        this.formPanel = {
            name: 'panel-facet',
            translation_domain: 'platform',
            fields: [
                { type: 'text', name: 'name', label: 'name' },
                { type: 'checkbox', name: 'collapse', label: 'collapse'}
            ]
        }

        this.formField = {
            name: 'field-facet',
            translation_domain: 'platform',
            fields: [
                { type: 'text', name: 'name', label: 'name' },
                {
                    type: 'select',
                    name: 'type',
                    label: 'type',
                    options: {
                        values: [
                            //these values currently come from the Entity/Facet/FieldFacet class
                            { value: 1, label: 'text'},
                            { value: 2, label: 'number'},
                            { value: 3, label: 'date'},
                            { value: 4, label: 'radio'},
                            { value: 5, label: 'select'},
                            { value: 6, label: 'checkbox'},
                            { value: 7, label: 'country'}
                        ]
                    }
                },
                { type: 'checkbox', name: 'visible', label: 'visible'},
                { type: 'checkbox', name: 'editable', label: 'editable'}
            ]
        }
    }

    onAddFacetFormRequest() {
        this.formFacet.model = this.newFacet

        const modalInstance = this.$uibModal.open({
            template: require('../Partial/facet_form.html'),
            controller: 'ModalController',
            controllerAs: 'mc',
            resolve:{
                form: () => { return this.formFacet },
                title: () => { return 'create_facet' },
                submit: () => { return 'create' }
            }
        })

        modalInstance.result.then(result => {
            if (!result) return
            var data = this.FormBuilderService.formSerialize('facet', result)

            this.$http.post(
                Routing.generate('api_post_facet'),
                data,
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
            ).then(
                d => {
                    this.facets.push(d.data)
                },
                d => {
                    alert('error handling')
                }
            );
        })
    }

    onEditFacetFormRequest(facet) {
        //for error handling
        const backup = angular.copy(facet)
        this.formFacet.model = facet

        const modalInstance = this.$uibModal.open({
            template: require('../Partial/facet_form.html'),
            controller: 'ModalController',
            controllerAs: 'mc',
            resolve:{
                form: () => { return this.formFacet },
                title: () => { return 'edit_facet' },
                submit: () => { return 'edit' }
            }
        })

        modalInstance.result.then(result => {
            if (!result) return
            var data = this.FormBuilderService.formSerialize('facet', result)

            this.$http.put(
                Routing.generate('api_put_facet', {facet: result.id}),
                data,
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
            ).then(
                d => {},
                d => {
                    //put backup where is should be
                    alert('an error occured')
                }
            );
        })
    }

    onDeleteFacet(facet) {
        const url = Routing.generate('api_delete_facet', {facet: facet.id})

        this.ClarolineAPIService.confirm(
            {url, method: 'DELETE'},
            function() {
                alert('meh')
                this.ClarolineAPIService.removeElements(facet, this.facets)
            }.bind(this),
            Translator.trans('delete_facet', {}, 'platform'),
            Translator.trans('delete_facet_confirm', 'platform')
        )
    }

    onMoveFacetLeft(facet) {

    }

    onMoveFacetRight(facet) {

    }

    onSetFacetRoles(facet) {
        const modalInstance = this.$uibModal.open({
            template: require('../Partial/facet_roles_form.html'),
            controller: 'FacetRolesController',
            controllerAs: 'frc',
            resolve:{
                facet: () => { return facet },
                platformRoles: () => { return this.platformRoles }
            }
        })
    }

    onAddPanelFormRequest(facet)
    {
        const modalInstance = this.$uibModal.open({
            template: require('../Partial/panel_form.html'),
            controller: 'ModalController',
            controllerAs: 'mc',
            resolve:{
                form: () => { return this.formPanel }
            }
        })

        modalInstance.result.then(result => {
            if (!result) return
            var data = this.FormBuilderService.formSerialize('panel', result)

            this.$http.post(
                Routing.generate('api_post_panel_facet', {facet: facet.id}),
                data,
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
            ).then(d => console.log(d));
        })
    }

    onEditPanelFormRequest(panel)
    {
        alert('edit panel')
    }

    onDeletePanel(panel)
    {
        alert('delete panel')
    }

    onAddFieldFormRequest(panel)
    {
        const modalInstance = this.$uibModal.open({
            template: require('../Partial/field_form.html'),
            controller: 'ModalController',
            controllerAs: 'mc',
            resolve:{
                form: () => { return this.formField }
            }
        })

        modalInstance.result.then(result => {
            if (!result) return
            var data = this.FormBuilderService.formSerialize('field', result)

            this.$http.post(
                Routing.generate('api_post_field_facet', {panel: panel.id}),
                data,
                {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
            ).then(d => console.log(d));
        })
    }

    onEditFieldFormRequest(field)
    {
        alert('edit field')
    }

    onDeleteField(field)
    {
        alert('delete field')
    }
}
