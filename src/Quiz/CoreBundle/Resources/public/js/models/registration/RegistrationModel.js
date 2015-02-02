/// <reference path="../../typings/knockout/knockout.d.ts"/>
/// <reference path="../../typings/knockout.validation/knockout.validation.d.ts"/>
/// <reference path="../../typings/jquery/jquery.d.ts"/>
var RegistrationModule = (function () {
    function RegistrationModule(host, port) {
        this.name = ko.observable().extend({ required: true });
        this.lastName = ko.observable().extend({ required: true });
        this.department = ko.observable().extend({ required: true });
        this.semester = ko.observable().extend({ required: true, number: true });
        this.password = ko.observable().extend({ required: true });
        this.validatePassword = ko.observable().extend({ equal: this.password, required: true });
        this.host = ko.observable();
        RegistrationModule.Init();
        this.email = ko.observable().extend({ validateInput: 'Email', email: true, required: true });
        this.registrationNumber = ko.observable().extend({ validateInput: 'RegistryNumber', required: true, number: true });
        this.userName = ko.observable().extend({ validateInput: 'RegistryNumber', required: true });
        if (port != 80) {
            this.host(host + ':' + port);
        }
        this.host(host);
    }
    RegistrationModule.Init = function () {
        ko.validation.rules['validateInput'] = {
            async: true,
            validator: function (val, type, callback) {
                $.ajax({
                    method: 'post',
                    data: { email: val },
                    success: function (result) {
                        if (result == 1) {
                            callback(true);
                        }
                        else {
                            callback(false);
                        }
                    }
                });
            },
            message: 'Χρησιμοποιείται ήδη.'
        };
        ko.validation.registerExtenders();
    };
    return RegistrationModule;
})();
//# sourceMappingURL=RegistrationModel.js.map