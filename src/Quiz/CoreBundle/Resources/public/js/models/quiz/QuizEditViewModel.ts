/// <reference path="../_typings.ts"/>


 class QuizEditViewModel {



     public Quiz : Quiz;
     public saveQuizUrl : string;
     public MyQuestions : KnockoutObservableArray<any>;
     public isQuestionsLoading : KnockoutObservable<boolean>;
     public isLoading : KnockoutObservable<boolean>;
     public getQuestionsUrl : string;
     public isMediaElementsLoading : KnockoutObservable<boolean>;
     public totalQuestionsGrade : KnockoutObservable<number>;


     public url_media_element : KnockoutObservable<string>;
     public MyMediaElements : KnockoutObservableArray<any>;
     public upload_media_element : KnockoutObservable<MediaElement>;
     public current_question_id : KnockoutObservable<number>;
     public getMediaElementsURL : KnockoutObservable<string>;


    constructor (quiz : any, url : string, getQuestionsUrl : string, url2 : string) {


        this.totalQuestionsGrade = ko.observable(0);

        if (quiz.name) {
            this.Quiz = ko.mapping.fromJS(quiz);

            this.Quiz.name = ko.observable(this.Quiz.name()).extend({ required: true});
            this.Quiz.time = ko.observable(this.Quiz.time()).extend({number: true, required: true});
            this.Quiz.total_grade = ko.observable(this.Quiz.total_grade()).extend({number: true, required: true});
            this.Quiz.pass_grade = ko.observable(this.Quiz.pass_grade()).extend({number: true, required: true, equalOrLess: this.Quiz.total_grade});


            _.map(this.Quiz.questions(), (curr : Question)=>{
               curr.edit = ko.observable(false);
            });
        } else {
            this.Quiz = new Quiz();


            this.Quiz.name = ko.observable('').extend({number: false, required: true});
            this.Quiz.time = ko.observable(60).extend({number: true, required: true});
            this.Quiz.total_grade = ko.observable(0).extend({number: true, required: true});
            this.Quiz.pass_grade = ko.observable(0).extend({number: true, required: true, equalOrLess: this.Quiz.total_grade});


        }

        this.saveQuizUrl =  url;
        this.isLoading = ko.observable(false);
        this.isQuestionsLoading = ko.observable(false);
        this.MyQuestions = ko.observableArray([]);
        this.getQuestionsUrl = getQuestionsUrl;
        this.isMediaElementsLoading = ko.observable(false);
        this.upload_media_element = ko.observable(new MediaElement());
        this.url_media_element = ko.observable('');
        this.current_question_id = ko.observable(0);
        this.getMediaElementsURL = ko.observable(url2);
        this.MyMediaElements = ko.observableArray(null);





        var total = 0;

        _.forEach(this.Quiz.questions(), (c : Question) => {
           total += parseInt(c.correct_answer_grade() + '');

        });
        this.totalQuestionsGrade(total);



        if (! this.Quiz.show_questions_randomly()) {
            this.Quiz.questions.sort( (a , b) => {
               return a.order() == b.order() ? 0 :
                   a.order() < b.order() ? -1 : 1;
            });
        }






      this.Quiz.questions.subscribe(()=>{

          _.forEach(this.Quiz.questions(), (curr: Question, index :number) =>{

              // init answer id's

              var oldId = curr.id();
              var oldAnswers = curr.answers();

              curr.edit.subscribe((val : boolean) => {
                  if (val) {
                      curr.id(0);

                      _.forEach(curr.answers(), (answer: Answer) => {
                          answer.id(0);
                      });

                  } else {
                      curr.id(oldId);
                      curr.answers(oldAnswers);
                  }
              });


              curr.correct_answer_grade.subscribe((val : number) => {


                  var total = 0;

                  _.forEach(this.Quiz.questions(), (c : Question) =>{
                     total += parseInt(c.correct_answer_grade() + '');
                  });
                  this.totalQuestionsGrade(total);

              });

          });
      });


        // fire the subscriptions manually
        this.Quiz.questions.valueHasMutated();
    }

     remove(questionIndex : any, answerIndex : any) {
         // remove the item
         this.Quiz.questions()[questionIndex].answers.splice(answerIndex, 1);
     }


     isQuestionDisabledForEditing(id : number) {

         var question = _.find(this.Quiz.questions(), (curr : Question) => {
            return curr.id() == id;
         });

        if (question && question.selected) {
            return question.id() != 0;
        }

         return false;
     }

     removeQuestion(questionIndex : any) {

         var self = this;
         swal({
             title: "Σίγουρα?",
             text: "Θα διαγραφεί η ερώτηση!",
             type: "warning",   showCancelButton: true,
             confirmButtonColor: "#DD6B55",
             confirmButtonText: "Ναι!",
             cancelButtonText: "Όχι!",
             closeOnConfirm: true },
             function(){
                 // remove the question
                 self.Quiz.questions.splice(questionIndex,1);
             });

     }



     addNewAnswer(questionIndex : any) {
         var tp = this.Quiz.questions()[questionIndex].type();

         switch (tp) {
             case 'multiple':
                 this.Quiz.questions()[questionIndex].answers.push(new Answer());
                 break;
         }


     }

     addNewQuestion() {
         this.Quiz.questions.unshift(new Question());
     }






     addSelectedQuestions() {

         var nonIncluded = _.filter(this.MyQuestions(), (current : Question) =>{
                return (current.selected()  == true) &&
                    _.filter(this.Quiz.questions(), (item : Question)=>{ return item.id() == current.id() }).length ==0;
         });

         _.forEach(nonIncluded, (toAdd : Question)=>{
             this.Quiz.questions.unshift(toAdd);
         });
     }


     getMyQuestions() {

         this.MyQuestions([]);

         $('#insert-question-modal').modal('show');
         this.isQuestionsLoading(true);
         var self = this;


         $.ajax(this.getQuestionsUrl, {
             type: 'post',
             contentType: 'application/json; charset=utf-8',
             success: (response : any) => {
                 var arr = JSON.parse(response);


                 _.forEach(arr, (item : any)=>{
                     var toAdd = new Question();

                     toAdd.id(item.id);
                     toAdd.order(item.order);
                     toAdd.type(item.type);
                     toAdd.question_text(item.question_text);

                     _.forEach(item.answers, (answer : any)=>{
                        var ans = new Answer();
                         ans.answer_text( answer.answer_text);
                         ans.id( answer.id);
                         ans.is_correct(answer.is_correct);
                         ans.left_or_right( answer.left_or_right);

                         toAdd.answers.push(ans);
                     });

                     toAdd.selected(false);

                     this.MyQuestions.push(toAdd);
                 });

                 self.isQuestionsLoading(false);
             }
         });

     }

     canSave() {


         var toReturn = false;
         toReturn = this.Quiz.name.isValid() && this.Quiz.pass_grade.isValid() && this.Quiz.time.isValid() && this.Quiz.total_grade.isValid();




         if (this.Quiz.questions().length == 0 && !this.Quiz.is_disabled()) {
             toReturn= false;
         } else if (this.Quiz.questions().length > 0)  {

             _.forEach(this.Quiz.questions(), (current : Question) => {
                 if (current.answers().length == 0) {
                     toReturn = false;
                 }
             });
             return toReturn;

         }

         return toReturn;
     }


     saveQuiz() {


         if (this.canSave()) {
             this.isLoading(true);

             $.ajax(this.saveQuizUrl,  {
                 'type' : 'post',
                 data: ko.toJSON(this.Quiz),
                 contentType: 'application/json; charset=utf-8',
                 success: (response,status) => {
                     if (status == 'success') {
                         toastr.success("Αποθήκευση επιτυχής!");
                         if (this.Quiz.id() == 0) {
                             this.Quiz.id(response);
                         }

                         this.isLoading(false);
                     }
                     console.log(response);
                 }
             });
         }

     }



     removeMediaElement(questionIndex, elementIndex) {
         console.log(questionIndex, elementIndex);
         this.Quiz.questions()[questionIndex].media_elements.splice(elementIndex, 1);
     }

     insertNewMediaElement() {

         var activeTabIndex = 0;
          $('#media-insert-tabs').children().each((index,item)=>{
              if ($(item).hasClass('active')) {
                  activeTabIndex = index;
              }
          });



         switch (activeTabIndex) {
             case 0:
                 if (this.upload_media_element().dataURL().length > 0) {

                     var toAdd = new MediaElement();
                     toAdd.src(this.upload_media_element().dataURL());
                     toAdd.media_type('file');
                     this.Quiz.questions()[this.current_question_id()].media_elements.push(toAdd);
                     this.upload_media_element(new MediaElement());
                 }
                 break;

             case 1:
                 if (this.url_media_element().length > 0) {
                    var toAdd = new MediaElement();
                     toAdd.media_type('url');
                     toAdd.src(this.url_media_element());
                     this.Quiz.questions()[this.current_question_id()].media_elements.push(toAdd);
                     this.url_media_element('');
                 }

                 break;

             case 2:
                 var q = this.Quiz.questions()[this.current_question_id()];

                 var nonIncluded = _.filter(this.MyMediaElements(), (c : MediaElement) => {
                     return c.selected() && _.filter(q.media_elements(), (a : MediaElement) => {
                         return a.id() == c.id() }).length == 0;
                 });

                 _.forEach(nonIncluded, (item : MediaElement)=>{
                    q.media_elements.push(item);
                 });

                 break;
         }
     }


     getMediaElements() {


         this.MyMediaElements([]);
         this.isMediaElementsLoading(true);

         $.ajax(this.getMediaElementsURL(), {
             type: 'post',
             success: (response) => {
                 this.isMediaElementsLoading(false);
                 var items = JSON.parse(response);

                 _.forEach(items, (item : any)=>{
                     var toAdd = new MediaElement();
                     toAdd.id(item.id);
                     toAdd.src(item.src);
                     toAdd.media_type(item.media_type);
                    toAdd.selected(false);

                     this.MyMediaElements.push(toAdd);
                 });


             }
         });

     }

}