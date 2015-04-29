/// <reference path="../_typings.ts"/>
var QuizEditViewModel = (function () {
    function QuizEditViewModel(quiz, url, getQuestionsUrl) {
        var _this = this;
        if (quiz.name) {
            this.Quiz = ko.mapping.fromJS(quiz);
            _.map(this.Quiz.questions(), function (curr) {
                curr.edit = ko.observable(false);
            });
        }
        else {
            this.Quiz = new Quiz();
        }
        this.saveQuizUrl = url;
        this.isLoading = ko.observable(false);
        this.isQuestionsLoading = ko.observable(false);
        this.MyQuestions = ko.observableArray([]);
        this.getQuestionsUrl = getQuestionsUrl;
        this.Quiz.questions.subscribe(function () {
            _.forEach(_this.Quiz.questions(), function (curr, index) {
                var oldId = curr.id();
                var oldAnswers = curr.answers();
                curr.edit.subscribe(function (val) {
                    if (val) {
                        curr.id(0);
                        _.forEach(curr.answers(), function (answer) {
                            answer.id(0);
                        });
                    }
                    else {
                        curr.id(oldId);
                        curr.answers(oldAnswers);
                    }
                });
            });
        });
    }
    QuizEditViewModel.prototype.remove = function (questionIndex, answerIndex) {
        // remove the item
        this.Quiz.questions()[questionIndex].answers.splice(answerIndex, 1);
    };
    QuizEditViewModel.prototype.isQuestionDisabledForEditing = function (id) {
        var question = _.find(this.Quiz.questions(), function (curr) {
            return curr.id() == id;
        });
        if (question && question.selected) {
            return question.id() != 0;
        }
        return false;
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
            closeOnConfirm: true
        }, function () {
            // remove the question
            self.Quiz.questions.splice(questionIndex, 1);
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
    QuizEditViewModel.prototype.addNewQuestion = function () {
        this.Quiz.questions.unshift(new Question());
    };
    QuizEditViewModel.prototype.addSelectedQuestions = function () {
        var _this = this;
        var nonIncluded = _.filter(this.MyQuestions(), function (current) {
            return (current.selected() == true) && _.filter(_this.Quiz.questions(), function (item) {
                return item.id() == current.id();
            }).length == 0;
        });
        _.forEach(nonIncluded, function (toAdd) {
            _this.Quiz.questions.unshift(toAdd);
        });
    };
    QuizEditViewModel.prototype.getMyQuestions = function () {
        var _this = this;
        this.MyQuestions([]);
        $('#insert-question-modal').modal('show');
        this.isQuestionsLoading(true);
        var self = this;
        $.ajax(this.getQuestionsUrl, {
            type: 'post',
            contentType: 'application/json; charset=utf-8',
            success: function (response) {
                var arr = JSON.parse(response);
                _.forEach(arr, function (item) {
                    var toAdd = new Question();
                    toAdd.id(item.id);
                    toAdd.order(item.order);
                    toAdd.type(item.type);
                    toAdd.question_text(item.question_text);
                    _.forEach(item.answers, function (answer) {
                        var ans = new Answer();
                        ans.answer_text(answer.answer_text);
                        ans.id(answer.id);
                        ans.is_correct(answer.is_correct);
                        ans.left_or_right(answer.left_or_right);
                        toAdd.answers.push(ans);
                    });
                    toAdd.selected(false);
                    _this.MyQuestions.push(toAdd);
                });
                self.isQuestionsLoading(false);
            }
        });
    };
    QuizEditViewModel.prototype.canSave = function () {
        if (this.Quiz.is_disabled()) {
            return true;
        }
        if (this.Quiz.questions().length == 0 && !this.Quiz.is_disabled()) {
            return false;
        }
        else if (this.Quiz.questions().length > 0) {
            var toReturn = true;
            _.forEach(this.Quiz.questions(), function (current) {
                console.log('Evaluating ..');
                if (current.answers().length == 0) {
                    toReturn = false;
                }
            });
            return toReturn;
        }
        else {
            return true;
        }
    };
    QuizEditViewModel.prototype.saveQuiz = function () {
        var _this = this;
        if (this.canSave()) {
            this.isLoading(true);
            $.ajax(this.saveQuizUrl, {
                'type': 'post',
                data: ko.toJSON(this.Quiz),
                contentType: 'application/json; charset=utf-8',
                success: function (response, status) {
                    if (status == 'success') {
                        toastr.success("Αποθήκευση επιτυχής!");
                        if (_this.Quiz.id() == 0) {
                            _this.Quiz.id(response);
                        }
                        _this.isLoading(false);
                    }
                }
            });
        }
    };
    return QuizEditViewModel;
})();
//# sourceMappingURL=QuizEditViewModel.js.map