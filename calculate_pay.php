<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    // Get data from the order inputs.
    $price = filter_var(trim((string) ($_POST['price'] ?? '')), FILTER_VALIDATE_FLOAT);
    $quantity = filter_var(trim((string) ($_POST['quantity'] ?? '')), FILTER_VALIDATE_INT);
    $discountRate = filter_var(trim((string) ($_POST['discount'] ?? '')), FILTER_VALIDATE_FLOAT);
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

    // Formulas for the selected discount and amount to pay.
    $discountAmount = round((float) $price * (float) $discountRate, 2);
    $discountedAmount = round((float) $price - $discountAmount, 2);
    $totalDiscount = round($discountAmount * (int) $quantity, 2);
    $totalAmount = round($discountedAmount * (int) $quantity, 2);

    // Return the calculated order totals.
    echo json_encode([
        'discountAmount' => $discountAmount,
        'discountedAmount' => $discountedAmount,
        'totalQuantity' => (int) $quantity,
        'totalDiscount' => $totalDiscount,
        'totalAmount' => $totalAmount,
    ]);
    exit;
}

http_response_code(405);
header('Allow: POST');
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['error' => 'Only POST requests are accepted.']);
