/*
 * jQuery for UberUberTranslationAdminBundle
 */
$(document).ready(function(){
    $("#btn_edit_translation").click(function(){
        $("#edited_translation").text(
            $("#translation_form_translation").val()
        );
    });
});
