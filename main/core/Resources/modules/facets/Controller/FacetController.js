export default class FacetController {
  constructor ($http, $uibModal, FormBuilderService, ClarolineAPIService) {
    this.$http = $http
    this.$uibModal = $uibModal
    this.FormBuilderService = FormBuilderService
    this.ClarolineAPIService = ClarolineAPIService
    this.facets = []
    this.platformRoles = []
    $http.get(Routing.generate('api_get_facets')).then(d => this.facets = d.data)
    $http.get(Routing.generate('api_get_platform_roles')).then(d => this.platformRoles = d.data)

    this.formFacet = {
      translation_domain: 'platform',
      fields: [
        {
            type: 'text',
            name: 'name',
            label: 'name',
            options: {
                validators: {
                    'not-blank': {'max': 8}
                }
            }
        },
        { type: 'checkbox', name: 'force_creation_form', label: 'display_at_registration'}
      ]
    }

    this.formPanel = {
      translation_domain: 'platform',
      fields: [
        { type: 'text', name: 'name', label: 'name' },
        { type: 'checkbox', name: 'is_default_collapsed', label: 'collapse'}
      ]
    }

    this.formField = {
      translation_domain: 'platform',
      fields: [
        { type: 'text', name: 'name', label: 'name' },
        {
          type: 'select',
          name: 'type',
          label: 'type',
          options: {
            values: [
              // these values currently come from the Entity/Facet/FieldFacet class
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
        { type: 'checkbox', name: 'is_visible_by_owner', label: 'visible'},
        { type: 'checkbox', name: 'is_editable_by_owner', label: 'editable'}
      ]
    }

    this.formFieldRole = {
      name: 'field-role',
      translation_domain: 'platform',
      fields: [
        { type: 'checkbox', name: 'is_visible', label: ''},
        { type: 'checkbox', name: 'is_ediable', label: ''}
      ]
    }
  }

  onAddFacetFormRequest () {
    const modalInstance = this.$uibModal.open({
      template: require('../Partial/facet_form.html'),
      controller: 'ModalController',
      controllerAs: 'mc',
      resolve: {
        form: () => {
          return this.formFacet},
        title: () => {
          return 'create_facet'},
        submit: () => {
          return 'create'},
        model: () => {
          return {}
        }
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
      )
    })
  }

  onEditFacetFormRequest (facet) {
    // for error handling

    const modalInstance = this.$uibModal.open({
      template: require('../Partial/facet_form.html'),
      controller: 'ModalController',
      controllerAs: 'mc',
      resolve: {
        form: () => {
          return this.formFacet},
        title: () => {
          return 'edit_facet'},
        submit: () => {
          return 'edit'},
        model: () => {
          return facet
        }
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
        d => {
        },
        d => alert('an error occured')
      )
    })
  }

  onDeleteFacet (facet) {
    const url = Routing.generate('api_delete_facet', {facet: facet.id})

    this.ClarolineAPIService.confirm(
      {url, method: 'DELETE'},
      function () {
        alert('meh')
        this.ClarolineAPIService.removeElements(facet, this.facets)
      }.bind(this),
      Translator.trans('delete_facet', {}, 'platform'),
      Translator.trans('delete_facet_confirm', 'platform')
    )
  }

  onMoveFacetLeft (facet) {}

  onMoveFacetRight (facet) {}

  onSetFacetRoles (facet) {
    const modalInstance = this.$uibModal.open({
      template: require('../Partial/facet_roles_form.html'),
      controller: 'FacetRolesController',
      controllerAs: 'frc',
      resolve: {
        facet: () => {
          return facet},
        platformRoles: () => {
          return this.platformRoles}
      }
    })

    modalInstance.result.then(facet => {
      const roles = facet.roles
      const qs = this.FormBuilderService.generateQueryString(roles, 'ids')
      const route = Routing.generate('api_put_facet_roles', {'facet': facet.id}) + '?' + qs

      this.$http.put(route).then(
        d => {
          alert('ok')
        },
        d => {
          alert('pas ok')
        }
      )
    })
  }

  onAddPanelFormRequest (facet) {
    const modalInstance = this.$uibModal.open({
      template: require('../Partial/panel_form.html'),
      controller: 'ModalController',
      controllerAs: 'mc',
      resolve: {
        form: () => {
          return this.formPanel},
        title: () => {
          return 'create_panel'},
        submit: () => {
          return 'create'},
        model: () => {
          return {}
        }
      }
    })

    modalInstance.result.then(result => {
      if (!result) return
      var data = this.FormBuilderService.formSerialize('panel', result)

      this.$http.post(
        Routing.generate('api_post_panel_facet', {facet: facet.id}),
        data,
        {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
      ).then(
        d => {
          if (!facet.panels) facet.panels = []
          facet.panels.push(d.data)
        },
        d => alert('error')
      )
    })
  }

  onEditPanelFormRequest (panel) {
    // for error handling
    const backup = angular.copy(panel)
    this.formPanel.model = panel

    const modalInstance = this.$uibModal.open({
      template: require('../Partial/panel_form.html'),
      controller: 'ModalController',
      controllerAs: 'mc',
      resolve: {
        form: () => {
          return this.formPanel},
        title: () => {
          return 'edit_panel'},
        submit: () => {
          return 'edit'},
        model: () => {
          return panel
        }
      }
    })

    modalInstance.result.then(result => {
      if (!result) return
      var data = this.FormBuilderService.formSerialize('panel', result)

      this.$http.put(
        Routing.generate('api_put_panel_facet', {panel: panel.id}),
        data,
        {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
      ).then(
        d => {
        },
        d => alert('an error occured')
      )
    })
  }

  onDeletePanel (panel) {
    const url = Routing.generate('api_delete_panel_facet', {panel: panel.id})

    this.ClarolineAPIService.confirm(
      {url, method: 'DELETE'},
      function () {
        alert('meh')
      // this.ClarolineAPIService.removeElements(facet, this.facets)
      }.bind(this),
      Translator.trans('delete_panel', {}, 'platform'),
      Translator.trans('delete_panel_confirm', 'platform')
    )
  }

  onAddFieldFormRequest (panel) {
    const modalInstance = this.$uibModal.open({
      template: require('../Partial/field_form.html'),
      controller: 'ModalController',
      controllerAs: 'mc',
      resolve: {
        form: () => {
          return this.formField},
        title: () => {
          return 'create_field'},
        submit: () => {
          return 'create'},
        model: () => {
          return {}
        }
      }
    })

    modalInstance.result.then(result => {
      if (!result) return
      console.log(result)
      var data = this.FormBuilderService.formSerialize('field', result)

      this.$http.post(
        Routing.generate('api_post_field_facet', {panel: panel.id}),
        data,
        {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
      ).then(
        d => {
          if (!panel.fields) panel.fields = []
          panel.fields.push(d.data)
        },
        d => alert('boom')
      )
    })
  }

  onEditFieldFormRequest (field) {
    // for error handling
    const backup = angular.copy(field)
    this.formField.model = field

    const modalInstance = this.$uibModal.open({
      template: require('../Partial/field_form.html'),
      controller: 'ModalController',
      controllerAs: 'mc',
      resolve: {
        form: () => {
          return this.formField},
        title: () => {
          return 'edit_field'},
        submit: () => {
          return 'edit'},
        model: () => {
          return field
        }
      }
    })

    modalInstance.result.then(result => {
      if (!result) return
      var data = this.FormBuilderService.formSerialize('field', result)

      this.$http.put(
        Routing.generate('api_put_field_facet', {field: field.id}),
        data,
        {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
      ).then(
        d => {
        },
        d => alert('an error occured')
      )
    })
  }

  onDeleteField (field) {
    const url = Routing.generate('api_delete_field_facet', {field: field.id})

    this.ClarolineAPIService.confirm(
      {url, method: 'DELETE'},
      function () {
        alert('meh')
      // this.ClarolineAPIService.removeElements(facet, this.facets)
      }.bind(this),
      Translator.trans('delete_field', {}, 'platform'),
      Translator.trans('delete_field_confirm', 'platform')
    )
  }

  onSetFieldRoles (field) {
    const modalInstance = this.$uibModal.open({
      template: require('../Partial/field_roles_form.html'),
      controller: 'FieldRolesController',
      controllerAs: 'firc',
      resolve: {
        field: () => {
          return field},
        platformRoles: () => {
          return this.platformRoles},
        form: () => {
          return this.formFieldRole}
      }
    })

    modalInstance.result.then(field => {
        var data = this.FormBuilderService.formSerialize('roles', field.field_facets_role)
        console.log(data)

        this.$http.put(
          Routing.generate('api_put_field_roles', {field: field.id}),
          data,
          {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
        ).then(
          d => {
            alert('it worked !')
          },
          d => {
            alert('error handling')
          }
        )
    })
  }
}