import 'angular/angular.min'

import bootstrap from 'angular-bootstrap'
import translation from 'angular-ui-translation/angular-translation'
import asset from '../asset/module'

import Interceptors from '../interceptorsDefault'
import ProfileDirective from './Directive/ProfileDirective'
import '../form/module'
import '../services/module'

angular.module('UserProfile', [
  'ui.bootstrap',
  'ui.translation',
  'ClarolineAPI',
  'ui.asset'
])
  .directive('userProfile', () => new ProfileDirective)
  .config(Interceptors)
