/// <reference path="../../typings/knockout/knockout.d.ts"/>
/// <reference path="../../typings/jquery/jquery.d.ts"/>
var RegistrationModule = (function () {
    function RegistrationModule(host, port) {
        this.email = ko.observable().extend({ email: true, required: true });
        this.name = ko.observable().extend({ required: true });
        this.lastName = ko.observable().extend({ required: true });
        this.department = ko.observable().extend({ required: true });
        this.registrationNumber = ko.observable().extend({ required: true, number: true });
        this.semester = ko.observable().extend({ required: true, number: true });
        this.userName = ko.observable().extend({ required: true });
        this.password = ko.observable().extend({ required: true });
        this.validatePassword = ko.observable().extend({ equal: this.password, required: true });
        this.host = ko.observable();
        this.host(host + ':' + port);
    }
    RegistrationModule.prototype.checkEmail = function () {
        console.log('sending to ', this.host());
        //$.ajax({
        //    method: 'post',
        //    contentType: 'application/json',
        //    url: this.host() + '/emailAvailableAction',
        //    data: {'email': this.email()},
        //    success: (result) => {
        //        console.log('success');
        //        console.log(result);
        //    },
        //
        //    error: (error) => {
        //        console.log('success');
        //        console(error);
        //    }
        //});
    };
    return RegistrationModule;
})();
