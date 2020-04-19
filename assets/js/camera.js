if (document.getElementById("btn-start") != null) {
  var selected_sticker_id = "picsticker0";

  // The buttons to start & stop videoStream and to canvasCaptureEdited the image
  var btnStart = document.getElementById("btn-start");
  var btnStop = document.getElementById("btn-stop");
  var btnCapture = document.getElementById("btn-capture");


  var btnPublish = document.getElementById("publish-btn");

  var publishform = document.getElementById("publishform");


  var postTitle = document.getElementById("postTitle");


  var imageUploadedTemp = null;

  // The videoStream & canvasCaptureEdited
  var videoStream = document.getElementById("videoStream");
  var canvasCaptureEdited = document.getElementById("canvasCaptureEdited");
  var canvasCaptureEditedPrev = document.getElementById(
    "canvasCaptureEditedPrev"
  );
  var canvasCapture = document.getElementById("canvasCapture");
  var picturePreviewEditedDiv = document.getElementById("picturePreviewEdited");
  var uploaded_picture_file = document.getElementById("uploaded_picture_file");

  var captured_image = document.getElementById("captured_image");

  var radioCameraMode = document.getElementById("radioCameraMode");
  var radioUploadMode = document.getElementById("radioUploadMode");

  var final_image = document.getElementById("final_image");

  uploaded_picture_file.classList.add("hiddenBtn");

  var sticker_w = canvasCaptureEdited.width * 0.3;
  var sticker_offset = 10;
  var sticker_offset2 = canvasCaptureEdited.height - sticker_w - sticker_offset;

  radioCameraMode.checked = true;


  btnPublish.disabled = true;

  postTitle.addEventListener('input', function (evt) {
    //console.log(this.value);
    if (this.value!='')
    {
      if (this.value.length > 4)
      {
        btnPublish.disabled = false;
      }else{
        btnPublish.disabled = true;
      }
    }else{
      btnPublish.disabled = true;
    }
});



  function check_chosen_mode(radiobox) {
    if (radiobox.checked == true) {
      if (radiobox.id == "radioCameraMode") {

        final_image.setAttribute("style", "display: none;");
        //alert('camera')
        startStreaming();
        canvasCaptureEdited.classList.remove("hiddenBtn");
        canvasCaptureEdited.classList.add("rounded");
        canvasCaptureEdited.classList.add("w-100");
        canvasCaptureEdited.classList.add("h-100");
        btnCapture.classList.remove("hiddenBtn");
        btnStop.classList.remove("hiddenBtn");
        btnStart.classList.remove("hiddenBtn");
        videoStream.classList.remove("hiddenBtn");
        uploaded_picture_file.classList.add("hiddenBtn");
      } else if (radiobox.id == "radioUploadMode") {

        final_image.setAttribute("style", "display: none;");
        //alert('upload')
        stopStreaming();
        uploaded_picture_file.classList.remove("hiddenBtn");
        canvasCaptureEdited.classList.add("hiddenBtn");
        canvasCaptureEdited.classList.remove("rounded");
        canvasCaptureEdited.classList.remove("w-100");
        canvasCaptureEdited.classList.remove("h-100");
        btnCapture.classList.add("hiddenBtn");
        btnStop.classList.add("hiddenBtn");
        btnStart.classList.add("hiddenBtn");
        videoStream.classList.add("hiddenBtn");
      }
    }
  }

  function check_chosen_sticker() {
    var btnCapture = document.getElementById("btn-capture");
    var stickerChosenForm = document.getElementById("stickerChosenForm");

    var class_name = "chosen-sticker";
    var stickers = document.getElementsByName("sticker");
    var elem;
    for (var i = 0; i < stickers.length; i++) {
      elem = document.getElementById("pic" + stickers[i].id);
      if (elem != null) {
        if (stickers[i].checked) {
          if (elem.classList.contains(class_name) == false) {
            elem.classList.add(class_name);
          }
          selected_sticker_id = elem.id;
          btnCapture.disabled = false;
        } else {
          if (elem.classList.contains(class_name) == true) {
            elem.classList.remove(class_name);
          }
        }
      }
    }
    if (selected_sticker_id == null) {
      btnCapture.disabled = true;
    }
    stickerChosenForm.setAttribute(
      "value",
      selected_sticker_id.replace("picsticker", "")
    );

    if (radioUploadMode.checked == true) {
      //console.log('okk');
      sticker_selected = document.getElementById(selected_sticker_id);
      //showImage(uploaded_picture_file, canvasCaptureEditedPrev);
      var cnv = canvasCaptureEditedPrev.getContext("2d");
      if (sticker_selected != null && imageUploadedTemp != null) {
        cnv.drawImage(
          imageUploadedTemp,
          0,
          0,
          imageUploadedTemp.width,
          imageUploadedTemp.height,
          0,
          0,
          canvasCaptureEditedPrev.width,
          canvasCaptureEditedPrev.height
        );
        cnv.drawImage(
          sticker_selected,
          sticker_offset,
          sticker_offset2,
          sticker_w,
          sticker_w
        );
      }
    }
  }

  function showImage(uploaded_picture_file, canvasTarget) {
    var ctx = canvasTarget.getContext("2d");
    var img = new Image();
    var fr = new FileReader();
    fr.onload = function () {
      img.src = fr.result;
    };
    fr.readAsDataURL(uploaded_picture_file.files[0]);
    img.onload = function () {
      //console.log(img);
      // ctx.drawImage(img, 0, 0);
      imageUploadedTemp = img;
      ctx.drawImage(
        img,
        0,
        0,
        img.width,
        img.height,
        0,
        0,
        canvasTarget.width,
        canvasTarget.height
      );
      if (sticker_selected != null) {
        ctx.drawImage(
          sticker_selected,
          sticker_offset,
          sticker_offset2,
          sticker_w,
          sticker_w
        );
        final_image.setAttribute("style", "");
      }
    };
  }

  function putImage() {
    showImage(uploaded_picture_file, canvasCaptureEditedPrev);
  }

  // Start Streaming
  function startStreaming() {
    var mediaSupport = "mediaDevices" in navigator;

    if (mediaSupport && null == cameraStream) {
      navigator.mediaDevices
        .getUserMedia({ video: true })
        .then(function (mediaStream) {
          cameraStream = mediaStream;

          //videoStream.srcObject = mediaStream;

          if ("srcObject" in videoStream) {
            // video.srcObject = stream;
            videoStream.srcObject = mediaStream;
          }
          else {
            videoStream.src = window.URL.createObjectURL(mediaStream);
          }

          videoStream.play();
          btnStop.classList.remove("hiddenBtn");
          btnStart.classList.add("hiddenBtn");
        })
        .catch(function (err) {
          //console.log("Unable to access camera: " + err);
        });
    } else {
      alert("Your browser does not support media devices.");
      return;
    }
  }

  // Stop Streaming
  function stopStreaming() {
    if (null != cameraStream) {
      var track = cameraStream.getTracks()[0];

      track.stop();
      videoStream.load();

      cameraStream = null;

      btnStart.classList.remove("hiddenBtn");
      btnStop.classList.add("hiddenBtn");
    }
  }

  startStreaming();

  // The video videoStream
  var cameraStream = null;

  var ctxEdited = canvasCaptureEdited.getContext("2d");
  var ctxEditedPrev = canvasCaptureEditedPrev.getContext("2d");
  var ctx = canvasCapture.getContext("2d");

  var i;
  var sticker_selected = document.getElementById(selected_sticker_id);

  function captureSnapshot() {
    sticker_selected = document.getElementById(selected_sticker_id);
    //console.log('captureSnapshot');

    if (null == cameraStream) {
      alert("Start the camera first");
      return;
    }
    if (null == selected_sticker_id) {
      alert("Select a sticker from the list");
      return;
    }

    picturePreviewEditedDiv.innerHTML = "";

    //canvas
    //ctxEdited.drawImage(videoStream, 0, 0, canvasCaptureEdited.width, canvasCaptureEdited.height);
    //edited img add
    if (sticker_selected == null) {
      btnCapture.disabled = true;
      return;
    }

    ctx.drawImage(videoStream, 0, 0, canvasCapture.width, canvasCapture.height);

    if (cameraStream != null) {
      ctxEditedPrev.drawImage(
        videoStream,
        0,
        0,
        canvasCaptureEditedPrev.width,
        canvasCaptureEditedPrev.height
      );
      final_image.setAttribute("style", "");
    }

    if (sticker_selected != null) {
      ctxEditedPrev.drawImage(
        sticker_selected,
        sticker_offset,
        sticker_offset2,
        sticker_w,
        sticker_w
      );
    }

    captured_image.setAttribute("value", canvasCapture.toDataURL("image/png"));
  }

  // Attach listeners
  btnStart.addEventListener("click", startStreaming);
  btnStop.addEventListener("click", stopStreaming);
  btnCapture.addEventListener("click", captureSnapshot);
  btnCapture.disabled = false;
  btnStop.classList.add("hiddenBtn");

  videoStream.addEventListener(
    "play",
    function () {
      i = window.setInterval(function () {
        var sticker_selected_2 = document.getElementById(selected_sticker_id);
        ctxEdited.drawImage(
          videoStream,
          0,
          0,
          canvasCaptureEdited.width,
          canvasCaptureEdited.height
        );
        if (sticker_selected_2 != null) {
          ctxEdited.drawImage(
            sticker_selected_2,
            sticker_offset,
            sticker_offset2,
            sticker_w,
            sticker_w
          );
        }
      }, 20);
    },
    false
  );
  videoStream.addEventListener(
    "pause",
    function () {
      window.clearInterval(i);
    },
    false
  );
  videoStream.addEventListener(
    "ended",
    function () {
      clearInterval(i);
    },
    false
  );
}
