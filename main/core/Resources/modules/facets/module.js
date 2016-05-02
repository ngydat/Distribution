import 'angular/angular.min'

import bootstrap from 'angular-bootstrap'
import translation from 'angular-ui-translation/angular-translation'
import dragula from 'angular-dragula/dist/angular-dragula'

import Interceptors from '../interceptorsDefault'
import FacetManagementDirective from './Directive/FacetManagementDirective'
import ModalController from './Controller/ModalController'
import FacetRolesController from './Controller/FacetRolesController'
import FieldRolesController from './Controller/FieldRolesController'
import '../form/module'
import '../services/module'

angular.module('FacetManager', [
  'ui.bootstrap',
  'ui.translation',
  'FormBuilder',
  'ClarolineAPI',
  dragula(angular)
])
  .directive('facetManagement', () => new FacetManagementDirective)
  .controller('ModalController', ModalController)
  .controller('FacetRolesController', FacetRolesController)
  .controller('FieldRolesController', FieldRolesController)
  .config(Interceptors)
