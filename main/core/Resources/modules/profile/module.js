import 'angular/angular.min'

import bootstrap from 'angular-bootstrap'
import translation from 'angular-ui-translation/angular-translation'

import Interceptors from '../interceptorsDefault'
import FacetManagementDirective from './Directive/FacetManagementDirective'
import ModalController from './Controller/ModalController'
import FieldModalController from './Controller/FieldModalController'
import FacetRolesController from './Controller/FacetRolesController'
import FieldRolesController from './Controller/FieldRolesController'
import '../form/module'
import '../services/module'

angular.module('UserProfile', [
  'ui.bootstrap',
  'ui.translation',
  'ClarolineAPI'
])
  .directive('facetManagement', () => new FacetManagementDirective)
  .config(Interceptors)
