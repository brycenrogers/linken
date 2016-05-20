//  ====================  Welcome JS  ====================

//    created by:        Brycen Rogers ( brycenrogers@gmail.com )
//    creation data:     March 13, 2016

//    This file contains javascript for the Welcome page of Linken

//  ===========================================================

$( document ).ready(function() {

    $('#forgot-password-button').tooltip();

    var nameError           = "Please enter a name";
    var emailError          = "Please enter an email address";
    var emailInvalidError   = "Invalid email address";
    var passwordError       = "Please enter a password";
    var confirmError        = "Please confirm your password";
    var matchError          = "Your passwords do not match";

    var nameField           = "signup-name";
    var emailField          = "signup-email";
    var passwordField       = "signup-password";
    var confirmField        = "signup-password-confirm";

    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    };

    /**
     * Validates the Signup form
     *
     * @param submit
     * @returns {*}
     */
    function validateSignupForm(submit) {
        var name = $.trim($('#signup-name').val());
        var email = $.trim($('#signup-email').val());
        var password = $.trim($('#signup-password').val());
        var confirm = $.trim($('#signup-password-confirm').val());

        var errors = [];
        var errorFields = [];
        var remainingErrors = [];
        var confirmPasswords = true;

        // Name field
        if (name == "") {
            errors.push(nameError);
            errorFields.push(nameField);
        }
        // Email field
        if (email == "") {
            errors.push(emailError);
            errorFields.push(emailField);
        } else if ( ! isValidEmailAddress(email)) {
            errors.push(emailInvalidError);
            errorFields.push(emailField);
        }
        // Password field
        if (password == "") {
            confirmPasswords = false;
            errors.push(passwordError);
            errorFields.push(passwordField);
        }
        // Password Confirmation field
        if (confirm == "") {
            confirmPasswords = false;
            errors.push(confirmError);
            errorFields.push(confirmField);
        }
        if (confirmPasswords) {
            if (password != confirm) {
                errors.push(matchError);
                errorFields.push(passwordField);
                errorFields.push(confirmField);
            }
        }

        // Highlight the error fields
        if (submit == true) {
            $('#signup-modal .form-group').each(function () {
                var groupName = $(this).attr('id');
                if (groupName != undefined) {
                    var groupSplit = groupName.split('-');
                    fieldName = groupSplit[0] + '-' + groupSplit[1];
                    if (3 in groupSplit) {
                        fieldName += '-' + groupSplit[2];
                    }
                    var errorLoc = $.inArray(fieldName, errorFields);
                    if (errorLoc != -1) {
                        // Error was hit
                        $(this).addClass('has-error');
                    } else {
                        // Error was not hit
                        $(this).removeClass('has-error');
                    }
                }
            });

            // Add listeners
            $('#signup-name').on("blur", function () {
                updateFieldError(nameField);
            });
            $('#signup-email').on("blur", function () {
                updateFieldError(emailField);
            });
            $('#signup-password').on("blur", function () {
                updateFieldError(passwordField);
            });
            $('#signup-password-confirm').on("blur", function () {
                updateFieldError(confirmField);
            });

            // Show error messages and return
            var errorString = "";
            if (errors.length > 0) {
                errorString = errors.join('<br>');
                $('#signup-errors').html(errorString).show();
            } else {
                $('#signup-errors').html("").hide();
            }
        }

        // Return error data arrays
        return [errors, errorFields];
    }

    /**
     * Adds or removes error class from a field based on it's validation results
     *
     * @param field
     */
    function updateFieldError(field) {
        var errorDataArray = validateSignupForm();
        var errors = errorDataArray[0];
        var errorFields = errorDataArray[1];
        var errorLocForField = $.inArray(field, errorFields);
        if (errorLocForField == -1) {
            $('#' + field + '-group').removeClass('has-error');
        } else {
            $('#' + field + '-group').addClass('has-error');
        }
        // Handle password match/mismatch
        if (field == passwordField || field == confirmField) {
            matchErrorLoc = $.inArray(matchError, errors);
            if (matchErrorLoc == -1) {
                // No match error found, remove error classes if possible
                var passwordErrorLoc = $.inArray(passwordField, errorFields);
                var confirmErrorLoc = $.inArray(confirmField, errorFields);
                if (passwordErrorLoc == -1 && confirmErrorLoc == -1) {
                    $('#' + passwordField + '-group').removeClass('has-error');
                    $('#' + confirmField + '-group').removeClass('has-error');
                }
            } else {
                $('#' + passwordField + '-group').addClass('has-error');
                $('#' + confirmField + '-group').addClass('has-error');
            }
        }
    }

    $('#signup-submit-button').click(function() {
        var errorDataArray = validateSignupForm(true);
        var errors = errorDataArray[0];
        if (errors.length == 0) {
            $('#signup-form').submit();
        }
    });
});
