<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiKey = '';
    $listId = 'acffebec23';
    $dataCenter = 'us22';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    $url = "https://$dataCenter.api.mailchimp.com/3.0/lists/$listId/members/";

    // Hash email for GET request to check if the email is already subscribed
    $emailHash = md5(strtolower($email));
    $checkUrl = $url . $emailHash;

    // Check if the email is already subscribed
    $ch = curl_init($checkUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "anystring:$apiKey");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $checkResponse = curl_exec($ch);
    $checkStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Expose the check response and status code in the browser
    echo "<h3>Check Response</h3>";
    var_dump($checkResponse);
    echo "<h3>Check Status Code</h3>";
    var_dump($checkStatusCode);

    // If already subscribed, show message
    if ($checkStatusCode === 200) {
        $message = "Email is already subscribed.";
    } else {
        // Subscribe the user
        $data = ['email_address' => $email, 'status' => 'subscribed'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "anystring:$apiKey");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Expose the subscription response and status code in the browser
        echo "<h3>Subscription Response</h3>";
        var_dump($response);
        echo "<h3>Subscription Status Code</h3>";
        var_dump($statusCode);

        // Mailchimp returns status code 201 for successful subscription
        $message = $statusCode === 201 ? "Thank you! You've been added to the early access list." : "Failed to subscribe. Please try again.";
    }

    // Display the message in the browser
    echo "<p>$message</p>";
}
?>
