export default class ProfileController {
  constructor ($http) {
      this.$http = $http
      this.user = []
      this.arLinks = []
      this.facets = []

      $http.get(Routing.generate('api_get_connected_user')).then(d => this.user = d.data)
      $http.get(Routing.generate('api_get_profile_links')).then(d => this.arLinks = d.data)
      $http.get(Routing.generate('api_get_profile_private_facet')).then(d => this.facets = d.data)
      this.fieldTypes = ['text', 'number', 'date', 'radio', 'select', 'checkboxes', 'country']
  }
}
