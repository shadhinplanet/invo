@props(['count','text','route'])
<div {{ $attributes }}>
    <a href="{{ $route }}" class="flex px-10 py-14 flex-col items-center">
        <h1 class="font-bold text-3xl">{{ $count }}</h1>
        <h2 class="text-emerald-900 font-black uppercase">{{ $text }}</h2>
    </a>
</div>
