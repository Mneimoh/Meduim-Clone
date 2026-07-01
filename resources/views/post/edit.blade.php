<x-app-layout>

    <div class="py-4">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl mb-4">Update Post: <strong class="font-bold">{{ $post->title }}</strong></h1>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form method="POST" action="{{ route('post.update', $post->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                     <!-- Image -->

                    @if (/*$user->image*/ /*$user->getFirstMedia()*/ $post->imageUrl())  
                        <div class="mb-8">
                            <img src="{{ /*Storage::url($user->image)*/ $post->imageUrl() }}" 
                            alt="{{ $post->title }}" class="w-full">
                        </div>
                    @endif

                    <div>
                        <x-input-label for="image" :value="__('Image')" />
                        <x-text-input id="image" name="image" type="file" :value="old('image')" class="cursor-pointer 
                        bg-neutral-secondary-medium border border-default-medium text-heading text-sm 
                        rounded-base focus:ring-brand focus:border-brand block w-full shadow-xs placeholder:text-body"
                        />
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <!-- Title -->
                    <div class="mt-4">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" :value="old('title', $post->title)" 
                        class="block mt-1 w-full" type="text" name="title" autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    
                    <!-- Categories -->
                    <div class="mt-4">
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select id="category_id" name="category_id" class="border-gray-300 
                        focus:border-indigo-500 rounded-md showdaw-sm block mt-1 w-full">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                    @selected(old('category_id', $post->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    

                    <!-- Content -->
                    <div class="mt-4">
                        <x-input-label for="content" :value="__('Content')" />

                        <x-input-textarea rows="10" cols="50" id="content" name="content" class="block mt-1 w-full">
                            {{ old('content', $post->content) }}
                        </x-input-textarea>

                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>

                    <!-- Published At -->
                    <div class="mt-4">
                        <x-input-label for="published_at" :value="__('Published At')" />
                        <x-text-input id="published_at" :value="old('published_at', $post->published_at)" 
                        class="block mt-1 w-full" type="datetime-local" name="published_at" autofocus />
                        <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                    </div>
                    
                    <x-primary-button class="mt-4">
                        Submit
                    </x-primary-button>
                   
                </form>
            
            </div>
            
        </div>
    </div>
</x-app-layout>
