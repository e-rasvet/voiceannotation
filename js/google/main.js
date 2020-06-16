var typeRoot = M.cfg.wwwroot + "/filter/voiceannotation";

Mp3LameEncoderConfig = {
    memoryInitializerPrefixURL: typeRoot + "/js/google/",
    TOTAL_MEMORY: 1073741824 / 4,
}

var recStatus = [];
var audio_context;
var recorder;
var audio_stream;
var recs = 0;
var speechLang = 'en';
var dotsCount = 1;
var recordingBtnText;
var colorFlashBtn = "white";

function uploadFile(file, repo_id, itemid, title, ctx_id, btn) {
    var xhr = new XMLHttpRequest();
    var formdata = new FormData();
    formdata.append('repo_upload_file', file);
    formdata.append('sesskey', M.cfg.sesskey);
    formdata.append('repo_id', repo_id);
    formdata.append('itemid', itemid);
    formdata.append('title', title);
    formdata.append('overwrite', 1);
    formdata.append('ctx_id', ctx_id);
    var uploadUrl = M.cfg.wwwroot + "/repository/repository_ajax.php?action=upload";
    xhr.open("POST", uploadUrl, !0);
    xhr.btn = btn;
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            var audioname = this.btn.getAttribute('audioname');
            console.log(JSON.parse(xhr.responseText));
            $("#filter_voiceannotation_attachment_id").val(JSON.parse(xhr.responseText).id);
            document.getElementById(audioname).src = JSON.parse(xhr.responseText).url + '?' + Date.now();
            //document.getElementById(recStatus[activeRecID].audioname).src + '?' + Date.now();
            this.btn.innerHTML = '<i class="fa fa-microphone"></i> Start';
            this.btn.className = "filter_voiceannotation_srecordingBTN button-xl";
            this.btn.removeAttribute("disabled");
        }
    }
    xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            percentComplete = parseInt(percentComplete * 100);
            xhr.btn.innerHTML = '<i class="fa fa-upload" style="background: linear-gradient(to top, white '+percentComplete+'%, #a4c9e9 '+percentComplete+'% 100%);\n' +
                '    -webkit-background-clip: text;\n' +
                '    -webkit-text-fill-color: transparent;\n' +
                '    display: initial;"></i>  Uploading... ('+percentComplete+'%)';
        }
    }, false);

    xhr.send(formdata);
}


function startRecording() {
    navigator.getUserMedia({audio: true}, function (stream) {
        audio_stream = stream;

        var input = audio_context.createMediaStreamSource(stream);
        console.log('Media stream succesfully created');

        recorder = new Recorder(input, {typeRoot: typeRoot});
        console.log('Recorder initialised');

        recorder && recorder.record();
        console.log('Recording...');
    }, function (e) {
        console.error('No live audio input: ' + e);
    });
}

function stopRecording(callback) {
    recorder && recorder.stop();
    console.log('Stopped recording.');

    audio_stream.getAudioTracks()[0].stop();

    if (typeof (callback) == "function") {
        recorder && recorder.exportMP3(function (buffer) {
            callback(buffer);
            recorder.clear();
        });
    }
}

function recBtn(ev) {

    console.log("Event Started");

    audio_context = new AudioContext();

    var btn = ev.target;
    var id = btn.name;
    activeRecID = id;


    var final_transcript = '';

    if (recStatus[id] === undefined)
        recStatus[id] = null;

    if (recStatus[id] === null) {
        if (recs > 0)  // btn.innerText != 'Start recording' ||
            return;

        recs++;

        recStatus[id] = new webkitSpeechRecognition();
        recStatus[id].continuous = true;
        recStatus[id].interimResults = true;
        recStatus[id].lang = speechLang;
        recStatus[id].btn = btn;
        recStatus[id].ansDiv = document.getElementById(btn.getAttribute('answerDiv'));
        recStatus[id].speechtotextlang = btn.getAttribute('speechtotextlang');
        recStatus[id].audioname = btn.getAttribute('audioname');
        recStatus[id].options = btn.getAttribute('options');

        recStatus[id].onresult = function (e) {
            var interim_transcript = '';

            for (var i = e.resultIndex; i < e.results.length; ++i) {
                if (e.results[i].isFinal) {
                    final_transcript += e.results[i][0].transcript;
                    $(recStatus[activeRecID].ansDiv).text(final_transcript); // + ":fin";
                } else {
                    interim_transcript += e.results[i][0].transcript;
                    $(recStatus[activeRecID].ansDiv).text(final_transcript + interim_transcript); // + ":inter";
                }
            }

            // Choose which result may be useful for you
            /*
            console.log("Interim: ", interim_transcript);
            console.log("Final: ",final_transcript);
            console.log("Simple: ", e.results[0][0].transcript);
            */
        }

        recStatus[id].onend = function (e) {
            //this.btn.style.color = "black";
            clearInterval(recordingBtnText);
            colorFlashBtn = "white";

            this.btn.innerHTML = '<i class="fa fa-stop-circle"></i> Encoding...';
            var btn = this.btn;

            btn.className = "filter_voiceannotation_srecordingBTN button-xl";
            btn.setAttribute("disabled", true);

            stopRecording(function (blob) {
                recs--;
                btn.innerHTML = '<i class="fa fa-stop-circle"></i> Uploading...';
                var opts = JSON.parse(recStatus[activeRecID].options);
                uploadFile(blob, opts.repo_id, opts.itemid, opts.title, opts.ctx_id, btn);
                $('#filter_voiceannotation_update_id').prop('disabled', false);
            });

            recStatus[id] = null;
        }

        recStatus[id].start();
        startRecording();
        btn.className = "filter_voiceannotation_srecordingBTN button-xl-rec";
        btn.innerHTML = '<i class="fa fa-stop-circle"></i> Stop';

        recordingBtnText = setInterval(function () {
            var text = 'Stop';
            var dotsText = "";

            for (var i = 1; i <= dotsCount; i++) {
                dotsText = dotsText + ".";
            }

            if (colorFlashBtn != "white") {
                colorFlashBtn = "white";
            } else {
                colorFlashBtn = "brown";
            }

            btn.innerHTML = '<i class="fa fa-stop-circle" style="color:'+colorFlashBtn+'"></i> ' + text + dotsText;

            if (dotsCount > 4) {
                dotsCount = 1;
            } else {
                dotsCount++;
            }
        }, 500);

        $(recStatus[activeRecID].ansDiv).text('');
    } else {
        console.log("recStatus is not null");
        console.log(recStatus[id]);
        if (recStatus[id].abort)
            recStatus[id].abort();
    }
}


window.onload = function () {
    try {
        // Monkeypatch for AudioContext, getUserMedia and URL
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia;
        window.URL = window.URL || window.webkitURL;

        // Store the instance of AudioContext globally
        //audio_context = new AudioContext;
        console.log('Audio context is ready !');
        console.log('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));
    } catch (e) {
        alert('No web audio support in this browser!');
    }
}
