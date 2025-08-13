<?php 
/* Template Name: SMS Send */
get_headre();
?>
 <form action="send_otp.php" method="post">
        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required><br>
        <button type="submit">Send OTP</button>
    </form>
    <?php


    <?php
require_once 'HTTP/Request2.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = htmlspecialchars($_POST['phone']);

    // Generate a random 6-digit OTP
    $otp = rand(100000, 999999);
    
    $request = new HTTP_Request2();
    $request->setUrl('https://z1ndqx.api.infobip.com/sms/2/text/advanced');
    $request->setMethod(HTTP_Request2::METHOD_POST);
    $request->setConfig(array(
        'follow_redirects' => TRUE
    ));
    $request->setHeader(array(
        'Authorization' => 'App 75da5f3ac8bbf6acb03642f1c9a5b799-86ee493b-83f5-4810-9306-971c37166c84',
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ));
    
    $body = array(
        'messages' => array(
            array(
                'destinations' => array(
                    array('to' => $phone)
                ),
                'from' => 'ServiceSMS',
                'text' => "Your OTP is: $otp"
            )
        )
    );
    
    $request->setBody(json_encode($body));
    
    try {
        $response = $request->send();
        if ($response->getStatus() == 200) {
            echo 'OTP sent successfully: ' . $response->getBody();
        } else {
            echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase();
        }
    } catch (HTTP_Request2_Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}


get_footer();
?>