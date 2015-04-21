/// <reference path="../../typings/knockout/knockout.d.ts"/>
/// <reference path="../../typings/knockout.validation/knockout.validation.d.ts"/>
/// <reference path="../../typings/jquery/jquery.d.ts"/>
/// <reference path="../../typings/lodash/lodash.d.ts"/>
/// <reference path="../../typings/sweetalert/sweetalert.d.ts"/>
/// <reference path="../../typings/bootstrap/bootstrap.d.ts"/>
/// <reference path="../../typings/knockout.mapping/knockout.mapping.d.ts"/>
/// <reference path="Quiz.ts"/>



class QuizViewModel {


    public host : KnockoutObservable<string>;
    private quizId : KnockoutObservable<number>;
    private isDebug : boolean = true;
    private isTestStarted  : KnockoutObservable<boolean>;
    public Answers : Array<any>;
    public isLoading : KnockoutObservable<boolean>;
    public Questions : KnockoutObservableArray<any>;

    public quizInfo : any;

       constructor(quizInfo : any) {

        this.Answers = [];
        this.quizInfo = ko.mapping.fromJS(JSON.parse(quizInfo));
        this.isLoading = ko.observable(false);




           $.each(this.quizInfo.questions(), (index, item) => {
               this.Answers.push(new UserAnswer(item.id(), item.type(), []))
           });


           $('.answer').on('change', (event) => {
              var info = $(event.target).attr('name');
               var questionId = info.split(':')[0];
               var answerId = info.split(':')[1];
               var answerCheked = $(event.target).prop('checked');

               var questionIndex = _.findIndex(this.Answers, (item : UserAnswer) => {
                    return item.questionId == questionId;
               });


               var answerIndex  = _.findIndex(this.Answers[questionIndex].answer, (item ) => {
                    return item == answerId;
               });


               var thisAnswer  =  this.Answers[questionIndex];
               switch (thisAnswer.type) {
                   case 'multiple':
                       console.log(answerCheked);
                       if (answerCheked) {
                           this.Answers[questionIndex].answer.push(answerId);
                       } else {
                           this.Answers[questionIndex].answer.splice(answerIndex, 1);
                       }
                       break;

                   case 'match':
                       break;

                   case 'truefalse':
                       break;

                   case 'essay':
                       break;

                   case 'radio':
                       break;


               }

           });


           $('body').keydown((which : JQueryKeyEventObject) => {

               var $mycarousel =  $('#questions_carousel');
               if (which.which == 39) {
                   // next
                  $mycarousel.carousel('next');

               } else if (which.which == 37) {
                   // prev
                   $mycarousel.carousel('prev');

               }
           });


           $('#questions_carousel').on('slid.bs.carousel', () => {
               var index = $('#questions_carousel').find('.active').index('#questions_carousel .item');
               var $indicator = $('.question-list-item');
                    $indicator.each((index, item) => { $(item).removeClass('question-active'); });
               $indicator.eq(index).addClass('question-active')


           })

    }



    submitQuiz() {

        var final = location.href.replace('/start', '/submit');
        var data  = ko.toJSON({data : this.Answers});


        this.isLoading(true);
        $.ajax(final,{
            data: data,
            type : 'post',
            contentType: 'application/json',
            success: (response) => {
                console.log(response);
                this.isLoading(false);
            }
        } );


    }

}