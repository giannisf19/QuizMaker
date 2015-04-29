/// <reference path="../_typings.ts"/>


 class QuizEditViewModel {



     public Quiz : Quiz;
     public saveQuizUrl : string;
     public MyQuestions : KnockoutObservableArray<any>;
     public isQuestionsLoading : KnockoutObservable<boolean>;
     public isLoading : KnockoutObservable<boolean>;
     public getQuestionsUrl : string;

    constructor (quiz : any, url : string, getQuestionsUrl : string) {

        if (quiz.name) {
            this.Quiz = ko.mapping.fromJS(quiz);
            _.map(this.Quiz.questions(), (curr : Question)=>{
               curr.edit = ko.observable(false);
            });
        } else {
            this.Quiz = new Quiz();
        }

        this.saveQuizUrl =  url;
        this.isLoading = ko.observable(false);
        this.isQuestionsLoading = ko.observable(false);
        this.MyQuestions = ko.observableArray([]);
        this.getQuestionsUrl = getQuestionsUrl;


      this.Quiz.questions.subscribe(()=>{
          _.forEach(this.Quiz.questions(), (curr: Question, index :number) =>{

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

          });
      });



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

         if (this.Quiz.is_disabled()) {
             return true;
         }

         if (this.Quiz.questions().length == 0 && !this.Quiz.is_disabled()) {
             return false;
         } else if (this.Quiz.questions().length > 0)  {

             var toReturn = true;

             _.forEach(this.Quiz.questions(), (current : Question) => {
                 console.log('Evaluating ..');
                 if (current.answers().length == 0) {
                     toReturn = false;
                 }
             });
             return toReturn;


         } else {

             return true;
         }
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
                 }
             });
         }

     }

}