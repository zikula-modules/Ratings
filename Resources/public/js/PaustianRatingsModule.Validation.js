'use strict';

function paustianRatingsValidateNoSpace(val) {
    var valStr;
    valStr = new String(val);

    return (valStr.indexOf(' ') === -1);
}

/**
 * Runs special validation rules.
 */
function paustianRatingsExecuteCustomValidationConstraints(objectType, currentEntityId) {
}
