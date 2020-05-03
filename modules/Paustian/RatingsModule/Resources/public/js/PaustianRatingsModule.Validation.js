'use strict';

function paustianRatingsValidateNoSpace(val) {
    var valStr;

    valStr = '' + val;

    return -1 === valStr.indexOf(' ');
}

/**
 * Runs special validation rules.
 */
function paustianRatingsExecuteCustomValidationConstraints(objectType, currentEntityId) {
}
