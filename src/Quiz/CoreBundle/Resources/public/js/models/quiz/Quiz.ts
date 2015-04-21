class UserAnswer {
    constructor(public questionId : any, public type: any, public answer : any) {

    }
}

class QuizGradeResult {

    public questionId : string;
    public userAnswersId : any;
    public correctAnswerId : any;

    constructor(qid, userAnswer, correct) {
        this.questionId = qid;
        this.userAnswersId = userAnswer;
        this.correctAnswerId = correct;
    }

}

