/// <reference path="../../typings/knockout/knockout.d.ts"/>
/// <reference path="../../typings/knockout.validation/knockout.validation.d.ts"/>
/// <reference path="../../typings/jquery/jquery.d.ts"/>
/// <reference path="../../typings/knockout.mapping/knockout.mapping.d.ts"/>
/// <reference path="Quiz.ts"/>

class QuizViewModel {


    private host : KnockoutObservable<string>;
    private quizId : KnockoutObservable<number>;
    private isDebug : boolean = true;
    private isTestStarted  : KnockoutObservable<boolean>;
    public Answers : Array<any>;

    public Questions : KnockoutObservableArray<any>;

    public quizInfo : any;

       constructor(quizInfo : any) {

        this.Answers = [];
        this.quizInfo = ko.mapping.fromJS(JSON.parse(quizInfo));



           $.each(this.quizInfo.questions(), (index, item) => {
               this.Answers.push(new UserAnswer(item.id(), item.type(), null))
           });


           $('.answer').on('change', (event) => {
              var info = $(event.target).attr('name');
               var questionId = info.split(':')[0];
               var answerId = info.split(':')[1];

           });

    }


}