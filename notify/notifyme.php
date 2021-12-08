<?php
###### Created By: Brad Sanders, 12/8/21 #######
###### Run this script: C:\xampp\php C:\xampp\htdocs\notify\notifyme.php


// This will cache our files contents, so we can keep track of changes
$file_contents_cache = "";

// This script will run in a loop forever, until stopped
$loop_count = 0; // This will keep track how many times the loop has ran
while (true){
    $loop_count = $loop_count +1; //Increment the loop counter

    //Read a file and check if it contains a value we need to know
    $file_to_read = "C:/xampp2/htdocs/notify/log.txt";
    $file_contents = file_get_contents($file_to_read);

    // Run Code Below if the log file changes, you will get a Slack message.
    // Lets send a message to Slack
    if($file_contents_cache != $file_contents){
        $file_contents_cache = $file_contents; //Lets set the cache, this will prevent sending you a message if the log is the same.
        $icon = "hugging_face";
        $message = "Your log file has logged an important message: ".$file_contents;
        sendSlack($icon,  $message);
    }
    sleep(2); // pause the script from looping again for x seconds
}



// Slack API Documentation: https://sfmitdepartment.slack.com/services/2812077189764?updated=1#service_setup
function sendSlack($icon,  $message){ //This is a function that can be called, it has to required paramaters.
    $payload["text"] = trim($message); //Trim removes the extra spaces around the string
    $payload["icon_emoji"] = $icon; //set Icon to that passed in the function

    // Make a curl call to the slack servers is their API format
    $ch = curl_init(); //Start Curl Setup
    curl_setopt($ch, CURLOPT_URL,"https://hooks.slack.com/services/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");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
        'payload' => json_encode($payload),
    )));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch); //Run Curl
    curl_close ($ch); //Close Curl
    return $server_output; // The function value will equal this.
}
