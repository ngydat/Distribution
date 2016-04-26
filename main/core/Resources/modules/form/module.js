import 'angular/angular.min'
import translation from 'angular-ui-translation/angular-translation'

import './Field/Checkbox/module'
import './Field/Checkboxes/module'
import './Field/Select/module'
import './Field/Text/module'

import './Validator/NotBlank/module'

import FormDirective from './Form/Form/FormDirective'
import FormBuilderService from './Form/Form/FormBuilderService'
import FieldDirective from './Form/Field/FieldDirective'

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
