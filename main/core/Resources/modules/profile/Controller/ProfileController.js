export default class ProfileController {
  constructor ($http, FormBuilderService) {
    this.$http = $http
    this.FormBuilderService = FormBuilderService
    this.user = []
    this.arLinks = []
    this.facets = []
    this.userId = window['userId']
    this.disabled = window['disabled']

    $http.get(Routing.generate('api_get_connected_user')).then(d => this.user = d.data)
    $http.get(Routing.generate('api_get_profile_links', {user: this.userId})).then(d => this.arLinks = d.data)
    $http.get(Routing.generate('api_get_profile_facets', {user: this.userId})).then(d => this.facets = d.data)
    this.fieldTypes = ['text', 'number', 'date', 'radio', 'select', 'checkboxes', 'country']
  }

  onSubmit (facet) {
    const fields = []

    facet.panels.forEach(panel => {
      panel.fields.forEach(field => {
        fields.push(field)
      })
    })

    var data = this.FormBuilderService.formSerialize('fields', fields)

    this.$http.put(
      Routing.generate('api_put_profile_fields', {user: this.userId}),
      data,
      {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
    ).then(
      d => {
      },
      d => alert('an error occured')
    )
  }
}