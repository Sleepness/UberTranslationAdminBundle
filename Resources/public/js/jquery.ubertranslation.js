/*
 * jQuery for UberTranslationAdminBundle
 */
$(document).ready(function(){
    var definedMessage = $("#translation_form_translation").val();
    var definedLocale = $("#translation_form_locale").val();
    var definedDomain = $("#translation_form_domain").val();
    $("#btn_edit_translation").click(function(){
        //reset displaying changed properties
        $("#changed_translation").css("display", "none");
        $("#changed_locale").css("display", "none");
        $("#changed_domain").css("display", "none");
        $("#check_existence").css("display", "none");
        $("#confirm_message").css("display", "none");

        var url = $("#path_for_check").attr('href');
        $.get(url, {
            key: $("#translation_form_key").val(),
            locale: $("#translation_form_locale").val(),
            domain: $("#translation_form_domain").val()
        }, function(data) {
            if (data.isExists == true) {
                $("#check_existence").css("display", "initial");
            } else {
                $("#confirm_message").css("display", "initial");
            }
        });
        if ($("#translation_form_translation").val() != definedMessage) {
            $("#edited_translation").text($("#translation_form_translation").val());
            $("#changed_translation").css("display", "initial");
        }
        if ($("#translation_form_locale").val() != definedLocale) {
            $("#edited_locale").text($("#translation_form_locale").val());
            $("#changed_locale").css("display", "initial");
        }
        if ($("#translation_form_domain").val() != definedDomain) {
            $("#edited_domain").text($("#translation_form_domain").val());
            $("#changed_domain").css("display", "initial");
        }
    });
});
