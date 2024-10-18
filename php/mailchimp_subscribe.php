<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiKey = '5e098748b357bb73e7c28201caf4e6b5-us22';
    $listId = 'acffebec23';
    $dataCenter = 'us22';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    $url = "https://$dataCenter.api.mailchimp.com/3.0/lists/$listId/members/";

    $emailHash = md5(strtolower($email));
    $checkUrl = $url . $emailHash;

    $ch = curl_init($checkUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "anystring:$apiKey");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $checkResponse = curl_exec($ch);
    $checkStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($checkStatusCode === 200) {
        $message = "Email is already subscribed.";
    } else {
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

        $message = $statusCode === 200 ? "Thank you! You've been added to the early access list." : "Failed to subscribe. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Form</title>
</head>
<body>
    <div class="card-body">
        <div class="card-title">$0</div>
        <p>We're offering free access to our Beta. Just enter your email below to get started.</p>

        <form id="earlyAccessForm" method="POST" action="">
            <div class="form-group">
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="btn btn-solid-reg">Start building</button>
        </form>

        <?php if (isset($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
