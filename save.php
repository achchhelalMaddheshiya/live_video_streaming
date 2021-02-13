<?php

if (isset($_FILES["video_blob"])) {
   
    $name = $_POST["file_name"] . '_' . time() . '.mp4';
    $uploadDirectory = 'uploads/' . $name;
    if (!move_uploaded_file($_FILES["video_blob"]["tmp_name"], $uploadDirectory)) {
        echo ("Problem writing video file to disk!");
    } else {

        echo "Congrats bro your video has been saved.";
        $videoFile = 'uploads/' . time() . '.mp4';

        
    }
} else {
    echo "Video file is not found.";
}
