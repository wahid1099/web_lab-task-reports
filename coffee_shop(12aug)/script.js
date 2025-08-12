const container = document.getElementById("product-container");
const cartCount = document.getElementById("cart-count");
let totalCartItems = 0;

products.forEach((product) => {
  const card = document.createElement("div");
  card.className = "product-card";

  const priceAfterDiscount = product.discount
    ? (product.price * (1 - product.discount / 100)).toFixed(2)
    : product.price;

  card.innerHTML = `
          <div class="discount-badge" style="${
            product.discount ? "" : "display: none;"
          }">
            ${product.discount || 0}% OFF
          </div>
          <img src="${product.image}" alt="${product.name}" />
          <h3>${product.name}</h3>
          <p class="price">
            ${
              product.discount
                ? `<span class="old-price">${product.price}tk</span> <span class="new-price">${priceAfterDiscount}tk</span>`
                : `${product.price}tk`
            }
          </p>
          <div class="cart-controls">
            <button class="minus">-</button>
            <span class="quantity">0</span>
            <button class="plus">+</button>
            <button class="add-to-cart">Add to Cart</button>
          </div>
        `;

  container.appendChild(card);

  let quantity = 0;
  const minusBtn = card.querySelector(".minus");
  const plusBtn = card.querySelector(".plus");
  const qtyDisplay = card.querySelector(".quantity");
  const addToCartBtn = card.querySelector(".add-to-cart");

  plusBtn.onclick = () => {
    quantity++;
    qtyDisplay.innerText = quantity;
  };

  minusBtn.onclick = () => {
    if (quantity > 0) {
      quantity--;
      qtyDisplay.innerText = quantity;
    }
  };

  addToCartBtn.onclick = () => {
    totalCartItems += quantity;
    cartCount.innerText = totalCartItems;
    quantity = 0;
    qtyDisplay.innerText = 0;
  };
});
