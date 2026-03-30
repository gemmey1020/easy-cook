@props(['id' => '', 'active' => false, 'class' => ''])

<div
    class="tab-pane fade h-100 {{ $active ? 'show active' : '' }} {{ $class }}"
    id="{{ $id }}"
    {{ $attributes }}
>
    <div class="p-4 h-100 d-flex flex-column">
        @if($header ?? null)
            <div {{ $header->attributes->class('mb-4') }}>
                {{ $header }}
            </div>
        @endif

        {{ $slot }}

        @if($footer ?? null)
            <div {{ $footer->attributes->class('mt-auto pt-3') }}>
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
