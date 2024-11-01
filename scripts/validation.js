jQuery(document).ready(function($){
    $('#publish').on("click",function () {
        var form = getFormObj("post");
        
        if (form["post_status"] == "publish" || form["original_post_status"] != "publish" || (form["original_post_status"] == "publish" && !form["post_status"] == "draft")) {
            var staff = $("#push_staff").is(":checked");
            var opencheck = $("#push_opencheck").is(":checked");
            var message = "This message will be sent to";

            if (staff)
                message = message + " all staff";

            if (staff && opencheck)
                message = message + " and";


            if (opencheck)
                message = message + " parents subscribing to school\'s OpenCheck";


            message = message + ". Are you sure you want to continue?";

            if (staff || opencheck)
                return confirm(message);
        }
    });

    function getFormObj(formId) {
        var formObj = {};
        var inputs = $('#' + formId).serializeArray();
        $.each(inputs, function (i, input) {
            formObj[input.name] = input.value;
        });
        return formObj;
    }
});