var player = document.getElementById('player');
var snapshotCanvas = document.getElementById('snapshot');
var captureButton = document.getElementById('capture');
var videoTracks;

var handleSuccess = function(stream) {
  // Attach the video stream to the video element and autoplay.
  player.srcObject = stream;
  videoTracks = stream.getVideoTracks();
};

//captureButton.addEventListener('click', function() {
//  var context = snapshot.getContext('2d');
  //context.drawImage(player, 0, 0, snapshotCanvas.width, snapshotCanvas.height);

  // Stop all video streams.
  //videoTracks.forEach(function(track) {track.stop()});
//});

function addFoto(){
    var photo = document.createElement("img");
    var context = snapshot.getContext('2d');
    var dataURL = snapshot.toDataURL("image/png");
    context.drawImage(player, 0, 0, snapshotCanvas.width, snapshotCanvas.height);
    photo.setAttribute("class", "img");
    photo.setAttribute("src", dataURL);
    document.getElementById('images').insertBefore(photo, document.getElementById('images').firstChild);

    document.getElementById('hidden_data').value = dataURL;
    var fd = new FormData(document.forms["form1"]);

		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'upload_data.php', true);

		xhr.upload.onprogress = function(e) {
			if (e.lengthComputable) {
				var percentComplete = (e.loaded / e.total) * 100;
				console.log(percentComplete + '% uploaded');
			//	alert('Succesfully uploaded');
			}
		};

		xhr.onload = function() {

		};
		xhr.send(fd);
}

window.onload = navigator.mediaDevices.getUserMedia({video: true})
    .then(handleSuccess);