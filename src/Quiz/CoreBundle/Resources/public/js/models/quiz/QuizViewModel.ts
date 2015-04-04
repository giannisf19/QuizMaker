/// <reference path="../../typings/knockout/knockout.d.ts"/>
/// <reference path="../../typings/knockout.validation/knockout.validation.d.ts"/>
/// <reference path="../../typings/jquery/jquery.d.ts"/>
/// <reference path="../../typings/knockout.mapping/knockout.mapping.d.ts"/>


class QuizViewModel {


    private host : KnockoutObservable<string>;
    private quizId : KnockoutObservable<number>;
    private isDebug : boolean = true;
    private isTestStarted  : KnockoutObservable<boolean>;

    public quizInfo : any;


    constructor(quizInfo : any) {

        this.quizInfo = ko.mapping.fromJS(JSON.parse(quizInfo));




    }


}