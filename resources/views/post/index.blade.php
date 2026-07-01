<x-app-layout>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900">

                    <x-category-tabs>
                        No Categories
                    </x-category-tabs>

                </div>
            </div>

            <div class="p-4 text-gray-900">

                <!--foreach ($posts as $post)-->
                @forelse ($posts as $post)

                    <x-post-item :post="$post"></x-post-item>
                
                <!--endforeach-->
                @empty
                    <div class="text-center text-gray-400 py-16">No posts Found</div>
                @endforelse
            </div>
            <!-- $posts->onEachSide(1)->links() -->
            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>
