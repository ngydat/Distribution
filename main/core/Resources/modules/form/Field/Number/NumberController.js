export default class NumberController {
  constructor () {
      this.name = this.field[0]
      this.options = this.field[2] || {}
      this.label = this.options.label !== undefined ? this.options.label: this.name
      this.domainTranslation = this.field.domain_translation | 'platform'
  }
}
