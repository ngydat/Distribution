/**
 * Cloze Question Controller
 * @param {FeedbackService} FeedbackService
 * @constructor
 */
var ClozeQuestionCtrl = function ClozeQuestionCtrl(FeedbackService) {
    AbstractQuestionCtrl.apply(this, arguments);

    // Initialize answer if needed
    if (null === this.questionPaper.answer || typeof this.questionPaper.answer === 'undefined') {
        this.questionPaper.answer = [];
    }
};

// Extends AbstractQuestionCtrl
ClozeQuestionCtrl.prototype = Object.create(AbstractQuestionCtrl.prototype);

// Set up dependency injection (get DI from parent too)
ClozeQuestionCtrl.$inject = AbstractQuestionCtrl.$inject;

/**
 * Stores Holes to be able to toggle there state
 * This object is populated while compiling the directive to add data-binding on cloze
 * @type {Object}
 */
ClozeQuestionCtrl.prototype.holes = {};

/**
 * Check whether a Hole is valid or not
 * @param   {Object} hole
 * @returns {Boolean}
 */
ClozeQuestionCtrl.prototype.isHoleValid = function isHoleValid(hole) {
    var solution = this.getHoleSolution(hole);
    var answer   = this.getHoleAnswer(hole);

    if (solution && answer) {
        var expected = this.getHoleExpectedAnswer(hole, solution);
        if (expected) {
            // The right response has been found, we can check the User answer
            if (hole.selector) {
                return answer.answerText === expected.id;
            } else {
                return !!((expected.caseSensitive && expected.text === answer.answerText)
                || (!expected.caseSensitive && expected.text.toLowerCase() === answer.answerText.toLowerCase()));
            }
        }
    }
};

/**
 * Get the User answer for a Hole
 * @param   {Object} hole
 * @returns {Object}
 */
ClozeQuestionCtrl.prototype.getHoleAnswer = function getHoleAnswer(hole) {
    var answer = null;

    for (var i = 0; i < this.questionPaper.answer.length; i++) {
        if (hole.id === this.questionPaper.answer[i].holeId) {
            answer = this.questionPaper.answer[i];
            break; // Stop searching
        }
    }

    if (null === answer) {
        // Generate an empty response
        answer = {
            holeId     : hole.id,
            answerText : ''
        };

        // Add to the list of answers
        this.questionPaper.answer.push(answer);
    }

    return answer;
};

/**
 * Get the complete solution for a Hole
 * @param   {Object} hole
 * @returns {{
 *      id      : String
 *      answers : Array
 * }}
 */
ClozeQuestionCtrl.prototype.getHoleSolution = function getHoleSolution(hole) {
    var solution = null;
    if (this.question.solutions) {
        for (var i = 0; i < this.question.solutions.length; i++) {
            if (this.question.solutions[i].holeId == hole.id) {
                solution = this.question.solutions[i];
                break; // Stop searching
            }
        }
    }

    return solution;
};

/**
 * Get the Feedback of a Hole
 * @param   {Object} hole
 * @returns {string}
 */
ClozeQuestionCtrl.prototype.getHoleFeedback = function getHoleFeedback(hole) {
    var feedback = '';

    var solution = this.getHoleSolution(hole);
    if (solution) {
        var expected = this.getHoleExpectedAnswer(hole, solution);
        if (expected && expected.feedback) {
            feedback = expected.feedback;
        }
    }

    return feedback;
};

/**
 * Get the expected answer from the Hole solution
 * @param   {Object} hole
 * @param   {Object} solution
 * @returns {{
 *      text          : String,
 *      caseSensitive : Boolean,
 *      score         : Number,
 *      feedback      : String,
 *      rightResponse : Boolean
 * }}
 */
ClozeQuestionCtrl.prototype.getHoleExpectedAnswer = function getHoleExpectedAnswer(hole, solution) {
    var expectedWord = null;
    if (hole.selector) {
        // The hole is a <select>
        // Find the expected word in the solution list
        for (var i = 0; i < solution.answers.length; i++) {
            if (solution.answers[i].rightResponse) {
                expectedWord = solution.answers[i];
                break; // stop searching
            }
        }
    } else {
        // The hole is a <input>
        expectedWord = solution.answers && solution.answers.length > 0 ? solution.answers[0] : null;
    }

    return expectedWord;
};

/**
 * Validate Holes when feedback are shown to know which answers are valid
 */
ClozeQuestionCtrl.prototype.onFeedbackShow = function onFeedbackShow() {
    // Validate holes
    if (this.question.solutions) {
        for (var holeId in this.holes) {
            if (this.holes.hasOwnProperty(holeId)) {
                this.holes[holeId].valid = this.isHoleValid(this.holes[holeId]);
            }
        }
    }
    
    this.answersAllFound();
};

ClozeQuestionCtrl.prototype.answersAllFound = function answersAllFound() {
    console.log(this.question);
    console.log(this.questionPaper.answer);
    var numAnswersFound = 0;
    
    for (var i=0; i<this.question.solutions.length; i++) {
        for (var j=0; j<this.questionPaper.answer.length; j++) {
            if (this.question.solutions[i].holeId === this.questionPaper.answer[j].holeId) {
                for (var k=0; k<this.question.solutions[i].answers.length; k++) {
                    for (var l=0; l<this.question.holes.length; l++) {
                        if (this.question.holes[l].id === this.question.solutions[i].holeId && this.question.solutions[i].answers[k].text === this.questionPaper.answer[j].answerText && this.question.solutions[i].answers[k].score > 0 && !this.question.holes[l].selector) {
                            numAnswersFound++;
                        }
                        else if (this.question.holes[l].id === this.question.solutions[i].holeId && this.question.solutions[i].answers[k].id === this.questionPaper.answer[j].answerText && this.question.solutions[i].answers[k].score > 0 && this.question.holes[l].selector) {
                            numAnswersFound++;
                        }
                    }
                }
            }
        }
    }
    
    console.log(numAnswersFound);
    console.log(this.question.solutions.length);
    
    if (numAnswersFound === this.question.solutions.length) {
        // all answers have been found
        this.feedback.state = 0;
    }
    else if (numAnswersFound === this.question.solutions.length -1) {
        // one answer remains to be found
        this.feedback.state = 1;
    }
    else {
        // more answers remain to be found
        this.feedback.state = 2;
    }
};

// Register controller into AngularJS
angular
    .module('Question')
    .controller('ClozeQuestionCtrl', ClozeQuestionCtrl);