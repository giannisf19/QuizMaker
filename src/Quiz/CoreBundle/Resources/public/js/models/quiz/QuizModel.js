/// <reference path="../../typings/knockout/knockout.d.ts"/>
/// <reference path="../../typings/knockout.validation/knockout.validation.d.ts"/>
/// <reference path="../../typings/jquery/jquery.d.ts"/>
var QuizQuestion = (function () {
    function QuizQuestion() {
    }
    return QuizQuestion;
})();
var Quiz = (function () {
    function Quiz() {
        this.questions = ko.observableArray([]);
    }
    return Quiz;
})();
//# sourceMappingURL=QuizModel.js.map