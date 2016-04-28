import HelpBlockController from './HelpBlockController'

export default class HelpBlockDirective {
  constructor () {
    this.scope = {}
    this.restrict = 'E'
    this.template = require('./help_block.html')
    this.replace = true,
    this.controller = HelpBlockController
    this.controllerAs = 'hc'
    this.bindToController = {
        formCtrl: '=',
        fieldName: '@'
    }
  }
}
