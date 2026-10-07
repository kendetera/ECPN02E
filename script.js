document.getElementById('categorySelect').addEventListener('change', (event) => {
  if (event.target.value) {
    window.location.href = event.target.value;
  }
});

function peso(value) {
  return `P${Number(value).toLocaleString('en-PH', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })}`;
}

function selectProduct(card) {
  document.querySelectorAll('.pic_option').forEach((product) => product.classList.remove('selected'));
  card.classList.add('selected');
  document.getElementById('itemName').value = card.dataset.name;
  document.getElementById('price').value = peso(card.dataset.price);
  document.getElementById('rawPrice').value = card.dataset.price;
  document.getElementById('quantity').value ||= '1';
  activeInput = quantityInput;
  keypadOperation = null;
  startNewNumber = true;
  clearResults();
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

const orderForm = document.getElementById('orderForm');
const quantityInput = document.getElementById('quantity');
const cashInput = document.getElementById('cashGiven');
const statusText = document.getElementById('calculationStatus');
const resultFields = [
  'discountAmount',
  'discountedAmount',
  'totalQuantity',
  'totalDiscount',
  'totalAmount',
  'change'
];
let activeInput = quantityInput;
let keypadOperation = null;
let startNewNumber = false;

function clearResults() {
  resultFields.forEach((id) => {
    document.getElementById(id).value = '';
  });
  statusText.textContent = '';
  statusText.classList.remove('success');
}

function showStatus(message) {
  statusText.textContent = message;
}

function clearChange() {
  document.getElementById('change').value = '';
  showStatus('');
}

function handleAmountInput(input) {
  if (input === cashInput) {
    clearChange();
  } else {
    clearResults();
  }
}

function setActiveInput(input) {
  activeInput = input;
  input.focus();
}

[quantityInput, cashInput].forEach((input) => {
  input.addEventListener('focus', () => {
    activeInput = input;
    keypadOperation = null;
    startNewNumber = false;
  });
  input.addEventListener('input', () => handleAmountInput(input));
});

function applyKeypadOperator(operator) {
  const currentValue = Number(activeInput.value);
  if (!Number.isFinite(currentValue)) {
    showStatus('Enter a number before choosing an operator.');
    return;
  }

  if (keypadOperation && !startNewNumber) {
    evaluateKeypadOperation();
  }

  keypadOperation = { firstValue: Number(activeInput.value), operator };
  startNewNumber = true;
}

function evaluateKeypadOperation() {
  if (!keypadOperation || startNewNumber) return true;

  const secondValue = Number(activeInput.value);
  const { firstValue, operator } = keypadOperation;
  let result;

  if (operator === '+') result = firstValue + secondValue;
  if (operator === '-') result = firstValue - secondValue;
  if (operator === '*') result = firstValue * secondValue;
  if (operator === '/') result = secondValue === 0 ? NaN : firstValue / secondValue;

  if (!Number.isFinite(result) || result < 0) {
    showStatus('That keypad calculation is not valid.');
    keypadOperation = null;
    return false;
  }

  activeInput.value = activeInput === quantityInput
    ? String(Math.floor(result))
    : String(Math.round(result * 100) / 100);
  keypadOperation = null;
  startNewNumber = true;
  handleAmountInput(activeInput);
  return true;
}

document.querySelectorAll('.keypad button').forEach((button) => {
  button.addEventListener('click', () => {
    const key = button.dataset.key;

    if (['+', '-', '*', '/'].includes(key)) {
      applyKeypadOperator(key);
      return;
    }

    if (key === 'ENTER') {
      if (evaluateKeypadOperation()) calculateChange();
      return;
    }

    if (key === '.' && (activeInput === quantityInput || (!startNewNumber && activeInput.value.includes('.')))) {
      return;
    }

    if (startNewNumber) {
      activeInput.value = key === '.' ? '0.' : key;
      startNewNumber = false;
    } else {
      activeInput.value += key;
    }
    handleAmountInput(activeInput);
    setActiveInput(activeInput);
  });
});

function displayCalculation(result, includeChange = false) {
  const fieldsToDisplay = includeChange ? resultFields : resultFields.filter((id) => id !== 'change');

  fieldsToDisplay.forEach((id) => {
    const value = result[id];
    document.getElementById(id).value = value === null
      ? ''
      : id === 'totalQuantity' ? String(value) : peso(value);
  });
}

async function requestCalculation(endpoint) {
  if (keypadOperation && !startNewNumber && !evaluateKeypadOperation()) return;

  if (!document.getElementById('rawPrice').value) {
    showStatus('Select a product first.');
    return null;
  }

  showStatus('Calculating...');

  try {
    const response = await fetch(endpoint, {
      method: 'POST',
      body: new FormData(orderForm),
      headers: { Accept: 'application/json' }
    });
    const result = await response.json();

    if (!response.ok) throw new Error(result.error || 'The calculation could not be completed.');

    return result;
  } catch (error) {
    showStatus(error.message);
    return null;
  }
}

async function calculatePay() {
  const result = await requestCalculation('calculate_pay.php');
  if (!result) return;

  displayCalculation(result);
  showStatus('');
}

async function calculateChange() {
  const calculateButton = document.getElementById('calculateButton');
  calculateButton.disabled = true;

  try {
    const result = await requestCalculation(orderForm.action);
    if (!result) return;

    displayCalculation(result, true);
    if (result.change < 0) {
      showStatus(`Cash is short by ${peso(Math.abs(result.change))}.`);
    } else {
      showStatus('');
    }
  } finally {
    calculateButton.disabled = false;
  }
}

orderForm.addEventListener('submit', (event) => {
  event.preventDefault();
  calculateChange();
});

document.getElementById('calculateButton').addEventListener('click', calculateChange);

document.querySelectorAll('input[name="discount"]').forEach((radio) => {
  radio.addEventListener('change', () => {
    clearResults();
    if (document.getElementById('rawPrice').value && quantityInput.value) calculatePay();
  });
});

document.getElementById('newButton').addEventListener('click', () => {
  orderForm.reset();
  document.querySelector('input[name="discount"][value="0"]').checked = true;
  document.querySelectorAll('.pic_option').forEach((product) => product.classList.remove('selected'));
  document.getElementById('itemName').value = '';
  document.getElementById('price').value = '';
  document.getElementById('rawPrice').value = '';
  clearResults();
  keypadOperation = null;
  startNewNumber = false;
  setActiveInput(quantityInput);
});
