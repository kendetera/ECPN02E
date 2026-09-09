<?php
$storeName = "SE-RI'S CHOICE STORE";
$pageTitle = $pageTitle ?? 'Kitchen Utensils';
$categoryKey = $categoryKey ?? 'kitchen';

$catalog = [
    'kitchen' => [
        'type' => 'kitchen utensil',
        'products' => [
            ['Utensils 1', 1200], ['Utensils 2', 1200], ['Utensils 3', 300], ['Utensils 4', 200], ['Utensils 5', 1000], ['Utensils 6', 1200],
            ['Utensils 7', 1500], ['Utensils 8', 2100], ['Utensils 9', 950], ['Utensils 10', 1000], ['Utensils 11', 1200], ['Utensils 12', 1280],
            ['Utensils 13', 2900], ['Utensils 14', 1900], ['Utensils 15', 1200], ['Utensils 16', 800], ['Utensils 17', 600], ['Utensils 18', 400],
        ],
    ],
    'bags' => [
        'type' => 'local bag',
        'products' => [
            ['LP Bag 1', 1200], ['LP Bag 2', 300], ['LP Bag 3', 300], ['LP Bag 4', 1200], ['LP Bag 5', 200], ['LP Bag 6', 500],
            ['LP Bag 7', 500], ['LP Bag 8', 1100], ['LP Bag 9', 900], ['LP Bag 10', 300], ['LP Bag 11', 200], ['LP Bag 12', 1280],
            ['LP Bag 13', 900], ['LP Bag 14', 900], ['LP Bag 15', 1200], ['LP Bag 16', 1800], ['LP Bag 17', 600], ['LP Bag 18', 1200],
        ],
    ],
    'perfumes' => [
        'type' => 'perfume',
        'products' => [
            ['Amouage Interlude', 1200], ['Club de Nuit Intense Man', 3200], ['Club de Nuit Preceiux', 3300], ['JPG Classique Women', 2200], ['JPG Le Beau', 2000], ['JPG Le Male Elixir', 3200],
            ['JPG Le Parfum', 1500], ['JPG Ultra Male', 2100], ['Khadlaj Island Dunes', 1100], ['Khadlaj Island', 3000], ['Lattafa Asad Zanzibar', 4200], ['Lattafa Asad', 2280],
            ['Lattafa Khamrah', 2900], ['Lattafa Oud for Glory', 1900], ['Lattafa', 1200], ['Liquid Brun', 3800], ['LV Imagination', 1600], ['LV Limmensite', 1200],
            ['LV Pacific Chill', 3330], ['Shiyaaka Blue', 1990], ['Shiyaaka Snow', 3000], ['Supremacy Collector\'s Edition', 5200], ['Supremacy Not Only Intense', 2200], ['Vulcan Feu', 2900],
        ],
    ],
    'lights' => [
        'type' => 'light fixture',
        'products' => [
            ['Light 1', 8200], ['Light 2', 4200], ['Light 3', 9300], ['Light 4', 12200], ['Light 5', 22000], ['Light 6', 13200],
            ['Light 7', 21500], ['Light 8', 1100], ['Light 9', 1000], ['Light 10', 33000], ['Light 11', 3200], ['Light 12', 30280],
            ['Light 13', 12900], ['Light 14', 41900], ['Light 15', 15200], ['Light 16', 32800], ['Light 17', 11600], ['Light 18', 51200],
            ['Light 19', 23330], ['Light 20', 11990], ['Light 21', 13900], ['Light 22', 3200], ['Light 23', 12200], ['Light 24', 43200],
        ],
    ],
    'shoes' => [
        'type' => 'shoe',
        'products' => [
            ['RShoes 1', 1200], ['RShoes 2', 300], ['RShoes 3', 3300], ['CShoes 4', 2200], ['CShoes 5', 2000], ['RShoes 6', 2300],
            ['RShoes 7', 1500], ['RShoes 8', 2100], ['LShoes 9', 1100], ['CShoes 10', 3000], ['CShoes 11', 4200], ['LShoes 12', 2280],
            ['RShoes 13', 2900], ['RShoes 14', 1900], ['LShoes 15', 1200], ['CShoes 16', 3800], ['LShoes 17', 1600], ['LShoes 18', 1200],
            ['LShoes 19', 3330], ['RShoes 20', 1990], ['RShoes 21', 3000], ['RShoes 22', 5200], ['RShoes 23', 2200], ['RShoes 24', 2900],
        ],
    ],
];

$category = $catalog[$categoryKey] ?? $catalog['kitchen'];

function peso(float $value): string
{
    return 'P' . number_format($value, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($storeName) ?> - <?= htmlspecialchars($pageTitle) ?></title>
  <link rel="stylesheet" href="styles.css?v=<?= filemtime(__DIR__ . '/styles.css') ?>">
</head>
<body data-category="<?= htmlspecialchars($categoryKey) ?>">
  <main class="store-page">
    <header class="site-header">
      <div class="brand-row">
        <span class="brand-mark" aria-hidden="true">SC</span>
        <div class="brand-text">
          <p class="brand-eyebrow">Online store</p>
          <h1><?= htmlspecialchars($storeName) ?></h1>
        </div>
        <p class="category-pill"><?= htmlspecialchars($pageTitle) ?></p>
      </div>

      <section class="top-controls" aria-label="Product navigation">
        <button class="search-button" type="button">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.5" y2="16.5"/></svg>
          Search
        </button>
        <label class="category-field">
          <span>Category</span>
          <select id="categorySelect" aria-label="Select product category">
            <option value="" disabled>Select product</option>
            <option value="perfumes.php" <?= $categoryKey === 'perfumes' ? 'selected' : '' ?>>Perfumes</option>
            <option value="local-bag-products.php" <?= $categoryKey === 'bags' ? 'selected' : '' ?>>Local Bag Products</option>
            <option value="shoes.php" <?= $categoryKey === 'shoes' ? 'selected' : '' ?>>Shoes</option>
            <option value="lights.php" <?= $categoryKey === 'lights' ? 'selected' : '' ?>>Lights</option>
            <option value="index.php" <?= $categoryKey === 'kitchen' ? 'selected' : '' ?>>Kitchen Utensils</option>
          </select>
        </label>
      </section>
    </header>

    <section class="section-head" aria-label="<?= htmlspecialchars($pageTitle) ?> heading">
      <div>
        <h2 class="section-title"><?= htmlspecialchars($pageTitle) ?></h2>
        <p class="section-subtitle"><?= count($category['products']) ?> products &middot; <?= htmlspecialchars($category['type']) ?> collection</p>
      </div>
    </section>

    <section class="pic_group" aria-label="<?= htmlspecialchars($pageTitle) ?> products">
      <?php foreach ($category['products'] as $index => [$name, $price]): ?>
        <article class="pic_option" tabindex="0" role="button" data-name="<?= htmlspecialchars($name) ?>" data-price="<?= $price ?>">
          <div class="image-box">
            <img class="product-image" src="images/<?= htmlspecialchars($categoryKey) ?>/product-<?= $index + 1 ?>.png" alt="<?= htmlspecialchars($name . ' ' . $category['type']) ?>">
            <span class="image-placeholder" hidden>Image unavailable</span>
          </div>
          <div class="product-caption">
            <span class="product-name"><?= htmlspecialchars($name) ?></span>
            <span class="product-price"><?= peso($price) ?></span>
          </div>
        </article>
      <?php endforeach; ?>
    </section>

    <section class="checkout-section">
      <form id="orderForm" class="order-details card">
        <h2>Order details</h2>
        <p class="card-subtitle">Select a product above to fill this in automatically.</p>
        <label class="input_box"><span>Name of item</span><input id="itemName" type="text" readonly placeholder="No item selected"></label>
        <label class="input_box"><span>Quantity</span><input id="quantity" type="number" min="1" step="1" inputmode="numeric" placeholder="0"></label>
        <label class="input_box"><span>Price</span><input id="price" type="text" readonly placeholder="P0.00"></label>
        <label class="input_box"><span>Discount amount</span><input id="discountAmount" type="text" readonly placeholder="P0.00"></label>
        <label class="input_box"><span>Discounted amount</span><input id="discountedAmount" type="text" readonly placeholder="P0.00"></label>
        <label class="input_box"><span>Total quantity</span><input id="totalQuantity" type="text" readonly placeholder="0"></label>
        <label class="input_box"><span>Total discount given</span><input id="totalDiscount" type="text" readonly placeholder="P0.00"></label>
        <label class="input_box"><span>Total discounted amount</span><input id="totalAmount" type="text" readonly placeholder="P0.00"></label>
        <label class="input_box"><span>Cash given</span><input id="cashGiven" type="number" min="0" step="0.01" inputmode="decimal" placeholder="0.00"></label>
        <label class="input_box total-row"><span>Change</span><input id="change" type="text" readonly placeholder="P0.00"></label>
      </form>

      <section class="right-panel card">
        <fieldset class="discount-options">
          <legend>Order discount options</legend>
          <label class="bundle_option"><input type="radio" name="discount" value="0.20"><span>Senior Citizen &middot; 20%</span></label>
          <label class="bundle_option"><input type="radio" name="discount" value="0.10"><span>With Disc. Card &middot; 10%</span></label>
          <label class="bundle_option"><input type="radio" name="discount" value="0.15"><span>Employee Disc. &middot; 15%</span></label>
          <label class="bundle_option"><input type="radio" name="discount" value="0" checked><span>No Discount</span></label>
        </fieldset>

        <div class="action-buttons">
          <button id="calculateButton" class="btn_process btn-primary" type="button">Calculate change</button>
          <button id="newButton" class="btn_process" type="button">New</button>
          <button id="saveButton" class="btn_process" type="button">Save</button>
          <button id="updateButton" class="btn_process" type="button">Update</button>
        </div>

        <div class="keypad" aria-label="Numeric keypad">
          <button class="enter-key" type="button" data-key="ENTER">Enter</button>
          <button type="button" data-key="/">/</button><button type="button" data-key="*">*</button><button type="button" data-key="-">-</button>
          <button type="button" data-key="+">+</button><button type="button" data-key="6">6</button><button type="button" data-key="7">7</button>
          <button type="button" data-key="8">8</button><button type="button" data-key="9">9</button><button type="button" data-key="2">2</button>
          <button type="button" data-key="3">3</button><button type="button" data-key="4">4</button><button type="button" data-key="5">5</button>
          <button type="button" data-key="0">0</button><button type="button" data-key=".">.</button><button type="button" data-key="1">1</button>
        </div>
      </section>
    </section>
  </main>
  <script src="script.js"></script>
</body>
</html>

