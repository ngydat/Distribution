export default class FieldController {
  constructor () {
      this.name = this.field[0]
      this.options = this.field[2] || {}
      this.disabled = this.options.disabled || false
      this.readonly = this.options.disabled || false
      this.label = this.options.label !== undefined ? this.options.label: this.name
  }
}
