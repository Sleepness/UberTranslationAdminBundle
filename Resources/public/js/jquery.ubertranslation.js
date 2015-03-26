/*
 * jQuery for UberTranslationAdminBundle
 */
$(document).ready(function(){
    $("#btn_edit_translation").click(function(){
        $("#edited_translation").text(
            $("#translation_form_translation").val()
        );
        var url = $("#path_for_check").attr('href');
        $.get(url, {
            key: $("#translation_form_key").val(),
            locale: $("#translation_form_locale").val(),
            domain: $("#translation_form_domain").val()
        }, function(data) {
            if (data.isExists == true) {
                $("#check_existence").text("Exists");
            } else {
                $("#check_existence").text("Not Exists");
            }
        });
    });
});
