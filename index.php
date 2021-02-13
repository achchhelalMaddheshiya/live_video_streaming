<body>

    <div class="left">
        <div class="row">
           
                <div class="card mb-4">
                    <video id="videPplay" autoplay style="width:600px;height:400px;    background-color: black;"> </video><br>
                    <video id="recording"  style="display:none;width:600px;height:400px;" controls></video></br>

                    <div class="button" style="padding:5px;radius:2px 2px 2px 2px;">
                        <button id="startToCap" class="button">Start</button>
                        <button id="stopToCap" class="button">Stop</button><br>
                        <pre id="log"></pre>
                    </div>
                    <p id="demo"></p>
                </div>
                <div class="card mb-4">
                    <video width="320" height="240" controls>
                     <!-- <source src="uploads/recordedChunks_1613219190.wemb" type="video/webm"> -->
                     <source src="uploads/recordedChunks_1613221334.mp4" type="video/mp4">
                    </video></br>
                </div>
          
        </div>
    </div>
   
<script>

    let videoPplay= document.getElementById("videPplay");
    let recording = document.getElementById("recording");
    let startButton = document.getElementById("startToCap");
    let stopButton = document.getElementById("stopToCap");

    let logElement = document.getElementById("log");
    let recordingTimeMS = 60000;
    recording.style.display = "none";
    function log(msg) {
        logElement.innerHTML += msg + "\n";
    }

    function startRecording(stream, lengthInMS) {
        let recorder = new MediaRecorder(stream);
        let data = [];

        recorder.ondataavailable = event => data.push(event.data);
        recorder.start();
        //log(recorder.state + " for " + (lengthInMS /1000) + " seconds...");
        log(recorder.state + "...");

        let stopped = new Promise((resolve, reject) => {
            recorder.onstop = resolve;
            recorder.onerror = event => reject(event.name);
        });

        // let recorded = wait(lengthInMS).then(
        //     () => recorder.state == "recording" && recorder.stop()
        // );

        return Promise.all([
                stopped,

            ])
            .then(() => data);
    }
    function stop(stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    startButton.addEventListener("click", function() {
        navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true
            }).then(stream => {
                videoPplay.srcObject = stream;
            // downloadButton.href = stream;
                videoPplay.captureStream = videoPplay.captureStream || videoPplay.mozCaptureStream;
                return new Promise(resolve => videoPplay.onplaying = resolve);
            }).then(() => startRecording(videoPplay.captureStream(), recordingTimeMS))
            .then(recordedChunks => {

                console.log(recordedChunks);

                let recordedBlob = new Blob(recordedChunks, {
                    type: "video/webm"
                });
                loadVideo(recordedBlob);
                videoPplay.style.display='none';
            // recording.show();
                recording.style.display = "block";

                recording.src = URL.createObjectURL(recordedBlob);
                //downloadButton.href = recording.src;
            // downloadButton.download = "RecordedVideo.mp4";
            document.getElementById("log").innerHTML =  "";
                log("Successfully recorded " + recordedBlob.size + " bytes of " +
                    recordedBlob.type + " media.");
            })
            .catch(log);
    }, false);

    stopButton.addEventListener("click", function() {
        stop(videoPplay.srcObject);
    }, false);

    function loadVideo(recordedChunks) {
    //var token = $("input[name=_token]").val();
    var formData = new FormData();
    formData.append('file_name', 'recordedChunks');
    //formData.append('_token',token);
    formData.append('video_blob', recordedChunks);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("demo").innerHTML =  this.responseText;

    }
    };

    //xhttp.open("POST", "{{route('frontend.user.capture.video')}}", true);
    xhttp.open("POST", "save.php", true);
    xhttp.send(formData);
    }
</script>
</body>
