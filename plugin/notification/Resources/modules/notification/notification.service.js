export default class NotificationService{

  constructor($http){
    this.$http = $http
    this._types = NotificationService._getGlobal('types')
    this._parameters = {}
    this._parameters['displayEnabledTypes'] = NotificationService._getGlobal('displayEnabledTypes')
    this._parameters['phoneEnabledTypes'] = NotificationService._getGlobal('phoneEnabledTypes')
    this._parameters['mailEnabledTypes'] = NotificationService._getGlobal('mailEnabledTypes')
    this._parameters['rssEnabledTypes'] = NotificationService._getGlobal('rssEnabledTypes')
    this._types.forEach(t => {
      t['translated_group'] = window.Translator.trans(t['group'],{},'resource')
      t['translated_group'] = window.Translator.trans(t['translated_group'],{},'notification')	
    })
  }

  getTypes(){
    return this._types
  }

  getChildrenChecked(){
    return this._childrenChecked
  }

  getHttp(){
    return this.$http
  }

  getDisplayEnabledTypes(){
    return this._parameters['displayEnabledTypes']
  }

  getPhoneEnabledTypes(){
    return this._parameters['phoneEnabledTypes']
  }

  getMailEnabledTypes(){
    return this._parameters['mailEnabledTypes']
  }

  getRssEnabledTypes(){
    return this._parameters['rssEnabledTypes']
  }

  getParameters(){
    return this._parameters
  }

  saveParameters(originalParameters, newDisplay, newPhone, newMail, newRss){
    const originalDisplay = originalParameters.displayEnabledTypes
    const originalPhone = originalParameters.phoneEnabledTypes
    const originalMail = originalParameters.mailEnabledTypes
    const originalRss = originalParameters.rssEnabledTypes
    const url = window.Routing.generate('icap_notification_save_user_parameters')
    originalParameters.displayEnabledTypes = newDisplay
    originalParameters.phoneEnabledTypes = newPhone
    originalParameters.mailEnabledTypes = newMail
    originalParameters.rssEnabledTypes = newRss
    this.$http
    .put(url,{display : newDisplay, phone : newPhone, mail : newMail, rss : newRss })
    .then(()=>{
      originalParameters.displayEnabledTypes = originalDisplay
      originalParameters.phoneEnabledTypes = originalPhone
      originalParameters.mailEnabledTypes = originalMail
      originalParameters.rssEnabledTypes = originalRss
    })
  }

  createRssFeed(){
    const url = window.Routing.generate('icap_notification_regenerate_rss_url')
    this.$http
    .post(url)
  }

  static _getGlobal(name) {
    if (typeof window[name] === 'undefined') {
      throw new Error(
        `Expected ${name} to be exposed in a window.${name} variable`
      )
    }
    return window[name]
  }

}