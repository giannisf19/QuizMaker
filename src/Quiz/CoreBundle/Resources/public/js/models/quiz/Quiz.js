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
        this.feedback = ko.observable('');
    }
    return Answer;
})();
var MediaElement = (function () {
    function MediaElement() {
        this.media_type = ko.observable('');
        this.src = ko.observable('');
        this.selected = ko.observable(false);
        this.dataURL = ko.observable('');
        this.id = ko.observable(0);
    }
    return MediaElement;
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
        this.question_text = ko.observable('').extend({ required: true });
        this.order = ko.observable(0);
        this.type = ko.observable('multiple');
        this.answers = ko.observableArray([]);
        this.media_elements = ko.observableArray([]);
        this.correct_answer_grade = ko.observable(0).extend({ number: true, required: true });
        this.wrong_answer_grade = ko.observable(0).extend({ number: true, required: true });
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
        this.total_grade = ko.observable(0);
        this.pass_grade = ko.observable(0);
        this.show_questions_randomly = ko.observable(true);
        this.has_negative_grade = ko.observable(false);
    }
    return Quiz;
})();
//# sourceMappingURL=Quiz.js.map