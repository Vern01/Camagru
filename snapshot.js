var vid = document.getElementById("stream");
var capture = document.getElementById("capture");
var canvas = document.getElementById("canvas");
var videoTracks;

var handleSuccess = function(stream){
    vid.src = URL.createObjectURL(stream)
    videoTracks = stream.getVideoTracks();
};

capture.addEventListener('click', function(){
    var context = canvas.getContext('2d');
    context.drawImage(vid, 0, 0, canvas.width, canvas.height);
});

//remeber to stop the video stream with videoTracks.forEach(function(track){track.stop()});

navigator.mediaDevices.getUserMedia({
    audio: false,
    video: true
}).then(handleSuccess);