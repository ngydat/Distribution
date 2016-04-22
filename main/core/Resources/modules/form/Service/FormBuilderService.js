export default class FormBuilderService {
    constructor($httpParamSerializerJQLike) {
        this.$httpParamSerializerJQLike = $httpParamSerializerJQLike
    }

    //copy pasted from ClarolineAPIService as it's probably going to change and the other one should be removed someday
    formSerialize(formName, parameters) {
        var data = {};
        var serialized = angular.copy(parameters);
        data[formName] = serialized;

        return this.$httpParamSerializerJQLike(data);
    }
}
