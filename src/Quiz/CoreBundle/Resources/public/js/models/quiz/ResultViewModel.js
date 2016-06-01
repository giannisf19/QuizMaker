///<reference path="../_typings.ts"/>
var ResultViewModel = (function () {
    function ResultViewModel(url, assetUrl, swf) {
        var _this = this;
        this.Quizes = ko.observableArray([]);
        this.selectedQuiz = ko.observable();
        this.isLoading = ko.observable(false);
        this.data = [];
        this.currentResult = ko.observable({ user: { first_name: '', last_name: '' }, results: [] });
        this.getResultsUrl = url;
        this.assetUrl = assetUrl;
        this.swfUrl = swf;
        this.selectedQuiz.valueHasMutated();
        this.selectedQuiz.subscribe(function () {
            if (_this.selectedQuiz().length < 1) {
                return;
            }
            _this.isLoading(true);
            $.ajax(_this.getResultsUrl, {
                type: 'post',
                contentType: 'application/json; charset=utf-8',
                data: ko.toJSON(_this.selectedQuiz().id),
                success: function (response) {
                    var obj = JSON.parse(response);
                    _this.data = [];
                    _this.resp = obj;
                    var countSuccess = 0;
                    var countFail = 0;
                    var gradesData = [];
                    for (var a in Object.keys(obj)) {
                        var b = obj[a];
                        var d = new Date(b.date);
                        var formated = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + d.getHours() +
                            ':' + d.getMinutes() + ':' + d.getSeconds();
                        var toAdd = [
                            b.id,
                            b.user.first_name + ' ' + b.user.last_name,
                            b.user.registry_number,
                            b.user.semester,
                            formated,
                            b.test_duration,
                            b.degree,
                            b.is_passed == true ? 'Επιτυχία' : 'Αποτυχία',
                            'Επιλογή'
                        ];
                        if (b.is_passed) {
                            countSuccess++;
                        }
                        else {
                            countFail++;
                        }
                        // grades chart data
                        var index = _.findIndex(gradesData, function (tt) {
                            return tt.name == b.degree;
                        });
                        if (index == -1) {
                            gradesData.push({ name: b.degree, data: [1] });
                        }
                        else {
                            gradesData[index].data[0]++;
                        }
                        _this.data.push(toAdd);
                    }
                    _this.dt.fnClearTable();
                    _this.gradesChart.highcharts().setData([]);
                    if (_this.data.length != 0) {
                        _this.dt.fnAddData(_this.data);
                        _this.countChart.highcharts().series[0].setData([
                            ['Επιτυχία', countSuccess],
                            ['Αποτυχία', countFail]
                        ]);
                        gradesData.sort(function (a, b) {
                            return b.data - a.data;
                        });
                        _.forEach(gradesData, function (gdata) {
                            _this.gradesChart.highcharts().addSeries(gdata);
                        });
                    }
                    else {
                        _this.countChart.highcharts().series[0].setData([]);
                        while (_this.gradesChart.highcharts().series.length > 0) {
                            _this.gradesChart.highcharts().series[0].remove(true);
                        }
                    }
                    _this.isLoading(false);
                }
            });
        });
        $(function () {
            _this.dt = $('#results-table').dataTable({
                data: [],
                "sDom": 'Tlfrtip',
                columns: [
                    { 'title': 'Id' },
                    { 'title': 'Όνομα' },
                    { 'title': 'ΑΕΜ' },
                    { 'title': 'Εξάμηνο' },
                    { 'title': 'Ημερομηνία' },
                    { 'title': 'Διάρκεια' },
                    { 'title': 'Βαθμός' },
                    { 'title': 'Αποτέλεσμα' },
                    { 'title': 'Λεπτομέρειες' }
                ],
                language: { url: _this.assetUrl },
                "tableTools": {
                    "sSwfPath": _this.swfUrl,
                    "sDom": 'Tlfrtip',
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "sButtonText": "Αντιγραφή"
                        },
                        {
                            "sExtends": "print",
                            "sButtonText": "Εκτύπωση"
                        },
                        {
                            "sExtends": "csv",
                            "sButtonText": "CSV"
                        },
                        {
                            "sExtends": "pdf",
                            "sButtonText": "PDF"
                        },
                        {
                            "sExtends": "xls",
                            "sButtonText": "Excel"
                        }
                    ]
                }
            });
            $('#results-table tbody').on('click', 'tr', function (evt) {
                var id = $('td', evt.currentTarget).eq(0).text();
                for (var a in _this.resp) {
                    var c = _this.resp[a];
                    if (c.id == id) {
                        _.forEach(c.results, function (item) {
                            if (c.questionAnswer) {
                                var index = _.findIndex(c.questionAnswer, function (s) {
                                    return s.question.id == item.question.id;
                                });
                                if (index != -1) {
                                    c.questionAnswer[index].answers.push(item.answer);
                                }
                                else {
                                    c.questionAnswer.push({ question: item.question,
                                        is_correct: item.is_correct,
                                        degree: item.degree,
                                        answers: [item.answer]
                                    });
                                }
                            }
                            else {
                                c.questionAnswer = [];
                                c.questionAnswer.push({ question: item.question,
                                    is_correct: item.is_correct,
                                    degree: item.degree,
                                    answers: [item.answer]
                                });
                            }
                        });
                        _this.currentResult(c);
                    }
                }
                $('#moreInfoModal').modal('show');
            });
            _this.countChart = $('#countChart').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    width: 600
                },
                title: {
                    text: 'Πόσοι πέρασαν από όσους πήραν το Quiz'
                },
                tooltip: {},
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {}
                        }
                    }
                },
                series: [{
                        type: 'pie',
                        name: 'Σύνολο',
                        data: [
                            ['Επιτυχία', 20],
                            ['Αποτυχία', 80]
                        ]
                    }]
            });
            _this.gradesChart = $('#gradesChart').highcharts({
                chart: {
                    type: 'column',
                    width: 600
                },
                title: {
                    text: 'Κατανομή βαθμών'
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Σύνολο'
                    }
                },
                tooltip: {},
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: []
            });
        });
    }
    return ResultViewModel;
}());
//# sourceMappingURL=ResultViewModel.js.map