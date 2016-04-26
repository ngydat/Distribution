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

    if (field.options && field.options.validators) {
      Object.keys(field.options.validators).forEach(attr => {
        let test = angular.toJson(field.options.validators[attr])
        elem.children('input').attr(attr, test)
      })
    }

   this.$compile(elem.contents())(scope)
  }
}
