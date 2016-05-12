/**
 * Step Show Controller
 * @constructor
 * @param {FeedbackService}  FeedbackService
 */
var StepShowCtrl = function StepShowCtrl(FeedbackService) {
    this.FeedbackService = FeedbackService;
    this.feedback = this.FeedbackService.get();
};

// Set up dependency injection
StepShowCtrl.$inject = [ 'FeedbackService' ];

/**
 * Current step
 * @type {Object}
 */
StepShowCtrl.prototype.step = null;

/**
 * Current step number
 * @type {Object}
 */
StepShowCtrl.prototype.stepIndex = 0;


/**
 * Get the generic suite feedback
 * @returns {string}
 */
StepShowCtrl.prototype.getGenericSuiteFeedback = function getGenericSuiteFeedback() {
    if (this.currentStepTry === this.step.maxAttempts) {
        return "max_attempts_reached";
    }
    else if (this.currentStepTry === this.step.maxAttempts - 1) {
        return "one_try_left";
    }
    else {
        return "try_again";
    }
};

// Inject controller into AngularJS
angular
    .module('Step')
    .controller('StepShowCtrl', StepShowCtrl);