const itemName = document.getElementById('itemName');
const price = document.getElementById('price');
const quantity = document.getElementById('quantity');
const cashGiven = document.getElementById('cashGiven');
let activeInput = quantity;

function peso(value) {
  return `P${Number(value).toLocaleString('en-PH', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })}`;
}

function selectProduct(card) {
  document.querySelectorAll('.pic_option').forEach((product) => product.classList.remove('selected'));
  card.classList.add('selected');
  itemName.value = card.dataset.name;
  price.value = peso(card.dataset.price);
  quantity.focus();
  calculateOrder();
}

function selectedDiscount() {
  return Number(document.querySelector('input[name="discount"]:checked').value);
}

function numberFromPrice() {
  return Number(price.value.replace(/[^0-9.]/g, '')) || 0;
}

function calculateOrder() {
  const unitPrice = numberFromPrice();
  const count = Number(quantity.value) || 0;
  const rate = selectedDiscount();
  const discountPerItem = unitPrice * rate;
  const discountedPerItem = unitPrice - discountPerItem;
  const totalDiscount = discountPerItem * count;
  const totalAmount = discountedPerItem * count;
  const cash = Number(cashGiven.value) || 0;

  document.getElementById('discountAmount').value = peso(discountPerItem);
  document.getElementById('discountedAmount').value = peso(discountedPerItem);
  document.getElementById('totalQuantity').value = count;
  document.getElementById('totalDiscount').value = peso(totalDiscount);
  document.getElementById('totalAmount').value = peso(totalAmount);
  document.getElementById('change').value = cashGiven.value === '' ? '' : peso(cash - totalAmount);
}

function newOrder() {
  document.getElementById('orderForm').reset();
  document.querySelector('input[name="discount"][value="0"]').checked = true;
  document.querySelectorAll('.pic_option').forEach((product) => product.classList.remove('selected'));
  ['discountAmount', 'discountedAmount', 'totalQuantity', 'totalDiscount', 'totalAmount', 'change'].forEach((id) => {
    document.getElementById(id).value = '';
  });
  itemName.value = '';
  price.value = '';
  activeInput = quantity;
  quantity.focus();
}

function keypadInput(key) {
  if (key === 'ENTER') {
    calculateOrder();
    return;
  }

  if (['+', '-', '*', '/'].includes(key)) {
    return;
  }

  if (key === '.' && activeInput.value.includes('.')) {
    return;
  }

  activeInput.value += key;
  activeInput.dispatchEvent(new Event('input'));
  activeInput.focus();
}

document.querySelectorAll('.pic_option').forEach((card) => {
  card.addEventListener('click', () => selectProduct(card));
  card.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      selectProduct(card);
    }
  });

  const image = card.querySelector('.product-image');
  image.addEventListener('error', () => {
    image.hidden = true;
    card.querySelector('.image-placeholder').hidden = false;
  });
});

document.getElementById('categorySelect').addEventListener('change', (event) => {
  if (event.target.value) {
    window.location.href = event.target.value;
  }
});
quantity.addEventListener('input', calculateOrder);
cashGiven.addEventListener('input', calculateOrder);
[quantity, cashGiven].forEach((input) => input.addEventListener('focus', () => {
  activeInput = input;
}));
document.querySelectorAll('input[name="discount"]').forEach((option) => option.addEventListener('change', calculateOrder));
document.getElementById('calculateButton').addEventListener('click', calculateOrder);
document.getElementById('newButton').addEventListener('click', newOrder);
document.querySelectorAll('.keypad button').forEach((button) => {
  button.addEventListener('click', () => keypadInput(button.dataset.key));
});

