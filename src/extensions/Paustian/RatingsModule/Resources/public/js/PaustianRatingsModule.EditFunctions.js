'use strict';

var editedObjectType;
var editedEntityId;
var editForm;
var formButtons;
var triggerValidation = true;

function paustianRatingsTriggerFormValidation() {
    paustianRatingsExecuteCustomValidationConstraints(editedObjectType, editedEntityId);

    if (!editForm.get(0).checkValidity()) {
        // This does not really submit the form,
        // but causes the browser to display the error message
        editForm.find(':submit').first().click();
    }
}

function paustianRatingsHandleFormSubmit(event) {
    if (triggerValidation) {
        paustianRatingsTriggerFormValidation();
        if (!editForm.get(0).checkValidity()) {
            event.preventDefault();
            return false;
        }
    }

    // hide form buttons to prevent double submits by accident
    formButtons.each(function (index) {
        jQuery(this).addClass('d-none');
    });

    return true;
}

/**
 * Initialises an entity edit form.
 */
function paustianRatingsInitEditForm(mode, entityId) {
    if (jQuery('.paustianratings-edit-form').length < 1) {
        return;
    }

    editForm = jQuery('.paustianratings-edit-form').first();
    editedObjectType = editForm.attr('id').replace('EditForm', '');
    editedEntityId = entityId;

    if (jQuery('#moderationFieldsSection').length > 0) {
        jQuery('#moderationFieldsContent').addClass('d-none');
        jQuery('#moderationFieldsSection legend').css({cursor: 'pointer'}).click(function (event) {
            if (jQuery('#moderationFieldsContent').hasClass('d-none')) {
                jQuery('#moderationFieldsContent').removeClass('d-none');
                jQuery(this).find('i').removeClass('fa-expand').addClass('fa-compress');
            } else {
                jQuery('#moderationFieldsContent').addClass('d-none');
                jQuery(this).find('i').removeClass('fa-compress').addClass('fa-expand');
            }
        });
    }

    var allFormFields = editForm.find('input, select, textarea');
    allFormFields.change(function (event) {
        paustianRatingsExecuteCustomValidationConstraints(editedObjectType, editedEntityId);
    });

    formButtons = editForm.find('.form-buttons input');
    if (editForm.find('.btn-danger').length > 0) {
        editForm.find('.btn-danger').first().bind('click keypress', function (event) {
            if (!window.confirm(Translator.trans('Do you really want to delete this entry?'))) {
                event.preventDefault();
            }
        });
    }
    editForm.find('button[type=submit]').bind('click keypress', function (event) {
        triggerValidation = !jQuery(this).attr('formnovalidate');
    });
    editForm.submit(paustianRatingsHandleFormSubmit);

    if ('create' !== mode) {
        paustianRatingsTriggerFormValidation();
    }
}

/**
 * Initialises a relation field section with autocompletion and optional edit capabilities.
 */
function paustianRatingsInitRelationHandling(objectType, alias, idPrefix, includeEditing, inputType, createUrl) {
    if (inputType == 'autocomplete') {
        paustianRatingsInitAutoCompletion(objectType, alias, idPrefix, includeEditing);
    }
    if (includeEditing) {
        paustianRatingsInitInlineEditingButtons(objectType, alias, idPrefix, inputType, createUrl);
    }
}

jQuery(document).ready(function () {
    if (jQuery('.relation-editing-definition').length > 0) {
        jQuery('.relation-editing-definition').each(function (index) {
            var editHandler = {
                alias: jQuery(this).data('alias'),
                prefix: jQuery(this).data('inline-prefix'),
                moduleName: jQuery(this).data('module-name'),
                objectType: jQuery(this).data('object-type'),
                inputType: jQuery(this).data('input-type'),
                windowInstanceId: null
            };
            paustianRatingsInlineEditHandlers.push(editHandler);
            paustianRatingsInitRelationHandling(
                jQuery(this).data('object-type'),
                jQuery(this).data('alias'),
                jQuery(this).data('prefix'),
                '1' == jQuery(this).data('include-editing'),
                jQuery(this).data('input-type'),
                jQuery(this).data('create-url')
            );
        });
    }
    if (jQuery('.field-editing-definition').length > 0) {
        jQuery('.field-editing-definition').each(function (index) {
            if ('user' === jQuery(this).data('field-type')) {
                initUserLiveSearch(jQuery(this).data('field-name'));
            } else if ('date' === jQuery(this).data('field-type')) {
                paustianRatingsInitDateField(jQuery(this).data('field-name'));
            } else if ('upload' === jQuery(this).data('field-type')) {
                paustianRatingsInitUploadField(jQuery(this).data('field-name'));
            }
        });
    }
    if (jQuery('#formEditingDefinition').length > 0) {
        paustianRatingsInitEditForm(jQuery('#formEditingDefinition').data('mode'), jQuery('#formEditingDefinition').data('entityid'));
    }
});
