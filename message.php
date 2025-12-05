<?php

$file = "messages.json";

// Create file if missing
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$messages = json_decode(file_get_contents($file), true);

$action = $_GET["action"] ?? "";

/* ---------------- LIST MESSAGES ---------------- */
if ($action === "list") {
    echo json_encode($messages);
    exit;
}

/* ---------------- SEND MESSAGE ---------------- */
if ($action === "send") {
    $text = $_POST["text"] ?? "";
    $key  = $_POST["key"] ?? "";

    if (!$text) exit;

    $messages[] = [
        "id" => uniqid(),
        "text" => htmlspecialchars($text),
        "time" => date("Y-m-d H:i:s"),
        "key" => $key // stored delete permission
    ];

    file_put_contents($file, json_encode($messages));
    exit;
}

/* ---------------- DELETE MESSAGE ---------------- */
if ($action === "delete") {
    $id  = $_POST["id"] ?? "";
    $key = $_POST["key"] ?? "";

    // filter but keep only messages NOT matching id+key
    $messages = array_filter($messages, function($m) use ($id, $key) {
        return !($m["id"] === $id && $m["key"] === $key);
    });

    file_put_contents($file, json_encode(array_values($messages)));
    exit;
}

?>
