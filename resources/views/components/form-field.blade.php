@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
    'colspan' => '',
    'icon' => null,
    'autocomplete' => null,
])

@php
    $current = old($name, $value);
    $hasError = $errors->has($name);
    $inputId = 'field-' . $name;
    $isPassword = $type === 'password';
    $inputClass = trim(
        'form-input'
        . ($icon ? ' pl-11' : '')
        . ($isPassword ? ' pr-12' : '')
        . ($hasError ? ' is-invalid' : '')
    );
@endphp

<div class="{{ $colspan }}">
    <label class="form-label" for="{{ $inputId }}">
        {{ $label }}
        @if ($required)
            <span class="text-error" aria-hidden="true">*</span>
        @endif
    </label>

    @if ($type === 'textarea')
        <textarea class="form-input h-32 py-3 {{ $hasError ? 'is-invalid' : '' }}"
                  id="{{ $inputId }}"
                  name="{{ $name }}"
                  placeholder="{{ $placeholder }}"
                  @if ($required) required @endif
                  @error($name) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @enderror>{{ $current }}</textarea>
    @elseif ($type === 'select')
        <select class="form-input {{ $hasError ? 'is-invalid' : '' }}"
                id="{{ $inputId }}"
                name="{{ $name }}"
                @if ($required) required @endif
                @error($name) aria-invalid="true" @enderror>
            <option value="">-- {{ $placeholder ?? 'Pilih' }} --</option>
            {{ $slot }}
        </select>
    @elseif ($type === 'checkbox')
        <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="checkbox"
                   class="w-5 h-5 rounded border-2 border-surface-outline-app text-primary focus:ring-2 focus:ring-primary/30"
                   id="{{ $inputId }}"
                   name="{{ $name }}"
                   value="1"
                   @if ($required) required @endif
                   @checked((bool) $current)>
            <span class="font-body-sm text-body-sm text-on-surface">{{ $slot ?: $label }}</span>
        </label>
    @else
        <div class="relative">
            @if ($icon)
                <span class="material-symbols-outlined pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[20px] {{ $hasError ? 'text-error' : 'text-outline' }}">{{ $icon }}</span>
            @endif

            <input class="{{ $inputClass }}"
                   type="{{ $type }}"
                   id="{{ $inputId }}"
                   name="{{ $name }}"
                   value="{{ $current }}"
                   placeholder="{{ $placeholder }}"
                   @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
                   @if ($required) required @endif
                   @error($name) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @enderror>

            @if ($isPassword)
                <button type="button"
                        class="absolute right-1.5 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-outline transition-colors hover:bg-surface-container-low hover:text-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/30"
                        data-password-toggle="{{ $inputId }}"
                        aria-label="Tampilkan password"
                        aria-controls="{{ $inputId }}">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </button>
            @endif
        </div>
    @endif

    @error($name)
        <p id="{{ $inputId }}-error" class="mt-1.5 flex items-center gap-1 font-body-sm text-body-sm text-error">
            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
        </p>
    @enderror

    @if ($hint && ! $hasError)
        <p class="mt-1.5 font-body-sm text-body-sm text-outline">{{ $hint }}</p>
    @endif
</div>