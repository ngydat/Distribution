export default class TextController {
  constructor () {
      this.name = this.field[0]
      this.options = this.field[2] || {}
      console.log(this.options.label)
      this.label = this.options.label !== undefined ? this.options.label: this.name
      this.domainTranslation = this.field.domain_translation | 'platform'
  }
}
