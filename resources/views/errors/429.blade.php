@include('errors.partials.themed', [
    'statusCode' => 429,
    'title' => 'Too many requests',
    'image' => 'images/reading.PNG',
])
