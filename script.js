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
