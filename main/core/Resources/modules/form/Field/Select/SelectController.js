import FieldController from '../FieldController'

export default class SelectController extends FieldController {
  constructor () {
      super()
      this._ngModel = this.ngModel
  }
}
