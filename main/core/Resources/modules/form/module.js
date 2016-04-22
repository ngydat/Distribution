import 'angular/angular.min'

import translation from 'angular-ui-translation/angular-translation'
import FormDirective from './Directive/FormDirective'
import FormBuilderService from './Service/FormBuilderService'

angular.module('FormBuilder', [
  'ui.translation'
])
  .directive('formbuilder', () => new FormDirective)
  .service('FormBuilderService', FormBuilderService)
