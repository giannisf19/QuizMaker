/// <reference path="../_typings.ts"/>


 class QuizEditViewModel {





     public Quiz : KnockoutObservable<any>;


    constructor (quiz : any) {
        this.Quiz = ko.mapping.fromJS(quiz);

    }
}