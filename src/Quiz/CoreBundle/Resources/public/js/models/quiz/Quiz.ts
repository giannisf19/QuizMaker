/// <reference path="../_typings.ts"/>

class UserAnswer {
    constructor(public questionId : any, public type: any, public answer : any) {

    }
}



class Answer {

    public answer_text : KnockoutObservable<string>;
    public is_correct : KnockoutObservable<boolean>;
    public left_or_right : KnockoutObservable<string>;
    public id : KnockoutObservable<number>;

    constructor( ) {
        this.answer_text = ko.observable('');
        this.is_correct = ko.observable(false);
        this.left_or_right = ko.observable('');
        this.id = ko.observable(0);
    }
}


class QuizGradeResult {

    public questionId : string;
    public userAnswersId : any;
    public correctAnswerId : any;

    constructor(qid, userAnswer, correct) {
        this.questionId = qid;
        this.userAnswersId = userAnswer;
        this.correctAnswerId = correct;
    }

}


class Question {
    public id : KnockoutObservable<number>;
    public question_text : KnockoutObservable<string>;
    public order : KnockoutObservable<number>;
    public type : KnockoutObservable<string>;
    public answers : KnockoutObservableArray<Answer>;

    public selected : KnockoutObservable<boolean>;
    public edit : KnockoutObservable<boolean>;

    constructor() {
        this.id = ko.observable(0);
        this.question_text  = ko.observable('');
        this.order = ko.observable(0);
        this.type = ko.observable('multiple');
        this.answers = ko.observableArray([]);

        this.selected = ko.observable(false);
        this.edit  = ko.observable(false);
    }


}

class Quiz {
    public id : KnockoutObservable<number>;
    public name : KnockoutObservable<string>;
    public is_disabled : KnockoutObservable<boolean>;
    public is_private : KnockoutObservable<boolean>;
    public time :KnockoutObservable<number>;
    public questions : KnockoutObservableArray<Question>;



    constructor() {
        this.id = ko.observable(0);
        this.name  = ko.observable('');
        this.is_disabled = ko.observable(true);
        this.is_private = ko.observable(true);
        this.time = ko.observable(60);
        this.questions = ko.observableArray([]);
    }


}

