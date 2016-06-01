///<reference path="../_typings.ts"/>

interface JQuery {
    dataTable : any;
}

class ResultViewModel {

    public Quizes : KnockoutObservableArray<Quiz>  = ko.observableArray([]);
    public selectedQuiz  : KnockoutObservable<any> = ko.observable();
    public isLoading : KnockoutObservable<boolean> = ko.observable(false);
    public dt : any;
    public countChart : any;
    public gradesChart : any;
    public assetUrl : any;
    public data = [];
    public currentResult : KnockoutObservable<any> = ko.observable({user : {first_name: '', last_name :''}, results :[]});
    public resp : any;
    public getResultsUrl  : string;
    public swfUrl : string;
    constructor(url : string, assetUrl : any, swf) {


        this.getResultsUrl = url;
        this.assetUrl = assetUrl;
        this.swfUrl = swf;

        this.selectedQuiz.valueHasMutated();
        this.selectedQuiz.subscribe(()=> {


            if (this.selectedQuiz().length < 1) {
                return;
            }
            this.isLoading(true);
            $.ajax(this.getResultsUrl, {
                type : 'post',
                contentType: 'application/json; charset=utf-8',
                data: ko.toJSON(this.selectedQuiz().id),
                success: (response) =>{

                    var obj = JSON.parse(response);
                    this.data = [];
                    this.resp = obj;


                    var countSuccess = 0;
                    var countFail = 0;

                    var gradesData  = [];


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
                        } else {
                            countFail++;
                        }




                        // grades chart data
                        var index = _.findIndex(gradesData, (tt : any) => {
                            return tt.name == b.degree;
                        });



                        if (index == -1) {
                            gradesData.push({name: b.degree, data: [1]})
                        } else {
                            gradesData[index].data[0]++;
                        }



                        this.data.push(toAdd);
                    }



                    this.dt.fnClearTable();

                    try {
                        while(this.gradesChart.highcharts().series.length > 0) {
                            this.gradesChart.highcharts().series[0].remove(true);
                        }

                    } catch (ex ) {

                    }




                    if (this.data.length != 0) {
                        this.dt.fnAddData(this.data);
                        this.countChart.highcharts().series[0].setData([
                            ['Επιτυχία', countSuccess],
                            ['Αποτυχία', countFail]
                        ]);


                        gradesData.sort((a : any,b : any)=> {
                            return  b.data-a.data;
                        });

                        _.forEach(gradesData, (gdata) => {
                            this.gradesChart.highcharts().addSeries(gdata);
                        });


                    } else {
                        this.countChart.highcharts().series[0].setData([]);

                        while(this.gradesChart.highcharts().series.length > 0) {
                            this.gradesChart.highcharts().series[0].remove(true);
                        }
                    }



                    this.isLoading(false);
                }
            });

        });


        $(()=>{
            this.dt = $('#results-table').dataTable({

                data : [],
                "sDom": 'Tlfrtip',
                columns: [
                    {'title' : 'Id'},
                    {'title' : 'Όνομα'},
                    {'title' : 'ΑΕΜ'},
                    {'title' : 'Εξάμηνο'},
                    {'title' : 'Ημερομηνία'},
                    {'title' : 'Διάρκεια'},
                    {'title' : 'Βαθμός'},
                    {'title' : 'Αποτέλεσμα'},
                    {'title' : 'Λεπτομέρειες'}
                ],
                language: {url :this.assetUrl},

                "tableTools": {
                    "sSwfPath": this.swfUrl,
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


            $('#results-table tbody').on('click', 'tr', (evt) => {
               var id = $('td', evt.currentTarget).eq(0).text();

                for (var a in this.resp) {
                    var c = this.resp[a];
                    if (c.id == id) {

                        _.forEach(c.results, (item : any) => {
                            if (c.questionAnswer) {
                                var index = _.findIndex(c.questionAnswer, (s : any) => {
                                    return s.question.id == item.question.id;
                                });

                                if (index != -1) {
                                    c.questionAnswer[index].answers.push(item.answer);
                                } else {
                                    c.questionAnswer.push({question : item.question,
                                        is_correct : item.is_correct,
                                        degree: item.degree,
                                        answers: [item.answer]
                                    });
                                }


                            } else {
                                c.questionAnswer = [];
                                c.questionAnswer.push({question : item.question,
                                    is_correct : item.is_correct,
                                    degree: item.degree,
                                    answers: [item.answer]
                                });
                            }
                        });

                        this.currentResult(c);

                    }
                }

                $('#moreInfoModal').modal('show');

            });







            this.countChart = $('#countChart').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    width: 600
                },
                title: {
                    text: 'Πόσοι πέρασαν από όσους πήραν το Quiz'
                },
                tooltip: {

                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                //color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
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



            this.gradesChart =  $('#gradesChart').highcharts({
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
                tooltip: {

                },
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



}