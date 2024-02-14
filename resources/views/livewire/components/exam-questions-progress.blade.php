<div class=" mx-2   rounded-md shadow-md" x-data="timeProgress" x-init="incrementProgress">
    <!-- Question and Progress Bar -->
    <div class="mb-2 flex justify-between items-center">
        <h3 class="text-lg font-semibold">Question {{ $questionNumber }}/{{ $totalQuestions }}: </h3>
        <div class="flex items-center space-x-2">
            <span class="text-sm">Time Remaining:</span>
            <div class="relative w-32 h-3 bg-gray-200 rounded-full">
                <div class="absolute h-full bg-primary-500 rounded-full" x-bind:style="'width:' + progress + '%'"></div>
            </div>
        </div>
    </div>
    <h2 class="text-2xl font-bold mb-2">{{ $currentQuestion->question }}?</h2>
    <!-- Answer Options -->
    <div>
        @foreach ($choices as $choice)
            <label class="flex items-center mb-2">
                <input wire:model="selectedChoice" type="radio" name="answer"
                    class="mr-2 text-primary-500 border-gray-300 focus:ring-primary-500" value="{{ $choice->id }}">
                <span class="text-sm">{{ $choice->description }}</span>
            </label>
        @endforeach
        {{-- <span class="text-sm font-bold text-green-600">Correct Answer - Madrid</span> --}}

    </div>
    <!-- Submit Button -->
    <div class="mt-4">
        @if ($examFinished)
            <button wire:click="nextQuestion" class="subscribe-btn">Show Result</button>
        @else
            <button wire:click="nextQuestion" class="subscribe-btn">Submit</button>
        @endif
    </div>
    <script>
        console.log('================================');
        let step = @json($step);

        function incrementProgress() {
            this.progress = @json($passedTimeProgress);
            setInterval(() => {
                this.progress = this.progress + step;
            }, 1000)
        }
        document.addEventListener('alpine:init', () => {
            Alpine.data('timeProgress', () => ({
                progress: step,
                incrementProgress: incrementProgress
            }));
        });
    </script>
</div>
