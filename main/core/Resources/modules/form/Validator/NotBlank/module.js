import 'angular/angular.min'

import translation from 'angular-ui-translation/angular-translation'
import NotBlankDirective from './NotBlankDirective'
import HelpBlockDirective from '../../HelpBlock/HelpBlockDirective'

angular.module('ValidatorNotBlank', ['ui.translation'])
  .directive('notBlank', () => new NotBlankDirective())
  .directive('helpBlock', () => new HelpBlockDirective())
