/*
 * Edit button click handler
 */
(function ($) {
    // Wrap jQuery objects into vars to make sure, that we initialize all objects once
    var $changed_translation = $("#changed_translation"),
        $changed_locale = $("#changed_locale"),
        $changed_domain = $("#changed_domain"),
        $translation_form_locale = $("#translation_form_locale"),
        $translation_form_key = $("#translation_form_key"),
        $translation_form_domain = $("#translation_form_domain"),
        $translation_form_translation = $("#translation_form_translation"),
        $check_existence = $("#check_existence"),
        $confirm_message = $("#confirm_message"),
        $trans_loading = $('#trans_loading');

    var definedMessage = $translation_form_translation.val(),
        definedKey = $translation_form_key.val(),
        definedLocale = $translation_form_locale.val(),
        definedDomain = $translation_form_domain.val();

    $("#btn_edit_translation").on('click', function () {
        $check_existence.hide();
        $confirm_message.hide();

        //reset displaying changed properties
        $changed_translation.css("display", "none");
        $changed_locale.css("display", "none");
        $changed_domain.css("display", "none");

        if (definedKey != $translation_form_key.val()) {
            $trans_loading.show();
            var url = $("#path_for_check").attr('href');
            $.get(url, {
                key: $translation_form_key.val(),
                locale: $translation_form_locale.val(),
                domain: $translation_form_domain.val()
            }, function (data) {
                if (data.isExists === true) {
                    $check_existence.show();
                    $confirm_message.hide();
                    $trans_loading.hide();
                } else {
                    $confirm_message.show();
                    $trans_loading.hide();
                }
            });
        } else {
            $confirm_message.show();
        }

        if ($translation_form_translation.val() != definedMessage) {
            $changed_translation.show();
            $("#edited_translation").text($("#translation_form_translation").val());
        }
        if ($translation_form_locale.val() != definedLocale) {
            $changed_locale.show();
            $("#edited_locale").text($("#translation_form_locale").val());
        }
        if ($translation_form_domain.val() != definedDomain) {
            $changed_domain.show();
            $("#edited_domain").text($("#translation_form_domain").val());
        }
    });
})(jQuery);
