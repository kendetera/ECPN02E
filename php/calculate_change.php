<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    // Get data from the order and cash inputs.
    $price = filter_var(trim((string) ($_POST['price'] ?? '')), FILTER_VALIDATE_FLOAT);
    $quantity = filter_var(trim((string) ($_POST['quantity'] ?? '')), FILTER_VALIDATE_INT);
    $discountRate = filter_var(trim((string) ($_POST['discount'] ?? '')), FILTER_VALIDATE_FLOAT);
    $cashGiven = filter_var(trim((string) ($_POST['cashGiven'] ?? '')), FILTER_VALIDATE_FLOAT);
    $allowedDiscounts = [0.0, 0.10, 0.15, 0.20];

    if ($price === false || $price < 0) {
        http_response_code(422);
        echo json_encode(['error' => 'Select a valid product first.']);
        exit;
    }

    if ($quantity === false || $quantity < 1) {
        http_response_code(422);
        echo json_encode(['error' => 'Quantity must be a whole number of at least 1.']);
        exit;
    }

    if ($discountRate === false || !in_array((float) $discountRate, $allowedDiscounts, true)) {
        http_response_code(422);
        echo json_encode(['error' => 'Select a valid discount option.']);
        exit;
    }

    if ($cashGiven === false || $cashGiven < 0) {
        http_response_code(422);
        echo json_encode(['error' => 'Enter a valid cash amount.']);
        exit;
    }

    // Recompute the payable amount, then calculate the customer's change.
    $discountAmount = round((float) $price * (float) $discountRate, 2);
    $discountedAmount = round((float) $price - $discountAmount, 2);
    $totalDiscount = round($discountAmount * (int) $quantity, 2);
    $totalAmount = round($discountedAmount * (int) $quantity, 2);
    $change = round((float) $cashGiven - $totalAmount, 2);

    // Return the recalculated totals and change.
    echo json_encode([
        'discountAmount' => $discountAmount,
        'discountedAmount' => $discountedAmount,
        'totalQuantity' => (int) $quantity,
        'totalDiscount' => $totalDiscount,
        'totalAmount' => $totalAmount,
        'change' => $change,
    ]);
    exit;
}

http_response_code(405);
header('Allow: POST');
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['error' => 'Only POST requests are accepted.']);
