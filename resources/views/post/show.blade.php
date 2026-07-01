<x-app-layout>

    <div class="py-4">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white o verflow-hidden shadow-sm sm:rounded-lg p-8">
                <h1 class="text-2xl mb-4">{{ $post->title }}</h1>
                <!-- User avatar -->
                <div class="flex gap-4">
                    @if ($post->user->image)
                        <img src="{{ $post->user->imageUrl() }}" alt="{{ 
                        $post->user->name }}" class="w-12 h-12 rounded-full">
                    @else
                        <img src="https://static.everypixel.com/ep-
                        pixabay/0329/8099/0858/84037/3298099085884037069-head.png" 
                        alt="Dummy avatar" class="w-12 h-12 rounded-full">
                    @endif

                    <div>
                        
                        <x-follow-ctr :user="$post->user" class="flex gap-2">
                            <a href="{{ route('profile.show', $post->user) }}" 
                                alt="{{ $post->user->name }}" class="hover:underline">{{ $post->user->name }}</a>
                            &middot;
                            
                            @auth
                            @if (Auth::id() !== $post->user_id)
                                <button
                                x-text="following ? 'Unfollow' : 'Follow'"
                                :class="following ? 'text-red-600' : 'text-emerald-600'"
                                @click="follow()"></button>
                            @endif
                            
                            @endauth
                        </x-follow-ctr>
                        
                        <div class="flex gap-2 text-sm text-gray-500">
                            {{ $post->readTime() }} min read
                            &middot;
                            {{ $post->getPublishedAt() }}
                        </div>
                    </div>
                </div>
                <!-- User avatar -->
                @if ($post->user_id == Auth::id())
                    <div class="py-4 mt-8 border-t border-b border-gray-200">
                        <x-primary-button href="{{ route('post.edit', $post->slug) }}">
                            Edit Post
                        </x-primary-button>
                        
                        <form class="inline-block" action="{{ route('post.destroy', $post) }}" method="POST">
                            @csrf
                            @method('delete')
                            <x-danger-button>
                                Delete Post
                            </x-danger-button>
                        </form>
                    </div>  
                @endif
                
                <!-- Clap Section -->
                <x-clap-button :post="$post" />
                <!-- Clap Section -->

                <!-- Content Section -->
                <div class="mt-4">
                    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}"
                    class="w-full">

                    <div class="mt-4">
                        {{ $post->content }}
                    </div>
                </div>
                <!-- Content Section -->
                <div class="mt-8 mb-4">
                    <a href="{{ route('post.byCategory', $post->category) }}">
                        <span class="px-4 py-2 bg-gray-300 rounded-2xl">
                            {{ $post->category->name }}
                        </span>
                    </a>
                </div>

                <!-- Clap Section -->
                <x-clap-button :post="$post" />
            </div>

            
        </div>
    </div>
</x-app-layout>
