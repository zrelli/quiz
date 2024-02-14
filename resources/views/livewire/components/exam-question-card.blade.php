<!-- resources/views/components/question-component.blade.php -->

<div class="mb-6 mt-2">
    <hr class="separator">
    <h2 class="text-2xl font-bold mb-2">{{ $question->question }}?</h2>
    <div>
        @foreach ($question->choices as $choice)
            <label class="{{ 'flex items-center mb-2 ' . ($choice->is_correct ? 'text-green-500' : '') }}">
                <input wire:model="selectedChoice" type="radio" name="{{ 'answer' . $question->id }}"
                    class="mr-2 text-primary-500 border-gray-300 focus:ring-primary-500" value="{{ $choice->id }}"
                    @if ($choice->is_correct) checked @endif>
                <span class="text-sm">{{ $choice->description }}</span>
            </label>
            <strong>{{ $choice->explanation }}</strong>
        @endforeach
    </div>
</div>
