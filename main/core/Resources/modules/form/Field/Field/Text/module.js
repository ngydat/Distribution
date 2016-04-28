import 'angular/angular.min'

import translation from 'angular-ui-translation/angular-translation'
import TextDirective from './TextDirective'

angular.module('FieldText', ['ui.translation'])
    .directive('formText', ['$parse', '$compile', ($parse, $compile) => new TextDirective($parse, $compile)])
