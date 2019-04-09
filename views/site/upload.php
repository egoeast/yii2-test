<?php ?>
<style>
    .bar {
        height: 18px;
        background: green;
    }
</style>
<!--<div class="container">
    <br>
    <br>
    <br>
    <br>
    <br>
<input id="fileupload" type="file" name="files[]" data-url="index.php?r=site%2Fuploading" multiple>
    <div id="progress">
        <div class="bar" style="width: 0%; height: 18px;
        background: green;"></div>
    </div>-->

    <ul id="filelist"></ul>
    <br />

    <div id="container">
        <a id="browse" href="javascript:;">[Browse...]</a>
        <a id="start-upload" href="javascript:;">[Start Upload]</a>
    </div>

    <br />
    <pre id="console"></pre>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/vendor/jquery.ui.widget.js"></script>
<script src="js/plupload.full.min.js"></script>

    <script type="text/javascript">
        var uploader = new plupload.Uploader({
            browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
            url: 'index.php?r=site%2Fuploading',
            chunk_size: '200kb',
            max_retries: 3
        });

        uploader.init();

        uploader.bind('FilesAdded', function(up, files) {
            var html = '';
            plupload.each(files, function(file) {
                html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
            });
            document.getElementById('filelist').innerHTML += html;
        });

        document.getElementById('start-upload').onclick = function() {
            uploader.start();
        };

        uploader.bind('Error', function(up, err) {
            document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
        });
    </script>
</div>