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
//# sourceMappingURL=Quiz.js.map