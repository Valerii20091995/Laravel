<div class="container">
    <nav class="navbar">

        <a href="{{route('profile')}}" class="navbar-link">Мой профиль</a>
        <a href="/cart" class="navbar-link">Корзина <span class="total-count">0</span></a>
        <a href="/orders" class="navbar-link">Мои заказы</a>
        <a href="{{route('logout')}}" class="navbar-link">выйти из профиля</a>

    </nav>
    <h3 class="catalog-title">Catalog</h3>
    <div class="card-deck">
        @foreach($products as $product)
        <div class="card text-center">
            <a href="#">
                <div class="card-header">
                    <span class="badge badge-success">Hit!</span>
                </div>
                <img class="card-img-top" src="{{$product->image_url}}" alt="Card image">
                <div class="card-body">
                    <p class="product-name">{{$product->name}}</p>
                    <p class="product-description">{{$product->description}}</p>
                </div>
                <div class="card-footer">
                    <p class="price">{{$product->price}}</p>
                </div>
            </a>
        </div>

        <form  class="add-product-form" onsubmit="return false" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{$product->id}}">
            <input type="hidden" name="amount" value="1">
            <button type="submit" class="add-product-btn">+</button>
        </form>
        <form class="decrease-product-form" method="POST"  onsubmit="return false">
            @csrf
            <input type="hidden" name="product_id" value="{{$product->id}}">
            <button type="submit"  class="remove-product-btn">-</button>
        </form>
        <form action="{{route('product.show',['product' => $product->id])}}" method="GET" class="product-form">
            @csrf
            <input type="hidden" name="product_id" value="{{$product->id}}" id="product_id" required>
            <button type="submit"  class="remove-product-btn">отзывы о продукте</button>
        </form>
        @endforeach
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $("document").ready(function () {
        // var form = $('.product-form');
        $('.add-product-form').submit(function () {
            var form = $(this);
            $.ajax({
                type: "POST",
                url: "{{route('addProduct')}}",
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('.product-quantity').text(response.amount + ' шт')
                },
                error: function(xhr, status, error) {
                    console.error('Ошибка при добавлении товара:', error);
                }
            });
        });
        $('.decrease-product-form').submit(function () {
            var form = $(this);
            $.ajax({
                type: "POST",
                url: "{{route('decreaseProduct')}}",
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {

                    console.log('response')
                    $('.product-quantity').text(response.amount + ' шт')
                    if (response.amount <= 0) {
                        $form.hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ошибка при удалении товара:', error);
                }
            });
        });
    });
</script>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(135deg, #f3a683, #f7b7a3);
        margin: 0;
        padding: 0;
        color: #333;
    }

    .navbar {
        display: flex;
        justify-content: center;
        margin: 20px 0;
        background-color: #fff;
        padding: 10px 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .navbar-link {
        text-decoration: none;
        color: #ff6347;
        margin: 0 15px;
        font-size: 1.2rem;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    .navbar-link:hover {
        color: #d25e48;
    }

    .catalog-title {
        text-align: center;
        font-size: 2.5rem;
        margin-top: 20px;
        color: #fff;
    }

    .card-deck {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin: 30px 0;
    }

    .card {
        width: 250px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        text-align: center;
        margin-bottom: 20px;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-header {
        background-color: #ff6347;
        padding: 10px;
        color: white;
        font-weight: bold;
    }

    .badge {
        background-color: #28a745;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.9rem;
    }

    .card-body {
        padding: 15px;
    }

    .product-name {
        font-size: 1.2rem;
        font-weight: bold;
        margin: 10px 0;
    }

    .product-description {
        font-size: 1rem;
        color: #777;
        margin-bottom: 10px;
    }

    .card-footer {
        background-color: #f8f9fa;
        padding: 10px;
    }

    .price {
        font-size: 1.2rem;
        font-weight: bold;
        color: #ff6347;
    }

    .product-form {
        text-align: center;
        margin-top: 20px;
    }

    .product-quantity {
        font-size: 1rem;
        color: #333;
        margin: 10px 0;
    }

    .product-btn {
        padding: 10px 20px;
        background-color: #ff6347;
        color: white;
        border: none;
        font-size: 1.5rem;
        font-weight: bold;
        border-radius: 50%;
        cursor: pointer;
        width: 50px;
        height: 50px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .product-btn:hover {
        background-color: #d25e48;
        transform: scale(1.1);
    }

    .product-btn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .remove-product-btn {
        background-color: #ff6347;
        border-radius: 50%;
        font-size: 1.5rem;
    }

    .add-product-btn {
        background-color: #28a745;
        border-radius: 50%;
        font-size: 1.5rem;
    }

    @media (max-width: 768px) {
        .card-deck {
            flex-direction: column;
            align-items: center;
        }

        .card {
            width: 100%;
            max-width: 350px;
        }
    }

</style>
