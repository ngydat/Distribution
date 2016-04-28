import 'angular/angular.min'
import translation from 'angular-ui-translation/angular-translation'

import './Field/Field/Checkbox/module'
import './Field/Field/Checkboxes/module'
import './Field/Field/Select/module'
import './Field/Field/Text/module'

import './Validator/NotBlank/module'

import FormDirective from './FormDirective'
import FormBuilderService from './FormBuilderService'
import FieldDirective from './FieldDirective'

angular.module('FormBuilder', [
  'ui.translation',
  'FieldCheckbox',
  'FieldCheckboxes',
  'FieldSelect',
  'FieldText',
  'ValidatorNotBlank'
])
  .directive('formbuilder', () => new FormDirective)
  .directive('formField', () => new FieldDirective)
  .service('FormBuilderService', FormBuilderService)
