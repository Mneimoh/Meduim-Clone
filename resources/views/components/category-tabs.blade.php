<ul class="flex flex-wrap text-sm font-mediumtext-center text-gray-500 dark:text-gray-400 justify-center">
    <li class="me-2">
        <a href="/" 
        class="{{ request('category') 
            ? 'inline-block px-4 py-2 rounded-md hover:text-gray-900 
                hover:bg-gray-100' 
            : 'inline-block px-4 py-2 text-white bg-blue-600 rounded-lg 
            active'}}">
            All
        </a>
    </li>
    <!--foreach($categories as $category)-->
    @forelse ( $categories as $category )
        <li class="me-2">
            <a href="{{ route('post.byCategory', $category) }}" 
            class="{{ Route::currentRouteNamed('post.byCategory') &&
                request('category')->id == $category->id ? 'inline-block px-4 
                py-2 text-white bg-blue-600 rounded-lg active' 
                : 'inline-block px-4 py-2 rounded-md hover:text-gray-900 
                hover:bg-gray-100' }}">
                {{ $category->name }}
            </a>
        </li>
    <!--endforeach-->
    @empty
        {{ $slot }}
    @endforelse
    
</ul>