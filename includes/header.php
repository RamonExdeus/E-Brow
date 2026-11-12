<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? 'EBROW';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?> - EBROW</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="/ebrow/assets/css/style.css">
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">
