/// <reference path="../../typings/knockout/knockout.d.ts"/>
/// <reference path="../../typings/knockout.validation/knockout.validation.d.ts"/>
/// <reference path="../../typings/jquery/jquery.d.ts"/>
/// <reference path="../../typings/sweetalert/sweetalert.d.ts"/>
/// <reference path="../../typings/knockout.mapping/knockout.mapping.d.ts"/>
/// <reference path="Quiz.ts"/>
var QuizViewModel = (function () {
    function QuizViewModel(quizInfo) {
        var _this = this;
        this.isDebug = true;
        this.Answers = [];
        this.Quiz = ko.mapping.fromJS(JSON.parse(quizInfo));
        this.isLoading = ko.observable(false);
        this.resultMode = ko.observable(false);
        this.finalDegree = ko.observable(0);
        this.passed = ko.observable(false);
        this.canSubmit = ko.observable(false);
        if (this.Quiz.show_questions_randomly()) {
            this.Quiz.questions(QuizViewModel.shuffle(this.Quiz.questions()));
        }
        // shuffle answers
        _.map(this.Quiz.questions(), function (item) {
            item.answers(QuizViewModel.shuffle(item.answers()));
        });
        $.each(this.Quiz.questions(), function (index, item) {
            _this.Answers.push(new UserAnswer(item.id(), item.type(), []));
        });
        $(function () {
            $('.answer').on('change', function (event) {
                var info = $(event.target).attr('name');
                var questionId = info.split(':')[0];
                var answerId = info.split(':')[1];
                var answerCheked = $(event.target).prop('checked');
                var questionIndex = _.findIndex(_this.Answers, function (item) {
                    return item.questionId == questionId;
                });
                var answerIndex = _.findIndex(_this.Answers[questionIndex].answer, function (item) {
                    return item == answerId;
                });
                var thisAnswer = _this.Answers[questionIndex];
                switch (thisAnswer.type) {
                    case 'multiple':
                        if (answerCheked) {
                            _this.Answers[questionIndex].answer.push(answerId);
                        }
                        else {
                            _this.Answers[questionIndex].answer.splice(answerIndex, 1);
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
                var can = _.every(_this.Answers, function (answer) {
                    return answer.answer.length > 0;
                });
                _this.canSubmit(can);
            });
        });
        $('body').keydown(function (which) {
            var $mycarousel = $('#questions_carousel');
            if (which.which == 39) {
                // next
                $mycarousel.carousel('next');
            }
            else if (which.which == 37) {
                // prev
                $mycarousel.carousel('prev');
            }
        });
        $('#questions_carousel').on('slid.bs.carousel', function () {
            $('#questions_carousel').carousel('pause');
            var index = $('#questions_carousel').find('.active').index('#questions_carousel .item');
            var $indicator = $('.question-list-item');
            $indicator.each(function (index, item) { $(item).removeClass('question-active'); });
            $indicator.eq(index).addClass('question-active');
        });
    }
    QuizViewModel.prototype.nextQuestion = function () {
        var myCarousel = $('#questions_carousel');
        myCarousel.carousel('next');
    };
    QuizViewModel.prototype.submitQuiz = function () {
        var _this = this;
        var final = location.href.replace('/start', '/submit');
        this.isLoading(true);
        $.ajax(final, {
            data: ko.toJSON(this.Answers),
            type: 'post',
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                $('.countdown-container').countdown('pause');
                var resp = response;
                if (resp.error && resp.error == 'test_not_started') {
                    sweetAlert('Σφάλμα!', 'Αυτό το τεστ δεν ξεκίνησε κανονικά', 'error');
                }
                else if (response.error == 'times_up') {
                    sweetAlert('Σφάλμα!', 'Τέλος χρόνου αυτό το quiz δεν θα μετρηθεί.', 'error');
                }
                else {
                    var a = ko.mapping.fromJSON(response);
                    _this.passed(a.resultObject.is_passed());
                    _this.finalDegree(a.resultObject.degree());
                    _.forEach(_this.Quiz.questions(), function (current) {
                        var resultQuestion = _.filter(a.questions(), function (item) {
                            return item.id() == current.id();
                        })[0];
                        var userAnswers = _.filter(a.resultObject.results(), function (item) {
                            return item.question.id() == current.id();
                        });
                        console.log(userAnswers);
                        var domQuestion = $('[data-question="' + current.id() + '"]');
                        var correctAnswerIds = _.filter(resultQuestion.answers(), function (item) { return item.is_correct(); });
                        var correctAnswers = _.map(correctAnswerIds, function (item) { return item.id(); });
                        domQuestion.find('.answer-line').each(function (index, element) {
                            var thisAnswerId = parseInt($(element).find('input').attr('name').split(':')[1]);
                            var hasUserSelectedThis = _.filter(userAnswers, function (item) { return item.answer.id() == thisAnswerId; }).length != 0;
                            var isCorrect = correctAnswers.indexOf(thisAnswerId) != -1;
                            var thisAnswer = _.filter(resultQuestion.answers(), function (item) { return item.id() == thisAnswerId; })[0];
                            var feedback = '<span> ' + thisAnswer.feedback() + '</span>';
                            var icon = '';
                            $(element).find('input').prop('disabled', true);
                            if (hasUserSelectedThis) {
                                $(element).append(feedback);
                                if (isCorrect) {
                                    icon = '<i class="fa fa-2x fa-check green-color"></i>';
                                }
                                else {
                                    icon = '<i class="fa fa-2x fa-times red-color"></i>';
                                }
                                $(element).append(icon);
                            }
                            else if (isCorrect && !hasUserSelectedThis) {
                                icon = '<i class="fa fa-2x fa-check green-color"></i>';
                                $(element).append(icon);
                            }
                        });
                    });
                }
                _this.resultMode(true);
                _this.isLoading(false);
            }
        });
    };
    QuizViewModel.shuffle = function (array) {
        var counter = array.length, temp, index;
        // While there are elements in the array
        while (counter > 0) {
            // Pick a random index
            index = Math.floor(Math.random() * counter);
            // Decrease counter by 1
            counter--;
            // And swap the last element with it
            temp = array[counter];
            array[counter] = array[index];
            array[index] = temp;
        }
        return array;
    };
    return QuizViewModel;
})();
//# sourceMappingURL=QuizViewModel.js.map