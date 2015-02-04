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
        this.Init();
        this.email = ko.observable().extend({ validateInput: 'Email', email: true, required: true });
        this.registrationNumber = ko.observable().extend({ validateInput: 'RegistryNumber', required: true, number: true });
        this.userName = ko.observable().extend({ validateInput: 'username', required: true });
        if (port != 80) {
            this.host(host + ':' + port);
        }
        this.host(host);
    }
    RegistrationModule.prototype.Init = function () {
        ko.validation.rules['validateInput'] = {
            async: true,
            validator: function (val, type, callback) {
                var url = location.href;
                switch (type) {
                    case 'Email':
                        url += 'checkEmail';
                        break;
                    case 'username':
                        url += 'checkUsername';
                        break;
                    case 'RegistryNumber':
                        url += 'checkRegistryNumber';
                        break;
                }
                if (!val) {
                    callback(false);
                    return;
                }
                $.ajax({
                    url: url,
                    method: 'post',
                    data: { email: val },
                    success: function (result) {
                        console.log(result);
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