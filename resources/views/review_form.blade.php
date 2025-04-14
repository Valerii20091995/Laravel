@if($product)
    <h1>{{$product->name}}</h1>
    <img class="card-img-top" src="{{$product->image_url}}" alt="Card image">
    <p>{{$product->price}}</p>
    <p>{{$product->description}}</p>

    <h2>Отзывы</h2>
    @if($reviews->isEmpty())
        <p>На данный момент нет отзывов, напишите первым и поставьте оценку продукту!</p>
    @else
        <p class="average-rating">Средняя оценка продукта по отзывам: {{$averageRating}}</p>
        @foreach($reviews as $review)
            <div class="review">
                <p><strong>{{$review->author}}</strong> (Оценка: {{$review->rating}}/5)</p>
                <p>{{$review->product_review}}</p>
                <p><em>{{$review->created_at->format('d.m.Y H:i')}}</em></p>
            </div>
        @endforeach
    @endif

    <h3>Оставить отзыв</h3>
    @auth
        <form action="{{route('review.store',['product'=> $product->id])}}" method="POST">
            @csrf
            <label for="rating">Оценка (1-5):</label>
            @error('rating')
            <label for="rating"><b>{{ $message }}</b></label>
            @enderror
            <input type="number" name="rating" min="1" max="5" required><br>

            <label for="product_review">Ваш отзыв:</label><br>
            <textarea name="product_review" required></textarea><br>
            @error('product_review')
            <label for="product_review"><b>{{ $message }}</b></label>
            @enderror
            <button type="submit">Отправить отзыв</button>
        </form>
    @else
        <p>Чтобы оставить отзыв, пожалуйста, <a href="{{ route('login') }}">войдите</a> в систему.</p>
    @endauth

@else
    <p>Продукт не найден.</p>
@endif
