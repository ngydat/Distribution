export default class NotBlankDirective {
  constructor () {
    this.require = 'ngModel'
  }

  link (scope, elm, attrs, ctrl) {
    ctrl.$validators.isNotBlank = modelValue => !ctrl.$isEmpty(modelValue)
  }
}
