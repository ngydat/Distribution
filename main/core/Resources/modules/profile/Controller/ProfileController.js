export default class ProfileController {
  constructor ($http) {
      console.log($http)
      this.$http = $http
      this.user = []
      this.arLinks = []

      $http.get(Routing.generate('api_get_connected_user')).then(d => this.user = d.data)
      $http.get(Routing.generate('api_get_profile_links')).then(d => this.arLinks = d.data)
  }
}
