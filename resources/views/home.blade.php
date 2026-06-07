<!DOCTYPE html>
<html>

<head>
    <title>Food Paradise</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            background: #f7f7f7;
            overflow-x: hidden;
        }

        /* LEFT SIDEBAR */

        .category-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: white;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            z-index: 1000;
            padding: 20px;
        }

        /* RIGHT SIDEBAR */

        .cart-sidebar {
            position: fixed;
            right: 0;
            top: 0;
            width: 350px;
            height: 100vh;
            background: white;
            border-left: 1px solid #ddd;
            overflow-y: auto;
            z-index: 1000;
            padding: 20px;
        }

        /* CENTER CONTENT */

        .content-area {
            margin-left: 270px;
            margin-right: 370px;
            padding: 20px;
        }

        .category-item {
            padding: 10px;
            margin-bottom: 5px;
            cursor: pointer;
            border-radius: 8px;
        }

        .category-item:hover {
            background: #eee;
        }

        .category-item.active {
            background: #6f42c1;
            color: white;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
            height: 100%;
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .product-body {
            padding: 15px;
        }

        .cart-item {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .qty-btn {
            width: 30px;
            height: 30px;
            border: none;
            background: #6f42c1;
            color: white;
            border-radius: 5px;
        }

        .empty-cart {
            text-align: center;
            padding: 50px 0;
            color: #888;
        }

        @media(max-width:1200px) {

            .category-sidebar,
            .cart-sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content-area {
                margin: 0;
            }

        }
    </style>
</head>

<body>

    <!-- CATEGORY SIDEBAR -->

    <div class="category-sidebar">

        <h4 class="mb-4">Categories</h4>

        <div class="category-item active" data-category="all">

            All Items

        </div>

        @foreach ($categories as $category)
            <div class="category-item" data-category="{{ $category->id }}">

                {{ $category->name }}

            </div>
        @endforeach

    </div>

    <!-- PRODUCTS -->

    <div class="content-area">

        <div class="row g-4">

            @foreach ($items as $item)
                @php
                    $lowestPrice = $item->sizes->min('price');
                @endphp

                <div class="col-md-4 product-wrapper" data-category="{{ $item->category_id }}">

                    <div class="product-card">

                        <img src="{{ asset('chef_hat.jpg') }}" alt="Food">

                        <div class="product-body">

                            <h6>{{ $item->name }}</h6>

                            <div class="text-muted mb-2">
                                Starting ₹{{ number_format($lowestPrice, 2) }}
                            </div>

                            <select class="form-select mb-3 size-selector" data-item-id="{{ $item->id }}">

                                @foreach ($item->sizes as $size)
                                    <option value="{{ $size->id }}" data-price="{{ $size->price }}">

                                        {{ $size->size ?: 'Regular' }}
                                        -
                                        ₹{{ number_format($size->price, 2) }}

                                    </option>
                                @endforeach

                            </select>

                            <button class="btn btn-danger w-100" onclick="addToCart({{ $item->id }})">

                                Add To Cart

                            </button>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

    <!-- CART -->

    <div class="cart-sidebar">

        <h4>Order Summary</h4>

        <hr>

        <div id="cartContainer">

            <div class="empty-cart">

                Cart Empty

            </div>

        </div>

        <hr>

        <div class="d-flex justify-content-between fw-bold">

            <span>Total</span>

            <span id="cartTotal">
                ₹0.00
            </span>

        </div>

        <button class="btn btn-success w-100 mt-3">
            Checkout
        </button>

    </div>

    <script>
        const products = @json(
            $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                ];
            }));

        let cart = {};

        // CATEGORY FILTER

        document
            .querySelectorAll('.category-item')
            .forEach(item => {

                item.addEventListener('click', function() {

                    document
                        .querySelectorAll('.category-item')
                        .forEach(x => x.classList.remove('active'));

                    this.classList.add('active');

                    let category =
                        this.dataset.category;

                    document
                        .querySelectorAll('.product-wrapper')
                        .forEach(product => {

                            if (
                                category === 'all' ||
                                product.dataset.category === category
                            ) {
                                product.style.display = '';
                            } else {
                                product.style.display = 'none';
                            }

                        });

                });

            });

        // ADD TO CART

        function addToCart(itemId) {
            let selector =
                document.querySelector(
                    `.size-selector[data-item-id="${itemId}"]`
                );

            let option =
                selector.options[
                    selector.selectedIndex
                ];

            let sizeId =
                option.value;

            let sizeName =
                option.text;

            let price =
                parseFloat(
                    option.dataset.price
                );

            let item =
                products.find(
                    p => p.id == itemId
                );

            let key =
                itemId + '_' + sizeId;

            if (cart[key]) {

                cart[key].qty++;

            } else {

                cart[key] = {

                    key: key,
                    itemId: itemId,
                    name: item.name,
                    sizeId: sizeId,
                    sizeName: sizeName,
                    price: price,
                    qty: 1

                };

            }

            renderCart();
        }

        // PLUS

        function increaseQty(key) {
            cart[key].qty++;

            renderCart();
        }

        // MINUS

        function decreaseQty(key) {
            cart[key].qty--;

            if (cart[key].qty <= 0) {

                delete cart[key];

            }

            renderCart();
        }

        // RENDER CART

        function renderCart() {
            let html = '';

            let total = 0;

            let items =
                Object.values(cart);

            if (items.length === 0) {

                document.getElementById(
                    'cartContainer'
                ).innerHTML = `
            <div class="empty-cart">
                Cart Empty
            </div>
        `;

                document.getElementById(
                    'cartTotal'
                ).innerText = '₹0.00';

                return;
            }

            items.forEach(item => {

                let rowTotal =
                    item.price * item.qty;

                total += rowTotal;

                html += `

            <div class="cart-item">

                <div class="fw-bold">
                    ${item.name}
                </div>

                <div class="small text-muted">
                    ${item.sizeName}
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">

                    <div>

                        <button
                            class="qty-btn"
                            onclick="decreaseQty('${item.key}')">

                            -

                        </button>

                        <span class="mx-2">
                            ${item.qty}
                        </span>

                        <button
                            class="qty-btn"
                            onclick="increaseQty('${item.key}')">

                            +

                        </button>

                    </div>

                    <div>

                        ₹${rowTotal.toFixed(2)}

                    </div>

                </div>

            </div>

        `;

            });

            document.getElementById(
                'cartContainer'
            ).innerHTML = html;

            document.getElementById(
                    'cartTotal'
                ).innerText =
                '₹' + total.toFixed(2);
        }
    </script>

</body>

</html>
