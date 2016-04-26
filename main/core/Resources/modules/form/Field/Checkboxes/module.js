import 'angular/angular.min'

import translation from 'angular-ui-translation/angular-translation'
import CheckboxDirective from './CheckListDirective'

angular.module('FieldCheckboxes', ['ui.translation'])
  .directive('checklistModel', ['$parse', '$compile', ($parse, $compile) => new CheckListDirective($parse, $compile)])
