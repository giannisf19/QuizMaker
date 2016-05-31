/// <reference path="../_typings.ts"/>
var QuizEditViewModel = (function () {
    function QuizEditViewModel(quiz, url, getQuestionsUrl, url2) {
        var _this = this;
        this.totalQuestionsGrade = ko.observable(0);
        if (quiz.name) {
            this.Quiz = ko.mapping.fromJS(quiz);
            this.Quiz.name = ko.observable(this.Quiz.name()).extend({ required: true });
            this.Quiz.time = ko.observable(this.Quiz.time()).extend({ number: true, required: true });
            this.Quiz.total_grade = ko.observable(this.Quiz.total_grade()).extend({ number: true, required: true });
            this.Quiz.pass_grade = ko.observable(this.Quiz.pass_grade()).extend({ number: true, required: true, equalOrLess: this.Quiz.total_grade });
            _.map(this.Quiz.questions(), function (curr) {
                curr.edit = ko.observable(false);
            });
        }
        else {
            this.Quiz = new Quiz();
            this.Quiz.name = ko.observable('').extend({ number: false, required: true });
            this.Quiz.time = ko.observable(60).extend({ number: true, required: true });
            this.Quiz.total_grade = ko.observable(0).extend({ number: true, required: true });
            this.Quiz.pass_grade = ko.observable(0).extend({ number: true, required: true, equalOrLess: this.Quiz.total_grade });
        }
        this.saveQuizUrl = url;
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
        _.forEach(this.Quiz.questions(), function (c) {
            total += parseInt(c.correct_answer_grade() + '');
        });
        this.totalQuestionsGrade(total);
        if (!this.Quiz.show_questions_randomly()) {
            this.Quiz.questions.sort(function (a, b) {
                return a.order() == b.order() ? 0 :
                    a.order() < b.order() ? -1 : 1;
            });
        }
        this.Quiz.questions.subscribe(function () {
            _.forEach(_this.Quiz.questions(), function (curr, index) {
                // init answer id's
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
                curr.correct_answer_grade.subscribe(function (val) {
                    var total = 0;
                    _.forEach(_this.Quiz.questions(), function (c) {
                        total += parseInt(c.correct_answer_grade() + '');
                    });
                    _this.totalQuestionsGrade(total);
                });
            });
        });
        // fire the subscriptions manually
        this.Quiz.questions.valueHasMutated();
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
            type: "warning", showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ναι!",
            cancelButtonText: "Όχι!",
            closeOnConfirm: true }, function () {
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
        var index = this.Quiz.questions().length - 1;
        function initToolbarBootstrapBindings() {
            var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
                'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
                'Times New Roman', 'Verdana'
            ], fontTarget = $('[title=Font]').siblings('.dropdown-menu');
            $.each(fonts, function (idx, fontName) {
                fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
            });
            $('a[title]').tooltip({
                container: 'body'
            });
            $('.dropdown-menu input').click(function () {
                return false;
            })
                .change(function () {
                $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
            })
                .keydown('esc', function () {
                this.value = '';
                $(this).change();
            });
            $('[data-role=magic-overlay]').each(function () {
                var overlay = $(this), target = $(overlay.data('target'));
                overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
            });
            if ("onwebkitspeechchange" in document.createElement("input")) {
                x;
                var editorOffset = $('#editor').offset();
                $('.voiceBtn').css('position', 'absolute').offset({
                    top: editorOffset.top,
                    left: editorOffset.left + $('#editor').innerWidth() - 35
                });
            }
            else {
                $('.voiceBtn').hide();
            }
        }
        initToolbarBootstrapBindings();
        $('#notes' + (this.Quiz.questions().length)).wysiwyg();
        window.prettyPrint;
        prettyPrint();
    };
    QuizEditViewModel.prototype.addSelectedQuestions = function () {
        var _this = this;
        var nonIncluded = _.filter(this.MyQuestions(), function (current) {
            return (current.selected() == true) &&
                _.filter(_this.Quiz.questions(), function (item) { return item.id() == current.id(); }).length == 0;
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
        var toReturn = false;
        toReturn = this.Quiz.name.isValid() && this.Quiz.pass_grade.isValid() && this.Quiz.time.isValid() && this.Quiz.total_grade.isValid();
        if (this.Quiz.questions().length == 0 && !this.Quiz.is_disabled()) {
            toReturn = false;
        }
        else if (this.Quiz.questions().length > 0) {
            _.forEach(this.Quiz.questions(), function (current) {
                if (current.answers().length == 0) {
                    toReturn = false;
                }
            });
            return toReturn;
        }
        return toReturn;
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
                    console.log(response);
                }
            });
        }
    };
    QuizEditViewModel.prototype.removeMediaElement = function (questionIndex, elementIndex) {
        console.log(questionIndex, elementIndex);
        this.Quiz.questions()[questionIndex].media_elements.splice(elementIndex, 1);
    };
    QuizEditViewModel.prototype.insertNewMediaElement = function () {
        var activeTabIndex = 0;
        $('#media-insert-tabs').children().each(function (index, item) {
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
                var nonIncluded = _.filter(this.MyMediaElements(), function (c) {
                    return c.selected() && _.filter(q.media_elements(), function (a) {
                        return a.id() == c.id();
                    }).length == 0;
                });
                _.forEach(nonIncluded, function (item) {
                    q.media_elements.push(item);
                });
                break;
        }
    };
    QuizEditViewModel.prototype.getMediaElements = function () {
        var _this = this;
        this.MyMediaElements([]);
        this.isMediaElementsLoading(true);
        $.ajax(this.getMediaElementsURL(), {
            type: 'post',
            success: function (response) {
                _this.isMediaElementsLoading(false);
                var items = JSON.parse(response);
                _.forEach(items, function (item) {
                    var toAdd = new MediaElement();
                    toAdd.id(item.id);
                    toAdd.src(item.src);
                    toAdd.media_type(item.media_type);
                    toAdd.selected(false);
                    _this.MyMediaElements.push(toAdd);
                });
            }
        });
    };
    return QuizEditViewModel;
}());
//# sourceMappingURL=QuizEditViewModel.js.map