
<!DOCTYPE html>
<html>
<head>
  <title>Document Viewer</title>
  <style>
    #pdf-controls button {
      margin: 5px;
      padding: 5px 10px;
    }
    canvas {
      border: 1px solid #ccc;
      margin-top: 10px;
      max-width: 100%;
      height: auto;
    }
    #viewer {
      display: grid;
      place-items: center;
      /*min-height: 100vh; !* optional for vertical centering *!*/
    }
  </style>
</head>
<body>

<div id="viewer" >
  <p>Loading...</p>
</div>

@if(str_contains($contentType, 'image'))

  <script>
    const contentType = "{{ $contentType }}";
    const base64Data = "{{ $fileData }}";


    document.getElementById('viewer').innerHTML =
      '<img src="data:' + contentType + ';base64,' + base64Data + '" alt="Document Image" style="max-width:80%;display:block; margin:auto;">' +
      '<br>' +
      '<a href="data:' + contentType + ';base64,' + base64Data + '" download="document.' + contentType.split("/")[1] + '"' +
      ' style="margin-top:2px; size:20px; font-size: small; padding:4px 8px; background:#155f96; color:#fff; text-decoration:none; border-radius:4px;">' +
      'Download</a>';

  </script>
@else
  <p>Unsupported file type: {{ $contentType }}</p>
@endif

</body>
</html>
