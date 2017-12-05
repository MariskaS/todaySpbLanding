(function (window, $) {

    /**
     * Forms config.
     * @type {{forms: *[]}}
     */
    var config = {
        forms: [
            {selector: '#contactForm', path: '/form/'},
            {selector: '#orderForm', path: '/form/'},
            {selector: '#callOrderForm', path: '/form/'},
        ]
    };
    var commonErrorMessage = 'Произошла ошибка. Пожалуйста, попробуйте еще раз. ';
    var commonSuccessMessage = 'Заявка успешно отправлена. В ближайшее время мы с Вами свяжемся.';
    var ignoredFieldTypes = [
        'button', 'file', 'image', 'reset', 'submit'
    ];

    function SubmitForm() {
    }

    /**
     * Success event handler.
     * @param data
     */
    SubmitForm.onSuccess = function (data) {
        var message;

        try {
            message = data.message;
        }
        catch (e) {
        }

        alert(message || commonSuccessMessage);
    };

    /**
     * Error handler.
     * @param xhr
     */
    SubmitForm.onError = function (xhr) {
        var _error = xhr || {}, message;

        try {
            _error = JSON.parse(_error.responseText);
            message = _error.message;
        }
        catch (e) {
        }

        alert(message || commonErrorMessage);
    };

    /**
     * Returns form data.
     * @param form - Jquery object.
     * @returns {{}}
     * @private
     */
    SubmitForm._getFormData = function (form) {
        var data = {};

        $('select, textarea, input', form).each(function () {
            var self = $(this);

            if (ignoredFieldTypes.indexOf(self.attr('type')) === -1) {
                data[self.attr('name')] = self.val();
            }
        });

        return data;
    };

    /**
     * Functionality initialization.
     * @param selector
     * @param path
     */
    SubmitForm.init = function (selector, path) {
        var form = $(selector);

        form.on('submit', function (e) {
            e.preventDefault();

            var data = SubmitForm._getFormData(form);

            $.ajax({
                type: 'POST',
                url: path,
                data: JSON.stringify(data),
                contentType: 'application/json; charset=UTF-8',
                success: SubmitForm.onSuccess,
                error: SubmitForm.onError,
                dataType: 'json'
            });
        });
    };

    /**
     * Run initialization.
     */
    config.forms.forEach(function (value) {
        SubmitForm.init(value.selector, value.path);
    })

})(window, jQuery);