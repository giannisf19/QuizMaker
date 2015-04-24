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

