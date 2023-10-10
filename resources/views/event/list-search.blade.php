@push('css')
    <link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endpush

<x-app-layout>
    <x-slot name="header">
        <script src="{{ asset('script/category.js') }}"></script>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Search') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="sm:flex sm:items-center sm:ml-6">
                    <form action="{{ route('index.search') }}" method="GET" >

                    <x-input-label for="category" :value="__('Select A Category')"/>
                    <select name="category" id="category"  onchange="submit(this.form)" class="flex rounded-md shadow-sm"> 
                        <option value= "All Categories">{{__('All Categories')}}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex rounded-md shadow-sm mt-6">
                        <input type="text" name="keyword" value="{{$keyword}}" placeholder={{__('Keyword Search')}} id="hs-leading-button-add-on-with-icon"  class="py-3 px-4 block border-gray-200 shadow-sm rounded-l-md text-sm focus:z-10 focus:border-gray-500 focus:ring-gray-500">
                        <button type="submit" class="inline-flex flex-shrink-0 justify-center items-center h-[2.875rem] w-[2.875rem] rounded-r-md border border-transparent font-semibold bg-gray-500 text-white hover:bg-gray-600 focus:z-10 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all text-sm">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </button>
                    </div>
                    </form>
                </div>
            </div>
            
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @if ($events->isEmpty())
                <p>{{ __('No events found.') }}</p>
                @else
                <div id="card-layout-wrap">
                    @foreach ($events as $event)
                    <section class="card-list">
                        <a class="card-link" href="#">
                            <ul>
                                <li>
                                    <a href="{{ route('event.show', $event->id) }}">
                                        <h4 class="card-title">{{ $event->name }}</h4>
                                        <figure class="card-figure">
                                            <img class="event_image mx-auto" src="{{ Storage::url($event->image_path) }}" alt="">
                                        </figure>
                                        <p class="card-text-tax">
                                        {{ Carbon\Carbon::parse($event->date)->format('Y/m/d') }}
                                        {{ $event->detail }}</p>
                                    </a>
                                </li>
                            </ul>
                        </a>
                    </section>
                    @endforeach
                </div>
                @endif
                <br />
                {{ $events->appends(request()->query())->links()}}
            </div>
        </div>
    </div>
</x-app-layout>