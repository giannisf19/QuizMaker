/// <reference path="../_typings.ts"/>


 class QuizEditViewModel {



     public Quiz : any;
     public saveQuizUrl : string;
     public isLoading : KnockoutObservable<boolean>;


    constructor (quiz : any, url : string) {
        this.Quiz = ko.mapping.fromJS(quiz);
        this.saveQuizUrl =  url;
        this.isLoading = ko.observable(false);
    }

     remove(questionIndex : any, answerIndex : any) {

         // remove the item
         this.Quiz.questions()[questionIndex].answers.splice(answerIndex, 1);
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
             closeOnConfirm: false },
             function(){
                 // remove the question
                 self.Quiz.questions.splice(questionIndex,1);
                 swal("Επιτυχία!", "Η ερώτηση διεγράφη.", "success");
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

     canSave() {

         if (this.Quiz.questions().length > 0) {

             return true;
         } else {
             sweetAlert('Το Quiz πρέπει να έχει ερωτήσεις.');
             return false;
         }
     }


     saveQuiz() {

         if (this.canSave()) {
             var data = {data: this.Quiz};
             this.isLoading(true);

             $.ajax(this.saveQuizUrl, {
                 'type' : 'post',
                 data: ko.toJSON(data),
                 contentType: 'application/json',
                     success: (response,status) => {
                     if (status == 'success') {
                         console.log(response);
                         toastr.success("Αποθήκευση επιτυχής!");
                         this.isLoading(false);
                     }
                 }
             });
         }

     }

}