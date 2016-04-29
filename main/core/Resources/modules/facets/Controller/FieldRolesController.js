export default class FacetRolesController {
  constructor ($uibModalInstance, platformRoles, field, form) {
    this.$uibModalInstance = $uibModalInstance
    this.platformRoles = platformRoles
    this.field = field
    this.form = form

    //now we initialize the field_facet_roles array
    const fieldFacetRoles = field.field_facets_role

    //build the fieldFacetRoles array. This could be done on the server side.
    const missingRoles = this.platformRoles.filter(element => {
        let found = false
        fieldFacetRoles.forEach(fieldFacetRole => {
            if (element.id === fieldFacetRole.role.id) found = true
        })
        return !found
    }).forEach(element => {
        fieldFacetRoles.push({can_open: false, can_edit: false, role: element})
    })
  }

  onSubmit () {
    this.$uibModalInstance.close(this.field)
  }
}
