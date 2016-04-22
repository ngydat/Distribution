export default class FacetRolesController {
    constructor($uibModalInstance) {
        this.$uibModalInstance = $uibModalInstance
    }

    onSubmit() {
        this.$uibModalInstance.close(this.form.model)
    }
}
