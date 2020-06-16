define(['jquery', 'core/modal_factory', 'core/modal_events'], function($, ModalFactory, ModalEvents) {
    var trigger = $('#filter_voiceannotation_container');
    ModalFactory.create({
        title: 'Recording',
        body: '<div>' +
            '<div id="filter_voiceannotation_rec_btn">' +
            '</div>' +
            '<div class="filter_voiceannotation_rec_box">' +
            '<audio src="" controls="controls" id="filter_voiceannotation_audio_player"></audio>' +
            '</div><div id="filter_voiceannotation_answerDiv" class="filter_voiceannotation_rec_box"></div>' +
            '<div><button name="filter_voiceannotation_update" id="filter_voiceannotation_update_id" disabled="disabled" size="80"> Submit</button></div>' +
            '<input type="hidden" name="filter_voiceannotation_attachment" id="filter_voiceannotation_attachment_id" value=""></div>',
        footer: '',
    }, trigger)
        .then(function(modal) {

            this.modal = modal;

            console.log("Modal is created");

            this.modal.getRoot().on(ModalEvents.shown, function() {

                $('#filter_voiceannotation_rec').appendTo('#filter_voiceannotation_rec_btn');

                $("#filter_voiceannotation_update_id").unbind();
                $("#filter_voiceannotation_update_id").click(function (e) {

                    /*
                     * Set marker
                     */
                    var newNode = document.createElement("span");

                    newNode.setAttribute("id", "filter_voiceannotation_marker");
                    try {
                        window.range.surroundContents(newNode);
                    } catch (e) {
                        console.log("Error");
                    }
                    window.selected.removeAllRanges();

                    var journalentry = $("#" + window.htmlID).html().split('id="filter_voiceannotation_sep"></div>');

                    if (journalentry.length == 1) {
                        journalentry = journalentry[0];
                    } else {
                        journalentry = journalentry[1];
                    }

                    var opts = JSON.parse($('.filter_voiceannotation_srecordingBTN').attr('options'));

                    console.log("id:" + opts.id);
                    console.log("entryID:" + window.entryID);
                    console.log("attachment:" + $("#filter_voiceannotation_attachment_id").val());
                    console.log("contextid:" + opts.ctx_id);
                    console.log("comment:" + $('#filter_voiceannotation_answerDiv').text());
                    console.log("htmlID:" + window.htmlID);
                    console.log("html:" + journalentry);


                    $.post(M.cfg.wwwroot + "/filter/voiceannotation/submit.php", { id: opts.id, entryID: window.entryID, attachment: $("#filter_voiceannotation_attachment_id").val(),
                        contextid: opts.ctx_id, comment: $('#filter_voiceannotation_answerDiv').text(), html: journalentry}, function( data ) {
                        console.log( data );

                        window.location = (""+window.location).replace(/#[A-Za-z0-9_]*$/,'');
                    }, "json");


                });

            }.bind(this));

        });
});
