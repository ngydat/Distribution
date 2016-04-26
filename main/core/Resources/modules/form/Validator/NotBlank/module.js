import 'angular/angular.min'

import translation from 'angular-ui-translation/angular-translation'
import NotBlankDirective from './NotBlankDirective'

angular.module('ValidatorNotBlank', ['ui.translation'])
  .directive('notBlank', () => new NotBlankDirective())
