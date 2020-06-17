define(['jquery', 'core/modal_factory', 'core/modal_events', 'core/config'], function($, ModalFactory, ModalEvents, mdlcfg) {
    var trigger = $('.filter_voiceannotation_player');
    ModalFactory.create({
        title: 'Voice annotation',
        body: '<div class="filter_voiceannotation_rec_box"><audio controls="controls" id="filter_voiceannotation_audio_player_listen"></audio></div>' +
            '<div id="filter_voiceannotation_player_comment" class="filter_voiceannotation_rec_box"></div>',
        footer: '',
    }, trigger)
        .then(function(modal) {

            this.modal = modal;
            var itemID = 0;
            var commentText = 0;
            var attachmentUrl = 0;

            this.modal.getRoot().on(ModalEvents.shown, function() {
                var audio = document.getElementById('filter_voiceannotation_audio_player_listen');
                audio.autoplay = true;
                audio.load()
                audio.addEventListener("load", function() {
                    audio.play();
                }, true);
                audio.src = attachmentUrl;
                //$('#filter_voiceannotation_audio_player_listen').attr('src', attachmentUrl);
                $('#filter_voiceannotation_player_comment').html(commentText);
            }.bind(this));

            $(".filter_voiceannotation_player").click(function (e) {
                var btn = e.target;

                if (btn.nodeName == "I") {
                    var btn = e.target.parentElement;
                }

                itemID = btn.getAttribute('data-itemid');
                commentText = btn.getAttribute('data-comment');
                attachmentUrl = btn.getAttribute('data-url');
            });

        });
});

