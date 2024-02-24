<div>
    <h2 class="text-xl font-bold mb-2">{{ $quiz->title }}</h2>
    <div class="fi-ta-text grid w-full gap-y-1 mb-2">
        <div class="flex ">
            <div class="flex max-w-max">
                <div class="fi-ta-text-item inline-flex items-center gap-1.5 fi-color-custom">
                    <svg class="fi-ta-text-item-icon h-5 w-5 text-custom-500" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="fi-ta-text-item-label text-sm leading-6 text-custom-600 dark:text-custom-400  ">
                        {{ $quiz->test_type == 'out_of_time' ? 'Out of time' : 'In time' }} ({{ $quiz->duration }}
                        hours)
                    </span>
                </div>
            </div>
        </div>
    </div>
    <p class=" mb-4">{{ $quiz->description }}</p>
    <div class="flex items-center text-gray-100 mb-2">
        <svg class="h-4 w-4  bbb" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM10 2a7 7 0 100 14 7 7 0 000-14zM10 4a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 100 6 3 3 0 000-6zm0 2a1 1 0 100 2 1 1 0 000-2z"
                clip-rule="evenodd"></path>
        </svg>
        <span>Start Date: {{ $quiz->started_at }}</span>
    </div>
    <div class="flex items-center text-gray-100">
        <svg class="h-4 w-4 bbb" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM10 2a7 7 0 100 14 7 7 0 000-14zM10 4a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 100 6 3 3 0 000-6zm0 2a1 1 0 100 2 1 1 0 000-2z"
                clip-rule="evenodd"></path>
        </svg>
        <span>Expiration Date: {{ $quiz->expired_at }}</span>
    </div>
    @if ($canShowFooterBtn)
        <form wire:submit="submit">
            <button class="subscribe-btn" type="submit">{{ $submitBtnContent }}</button>
        </form>
    @endif
</div>
