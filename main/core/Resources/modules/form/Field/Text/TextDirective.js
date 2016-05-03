import TextController from './TextController'

export default class TextDirective {
  constructor ($parse, $compile) {
    this.$parse = $parse
    this.$compile = $compile
    this.scope = {}
    this.priority = 1001
    this.restrict = 'E'
    this.template = require('./text.html')
    this.replace = true
    this.controller = TextController
    this.controllerAs = 'tc'
    this.bindToController = {
      field: '=',
      ngModel: '=',
      validators: '=',
      formCtrl: '='
    }
  }

  compile (tElem, tAttrs) {
    return this.postLinkFn.bind(this)
  }

  postLinkFn (scope, elem, attrs) {
    const field = this.$parse(attrs.field)(scope.$parent)
    const options = field[2]

    if (options && options.validators) {
      Object.keys(options.validators).forEach(attr => {
        let test = angular.toJson(options.validators[attr])
        elem.children('input').attr(attr, test)
      })
    }

   this.$compile(elem.contents())(scope)
  }
}
