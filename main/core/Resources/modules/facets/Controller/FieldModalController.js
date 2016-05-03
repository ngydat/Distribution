export default class ModalController {
  constructor (form, title, submit, model, $uibModalInstance, $http) {
    this.form = form
    this.title = title
    this.submit = submit
    this.model = model
    this.$uibModalInstance = $uibModalInstance
    this.newChoice = ''
    this.$http = $http
  }

  onSubmit (form) {
    if (form.$valid) this.$uibModalInstance.close(this.model)
  }

  addChoice () {
      console.log(this.newChoice)
      this.model.field_facet_choices.push({id: Math.random(), name: this.newChoice})
  }

  removeChoice(choice) {

  }
}
