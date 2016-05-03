/**
 * Paper details directive controller
 * 
 */
angular.module('Correction').controller('CorrectionShortCtrl', [
    'CommonService',
    'CorrectionService',
    function (CommonService, CorrectionService) {

        this.question = {};
        this.paper = {};
        this.answer = "";
        this.score = 0;
        this.message = false;

        this.init = function (question, paper, player) {
            this.question = question;
            this.paper = paper;
            this.player = player;
            if (!this.player) {
                for (var i=0; i<this.paper.questions.length; i++) {
                    if (question.id.toString() === this.paper.questions[i].id) {
                        this.answer = this.paper.questions[i].answer;
                        this.score = this.paper.questions[i].score;
                    }
                }
            }
        };

        this.getAnswer = function () {
            return this.answer;
        };

        this.setAnswer = function (answer) {
            this.answer = answer;
        };

        this.showNotationInput = function () {
            $("#note_question").hide();
            $("#score_p").show();
        };

        this.saveNote = function () {
            var note = $("#score_given").val();
            if( note <= this.question.scoreMaxLongResp){
                CorrectionService.saveScore(this.question.id, this.paper.id, note);
                for (var i=0; i<this.paper.questions.length; i++) {
                    if (this.question.id.toString() === this.paper.questions[i].id) {
                        this.paper.questions[i].score = note;
                    }
                }
            }
            else{
               return this.message = true;
            }
            this.score = note;
        };

    }
]);
