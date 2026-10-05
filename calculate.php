<?php
declare(strict_types=1);

/**
 * Calculate all order totals. Keeping this function in PHP makes the server the
 * source of truth for discounts and money calculations.
 *
 * @return array<string, float|int|null>
 */
function calculateOrder(float $price, int $quantity, float $discountRate, ?float $cashGiven): array
{
    $subtotal = round($price * $quantity, 2);
    $discountPerItem = round($price * $discountRate, 2);
    $discountedPrice = round($price - $discountPerItem, 2);
    $totalDiscount = round($discountPerItem * $quantity, 2);
    $totalAmount = round($subtotal - $totalDiscount, 2);

    return [
        'discountAmount' => $discountPerItem,
        'discountedAmount' => $discountedPrice,
        'totalQuantity' => $quantity,
        'totalDiscount' => $totalDiscount,
        'totalAmount' => $totalAmount,
        'change' => $cashGiven === null ? null : round($cashGiven - $totalAmount, 2),
    ];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Only POST requests are accepted.']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
$quantity = filter_var($_POST['quantity'] ?? null, FILTER_VALIDATE_INT);
$discountRate = filter_var($_POST['discount'] ?? null, FILTER_VALIDATE_FLOAT);
$cashInput = trim((string) ($_POST['cashGiven'] ?? ''));
$cashGiven = $cashInput === '' ? null : filter_var($cashInput, FILTER_VALIDATE_FLOAT);
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

if ($cashGiven === false || ($cashGiven !== null && $cashGiven < 0)) {
    http_response_code(422);
    echo json_encode(['error' => 'Cash given must be zero or greater.']);
    exit;
}

echo json_encode(calculateOrder((float) $price, (int) $quantity, (float) $discountRate, $cashGiven));
