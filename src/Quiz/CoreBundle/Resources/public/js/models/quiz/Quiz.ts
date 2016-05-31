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
    public feedback : KnockoutObservable<string>;

    constructor( ) {
        this.answer_text = ko.observable('');
        this.is_correct = ko.observable(false);
        this.left_or_right = ko.observable('');
        this.id = ko.observable(0);
        this.feedback = ko.observable('');
    }
}

class MediaElement {
    public media_type : KnockoutObservable<string>;
    public src : KnockoutObservable<string>;
    public selected : KnockoutObservable<boolean>;
    public dataURL :  KnockoutObservable<any>;
    public id : KnockoutObservable<number>;

    constructor() {
        this.media_type = ko.observable('');
        this.src = ko.observable('');
        this.selected = ko.observable(false);
        this.dataURL = ko.observable('');
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
    public media_elements : KnockoutObservableArray<MediaElement>;
    public correct_answer_grade : KnockoutObservable<number>;
    public wrong_answer_grade : KnockoutObservable<number>;


    public selected : KnockoutObservable<boolean>;
    public edit : KnockoutObservable<boolean>;

    constructor() {
        this.id = ko.observable(0);
        this.question_text  = ko.observable('').extend({required: true});
        this.order = ko.observable(0);
        this.type = ko.observable('multiple');
        this.answers = ko.observableArray([]);
        this.media_elements = ko.observableArray([]);
        this.correct_answer_grade = ko.observable(0).extend({number: true, required: true});
        this.wrong_answer_grade = ko.observable(0).extend({number: true, required: true});


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
    public total_grade : KnockoutObservable<number>;
    public pass_grade : KnockoutObservable<number>;
    public show_questions_randomly : KnockoutObservable<boolean>;
    public has_negative_grade : KnockoutObservable<boolean>;

    constructor() {
        this.id = ko.observable(0);
        this.name  = ko.observable('');
        this.is_disabled = ko.observable(true);
        this.is_private = ko.observable(true);
        this.time = ko.observable(60);
        this.questions = ko.observableArray([]);
        this.total_grade = ko.observable(0);
        this.pass_grade = ko.observable(0);
        this.show_questions_randomly = ko.observable(true);
        this.has_negative_grade = ko.observable(false);
    }


}

