@section('title', $pageTitle)
<x-app-layout>
  <section class="space-y-6">
    <div class="text-center">
      <h1 class="text-[30px] mb-3">Tin Tức Mới</h1>
      <hr />
    </div>

    <div class="grid xl:grid-cols-3 grid-cols-1 gap-5">
      @foreach ($articles as $post)
        <div class="card">
          <div class=" h-[210px] w-full mb-2 ">
            <a href="{{ route('articles.show', ['slug' => $post->slug]) }}">
              <img data-src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" class="lazyload w-full h-full object-cover rounded-t-lg">
            </a>
          </div>
          <div class="px-2 pb-2 mb-3 text-start font-bold">
            <a href="{{ route('articles.show', ['slug' => $post->slug]) }}" class="text-lg">{{ $post->title }}</a>
          </div>
          <div class="px-5 flex pb-3 justify-between">
            <a href="#!" class="btn btn-sm btn-outline-danger">Ngày: {{ $post->created_at }}</a>
            <a href="{{ route('articles.show', ['slug' => $post->slug]) }}" class="btn btn-sm btn-primary">Xem Thêm <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-3">
      {{ $articles->links() }}
    </div>
  </section>
</x-app-layout>
