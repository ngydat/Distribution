import FieldController from '../FieldController'

export default class DateController extends FieldController {
    constructor() {
        super()
        this.options = {}
        this.parseNgModel()
        this.opened = false
    }

    getDateFormat() {
        return 'dd.MM.yyyy'
    }

    parseNgModel() {
        if (!this.ngModel) {
            this.ngModel = new Date()
        } else {
            //parse to date
        }
    }

    open() {
        this.opened = true;
    }
}
