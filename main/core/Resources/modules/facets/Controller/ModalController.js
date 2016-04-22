export default class ModalController {
    constructor(form, title, submit, $uibModalInstance) {
        this.form = form
        this.title = title
        this.submit = submit
        this.$uibModalInstance = $uibModalInstance
    }

    onSubmit() {
        this.$uibModalInstance.close(this.form.model)
    }
}
