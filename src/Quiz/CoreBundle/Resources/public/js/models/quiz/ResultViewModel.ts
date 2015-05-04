///<reference path="../_typings.ts"/>

interface JQuery {
    dataTable : any;
}

class ResultViewModel {

    public Quizes : KnockoutObservableArray<Quiz>  = ko.observableArray([]);
    public selectedQuiz  : KnockoutObservable<any> = ko.observable();
    public isLoading : KnockoutObservable<boolean> = ko.observable(false);
    public dt : any;
    public assetUrl : any;
    public data = [];
    public currentResult : KnockoutObservable<any> = ko.observable({user : {first_name: '', last_name :''}, results :[]});
    public resp : any;
    public getResultsUrl  : string;
    constructor(url : string, assetUrl : any) {


        this.getResultsUrl = url;
        this.assetUrl = assetUrl;


        this.selectedQuiz.valueHasMutated();
        this.selectedQuiz.subscribe(()=> {

            this.isLoading(true);
            $.ajax(this.getResultsUrl, {
                type : 'post',
                contentType: 'application/json; charset=utf-8',
                data: ko.toJSON(this.selectedQuiz().id),
                success: (response) =>{

                    var obj = JSON.parse(response);
                    this.data = [];
                    this.resp = obj;
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
                            'Κλικ'
                        ];

                        this.data.push(toAdd);
                    }


                    this.dt.fnClearTable();

                    if (this.data.length != 0) {
                        this.dt.fnAddData(this.data);
                    }



                    this.isLoading(false);
                }
            });

        });


        $(()=>{
            this.dt = $('#results-table').dataTable({

                data : [],

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
                language: {url :this.assetUrl}
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

        });

    }



}