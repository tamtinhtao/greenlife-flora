<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Mua nhanh - GreenLife Flora</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eef8ee;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .buy-box {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 12px 35px rgba(0,0,0,.12);
        }

        h2 {
            text-align: center;
            color: #198754;
            margin-top: 0;
        }

        .product {
            display: flex;
            gap: 20px;
            align-items: center;
            margin: 25px 0;
        }

        .product img {
            width: 180px;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
        }

        .info {
            flex: 1;
        }

        .name {
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .price {
            color: #dc3545;
            font-size: 22px;
            font-weight: bold;
        }

        .quantity-title {
            margin-top: 20px;
            font-weight: bold;
        }

        .quantity-box {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 15px 0 25px;
        }

        .quantity-box button {
            width: 45px;
            height: 45px;
            border: none;
            background: #198754;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .quantity-box input {
            width: 70px;
            height: 45px;
            text-align: center;
            border: 1px solid #ccc;
            font-size: 18px;
        }

        .total {
            text-align: center;
            font-size: 20px;
            margin-bottom: 25px;
        }

        .total span {
            color: #dc3545;
            font-weight: bold;
        }

        .buy-button {
            display: block;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #198754;
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #666;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="buy-box">

    <h2>🛒 Phiếu mua sản phẩm</h2>

    <div class="product">

        @if($product->image)

            <img
                src="{{ asset($product->image) }}"
                alt="{{ $product->name }}"
            >

        @endif

        <div class="info">

            <div class="name">
                {{ $product->name }}
            </div>

            <div class="price">
                {{ number_format($product->price) }} đ
            </div>

        </div>

    </div>


    <form
        action="{{ route('cart.buyNow.confirm', $product->id) }}"
        method="POST"
    >

        @csrf

        <div class="quantity-title">
            Số lượng:
        </div>

        <div class="quantity-box">

            <button
                type="button"
                onclick="decreaseQuantity()"
            >
                −
            </button>

            <input
                type="number"
                id="quantity"
                name="quantity"
                value="1"
                min="1"
                max="99"
                data-price="{{ $product->price }}"
                readonly
            >

            <button
                type="button"
                onclick="increaseQuantity()"
            >
                +
            </button>

        </div>


        <div class="total">

            Thành tiền:

            <span id="total">
                {{ number_format($product->price) }} đ
            </span>

        </div>


        <button
            type="submit"
            class="buy-button"
        >
            ⚡ Mua ngay
        </button>

    </form>


    <a
        href="{{ route('home') }}"
        class="back"
    >
        ← Tiếp tục xem sản phẩm
    </a>

</div>


<script>

    const quantityInput =
    document.getElementById('quantity');

const totalElement =
    document.getElementById('total');

const price =
    Number(quantityInput.dataset.price);


    function updateTotal()
    {
        const quantity =
            parseInt(quantityInput.value);

        const total =
            price * quantity;

        totalElement.innerText =
            total.toLocaleString('vi-VN') + ' đ';
    }


    function increaseQuantity()
    {
        let quantity =
            parseInt(quantityInput.value);

        if (quantity < 99) {
            quantityInput.value = quantity + 1;
        }

        updateTotal();
    }


    function decreaseQuantity()
    {
        let quantity =
            parseInt(quantityInput.value);

        if (quantity > 1) {
            quantityInput.value = quantity - 1;
        }

        updateTotal();
    }

</script>

</body>
</html>