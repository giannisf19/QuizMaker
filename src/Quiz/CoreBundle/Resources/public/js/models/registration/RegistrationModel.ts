/// <reference path="../../typings/knockout/knockout.d.ts"/>
/// <reference path="../../typings/jquery/jquery.d.ts"/>


class RegistrationModule {

    email : KnockoutObservable<string> = ko.observable<string>().extend({email: true, required: true});
    name : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    lastName : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    department : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    registrationNumber : KnockoutObservable<Number> = ko.observable<Number>().extend({ required: true, number: true});
    semester : KnockoutObservable<Number> = ko.observable<Number>().extend({ required: true, number: true});
    userName : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    password : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    validatePassword : KnockoutObservable<string> = ko.observable<string>().extend({ equal: this.password, required: true});


    host : KnockoutObservable<string>  = ko.observable<string>();

    constructor(host, port) {
        this.host(host + ':' + port);
    }


    checkEmail() {

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
    }
}
