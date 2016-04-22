import 'angular/angular.min'

import bootstrap from 'angular-bootstrap'
import translation from 'angular-ui-translation/angular-translation'

import Interceptors from '../interceptorsDefault'
import FacetManagementDirective from './Directive/FacetManagementDirective'
import ModalController from './Controller/ModalController'
import FormBuilder from '../form/module'
import ClarolineAPI from '../services/module'

angular.module('FacetManager', [
  'ui.bootstrap',
  'ui.translation',
  'FormBuilder',
  'ClarolineAPI'
])
  .directive('facetManagement', () => new FacetManagementDirective)
  .controller('ModalController', ModalController)
  .config(Interceptors)
