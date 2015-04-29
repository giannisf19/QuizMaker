/// <reference path="../_typings.ts"/>
var UserAnswer = (function () {
    function UserAnswer(questionId, type, answer) {
        this.questionId = questionId;
        this.type = type;
        this.answer = answer;
    }
    return UserAnswer;
})();
var Answer = (function () {
    function Answer() {
        this.answer_text = ko.observable('');
        this.is_correct = ko.observable(false);
        this.left_or_right = ko.observable('');
        this.id = ko.observable(0);
    }
    return Answer;
})();
var QuizGradeResult = (function () {
    function QuizGradeResult(qid, userAnswer, correct) {
        this.questionId = qid;
        this.userAnswersId = userAnswer;
        this.correctAnswerId = correct;
    }
    return QuizGradeResult;
})();
var Question = (function () {
    function Question() {
        this.id = ko.observable(0);
        this.question_text = ko.observable('');
        this.order = ko.observable(0);
        this.type = ko.observable('multiple');
        this.answers = ko.observableArray([]);
        this.selected = ko.observable(false);
        this.edit = ko.observable(false);
    }
    return Question;
})();
var Quiz = (function () {
    function Quiz() {
        this.id = ko.observable(0);
        this.name = ko.observable('');
        this.is_disabled = ko.observable(true);
        this.is_private = ko.observable(true);
        this.time = ko.observable(60);
        this.questions = ko.observableArray([]);
    }
    return Quiz;
})();
//# sourceMappingURL=Quiz.js.map