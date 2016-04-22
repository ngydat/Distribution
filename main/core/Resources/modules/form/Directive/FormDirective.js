import FormController from '../Controller/FormController'

export default class FacetManagementDirective {
  constructor () {
    this.scope = {}
    this.restrict = 'E'
    this.template = require('../Partial/form.html')
    this.replace = true,
    this.controller = FormController,
    this.controllerAs = 'fc'
    this.bindToController = {
        form: '='
    };
  }
}
