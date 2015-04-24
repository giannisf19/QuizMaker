/// <reference path="../_typings.ts"/>
var QuizEditViewModel = (function () {
    function QuizEditViewModel(quiz, url) {
        this.Quiz = ko.mapping.fromJS(quiz);
        this.saveQuizUrl = url;
        this.isLoading = ko.observable(false);
    }
    QuizEditViewModel.prototype.remove = function (questionIndex, answerIndex) {
        // remove the item
        this.Quiz.questions()[questionIndex].answers.splice(answerIndex, 1);
    };
    QuizEditViewModel.prototype.removeQuestion = function (questionIndex) {
        var self = this;
        swal({
            title: "Σίγουρα?",
            text: "Θα διαγραφεί η ερώτηση!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ναι!",
            cancelButtonText: "Όχι!",
            closeOnConfirm: false
        }, function () {
            // remove the question
            self.Quiz.questions.splice(questionIndex, 1);
            swal("Επιτυχία!", "Η ερώτηση διεγράφη.", "success");
        });
    };
    QuizEditViewModel.prototype.addNewAnswer = function (questionIndex) {
        var tp = this.Quiz.questions()[questionIndex].type();
        switch (tp) {
            case 'multiple':
                this.Quiz.questions()[questionIndex].answers.push(new Answer());
                break;
        }
    };
    QuizEditViewModel.prototype.canSave = function () {
        if (this.Quiz.questions().length > 0) {
            return true;
        }
        else {
            sweetAlert('Το Quiz πρέπει να έχει ερωτήσεις.');
            return false;
        }
    };
    QuizEditViewModel.prototype.saveQuiz = function () {
        var _this = this;
        if (this.canSave()) {
            var data = { data: this.Quiz };
            this.isLoading(true);
            $.ajax(this.saveQuizUrl, {
                'type': 'post',
                data: ko.toJSON(data),
                contentType: 'application/json',
                success: function (response, status) {
                    if (status == 'success') {
                        console.log(response);
                        toastr.success("Αποθήκευση επιτυχής!");
                        _this.isLoading(false);
                    }
                }
            });
        }
    };
    return QuizEditViewModel;
})();
//# sourceMappingURL=QuizEditViewModel.js.map