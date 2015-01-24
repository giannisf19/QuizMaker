/// <reference path="../../typings/knockout/knockout.d.ts"/>

class RegistrationModule {

    email : KnockoutObservable<string> = ko.observable<string>().extend({email: true, required: true});
    name : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    lastName : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    department : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    registrationNumber : KnockoutObservable<Number> = ko.observable<Number>().extend({ required: true, number: true});
    semester : KnockoutObservable<Number> = ko.observable<Number>().extend({ required: true, number: true});
    userName : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    password : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    validatePassword : KnockoutObservable<string> = ko.observable<string>().extend({ equal: 1});



    constructor() {


    }
}
