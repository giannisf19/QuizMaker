/// <reference path="../../typings/knockout/knockout.d.ts"/>
/// <reference path="../../typings/knockout.validation/knockout.validation.d.ts"/>
/// <reference path="../../typings/jquery/jquery.d.ts"/>


class QuizQuestion {

    question : string;
    answers : string[];

    constructor() {

    }
}





class Quiz {

    name : string;
    questions : KnockoutObservableArray<QuizQuestion> = ko.observableArray<QuizQuestion>([]);

    constructor() {


    }
}