@props(['user', 'size' => 'w-12 h-12'])


@if ($user->image)
    <img src="{{ $user->imageUrl() }}" alt="{{ 
    $user->name }}" class="{{ $size }} rounded-full">
@else
    <img src="https://static.everypixel.com/ep-pixabay/0329/8099/0858/84037/3298099085884037069-head.png" 
    alt="Dummy Avatar" class="{{ $size }} rounded-full">
@endif