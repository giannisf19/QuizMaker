/// <reference path="../../typings/knockout/knockout.d.ts"/>
/// <reference path="../../typings/knockout.validation/knockout.validation.d.ts"/>
/// <reference path="../../typings/jquery/jquery.d.ts"/>


class RegistrationModule {



    email : KnockoutObservable<string> ;
    userName : KnockoutObservable<string> ;
    registrationNumber : KnockoutObservable<Number> ;


    name : KnockoutObservable<string> = ko.observable<string>().extend({  required: true});
    lastName : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    department : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    semester : KnockoutObservable<Number> = ko.observable<Number>().extend({ required: true, number: true});
    password : KnockoutObservable<string> = ko.observable<string>().extend({ required: true});
    validatePassword : KnockoutObservable<string> = ko.observable<string>().extend({ equal: this.password, required: true});


    host : KnockoutObservable<string>  = ko.observable<string>();

    constructor(host, port) {

        RegistrationModule.Init();

        this.email = ko.observable<string>().extend({ validateInput: 'Email',  email: true, required: true});
        this.registrationNumber  = ko.observable<Number>().extend({validateInput: 'RegistryNumber',  required: true, number: true});
        this.userName = ko.observable<string>().extend({validateInput: 'RegistryNumber',  required: true});



        if (port != 80) {
            this.host(host + ':' + port);
        }

        this.host(host);



    }







    static Init() {


        ko.validation.rules['validateInput'] = {
            async: true,
            validator: (val, type, callback) => {

                $.ajax({
                    method: 'post',
                    url:  location.pathname + 'check' + type,
                    data: { email: val },
                    success: function(result) {
                        if (result == 1) {
                            callback(true);
                        } else {
                            callback(false);
                        }

                    }
                });
            },
            message: 'Χρησιμοποιείται ήδη.'
        };

        ko.validation.registerExtenders();
    }
}
