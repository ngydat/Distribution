export default class FacetRolesController {
  constructor ($uibModalInstance, platformRoles, field, form) {
    this.$uibModalInstance = $uibModalInstance
    this.platformRoles = platformRoles
    this.field = field
    this.form = form

    //now we initialize the field_facet_roles array
    const fieldFacetRoles = field.field_facets_role

    const missingRoles = this.platformRoles.filter(element => {
        let found = false
        fieldFacetRoles.forEach(fieldFacetRole => {
            if (element.id === fieldFacetRole.role.id) found = true
        })
        return !found
    }).forEach(element => {
        fieldFacetRoles.push({is_visible: false, is_editable: false, role: element})
    })
  }

  onSubmit () {
    this.$uibModalInstance.close(this.field)
  }
}
