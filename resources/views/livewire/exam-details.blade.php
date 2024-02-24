<div  wire:poll.60s>
    <div class=" p-2 rounded-lg shadow-md" x-data="timeProgress" x-init="initData">
        @if ($timeLeftToStart)
            <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                    <div>
                        {{-- <span class="text-xs font-semibold inline-block p-2  uppercase rounded-full text-primary-600 bg-primary-200">
                  Exam start after {{$quiz->timeLeftToStart()}}
                </span> --}}
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold inline-block text-primary-600">
                            Exam start after {{ $timeLeftToStart }}
                        </span>
                    </div>
                </div>
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-primary-200">
                    <div style="width: {{$timeLeftToStartProgress}}%;"
                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary-500">
                    </div>
                </div>
            </div>
        @endif
        @if (!$examHasStarted)
            @if ($lastExamAttempt && ($quiz->is_answers_visible || $quiz->test_type == 'in_time'))
                <div>
                    @if ($lastExamAttempt->isSuccessfulAttempt())
                        <h1 class="text-4xl font-bold text-green-500 mb-4">Congratulations!</h1>
                    @else
                        <h1 class="text-4xl font-bold text-red-500 mb-4">Failed!</h1>
                    @endif
                    <p class="text-white bg-green-600 p-4 text-xl">You have achieved a score of <span
                            class="font-bold text-4xl pl-4">{{ $lastExamAttempt->score ?? 0 }}%</span>.</p>
                </div>
            @endif
            <hr class="separator">
            @if ($attemptExpired || $quiz->isExpired())
                <div class="bg-red-600 p-4">Exam attempt has been expired.</div>
            @else
                @if ($leftAttempts > 0)
                    <div class="bg-green-600 p-4">You have <strong>{{ $leftAttempts }}</strong> attempts left.</div>
                @else
                    <div class="bg-red-600 p-4">You have tried all your attempts.</div>
                @endif
            @endif
            <hr class="separator">
            @livewire('components.quiz-details-card', ['quiz' => $quiz, 'isExamPage' => true])
            @if (((!$quiz->isExpired() && $leftAttempts > 0) || $resumeExam )&& !$timeLeftToStart)
                <hr class="separator">

                <form wire:submit="beginExam">

                    <button  class="subscribe-btn" type="submit">{{ $startExamBtnContent }}</button>
                </form>
            @endif
            @if ($lastExamAttempt && ($quiz->is_answers_visible || $quiz->test_type == 'in_time'))
                <hr class="separator">
                @livewire('components.exam-results', ['exam' => $currentExam])
                <div class="-mb-5 text-lg underline">Exam correct answers</div>
                @foreach ($quiz->questions as $question)
                    @livewire('components.exam-question-card', ['question' => $question])
                @endforeach
            @endif
        @else
            <div class=" mx-2   rounded-md shadow-md">
                <div x-show="showResult" class="bg-gray-600 h-screen flex items-center justify-center">
                    @if ($quiz->test_type == 'in_time')
                        <div class="bg-yellow-500 p-8 rounded shadow-md max-w-lg">
                            @if ($lastExamAttempt && $lastExamAttempt->isSuccessfulAttempt())
                                <h1 class="text-4xl font-bold text-green-500 mb-4">Congratulations!</h1>
                            @else
                                <h1 class="text-4xl font-bold text-red-500 mb-4">Failed!</h1>
                            @endif
                            <p class="text-gray-700 mb-6">You have achieved a score of <span
                                    class="font-bold text-2xl text-blue-500">{{ $lastExamAttempt->score ?? 0 }}%</span>.
                            </p>
                            {{-- @livewire('components.exam-results', ['exam' => $currentExam]) --}}
                            <div class="flex justify-center gap-3">
                                <button wire:click="retryExam"
                                    class= "w-full max-w-md bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Show exam details
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-500 p-8 rounded shadow-md max-w-lg">
                            <p class="text-gray-700 mb-6">We will send the result to You soon.</p>
                            <div class="flex justify-center gap-3">
                                <button wire:click="retryExam"
                                    class= "w-full max-w-md bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Return
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                <div x-show="!showResult">
                    <div class="mb-2 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Question {{ $questionNumber }}/{{ $totalQuestions }}: </h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm">Time Remaining:</span>
                            <div class="relative w-32 h-3 bg-gray-200 rounded-full">
                                <div class="absolute h-full bg-primary-500 rounded-full"
                                    x-bind:style="'width:' + progressVal + '%'"></div>
                                <div class="mt-4 text-center text-green-400"><strong
                                        x-text="leftTimeInminutes"></strong> minutes
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">{{ $currentQuestion->question }}?</h2>
                    <div>
                        @foreach ($choices as $choice)
                            <label class="flex items-center mb-2">
                                <input wire:model="selectedChoice" type="radio" name="answer"
                                    class="mr-2 text-primary-500 border-gray-300 focus:ring-primary-500"
                                    value="{{ $choice->id }}">
                                <span class="text-sm">{{ $choice->description }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        @if ($examFinished)
                            <button wire:click="nextQuestion" class="subscribe-btn">Show Result</button>
                        @else
                            <button wire:click="nextQuestion" class="subscribe-btn">Submit</button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        {{-- <div class="flex flex-wrap">
            @php
            for ($i = 1; $i <= 20; $i++) {
                $bgColor = $i % 2 === 0 ? 'bg-green-500' : 'bg-red-500';
                echo '<button class="border p-2 m-2 h-12 w-12 flex items-center justify-center text-white ' . $bgColor . '">' . $i . '</button>';
            }
            @endphp
        </div> --}}
    </div>
    @push('scripts')
        <script>
            let step = @json($step);
            let leftTime = @json($leftTime);
            let timer;
            document.addEventListener('alpine:init', () => {
                Alpine.data('timeProgress', () => ({
                    progress: 0,
                    leftTime: leftTime,
                    showResult: false,
                    get leftTimeInminutes() {
                        const val = Math.round(this.leftTime / 60)
                        return val < 1 ? 0 : val;
                    },
                    get progressVal() {
                        const val = parseInt(Math.round(this.progress));
                        return val >= 100 ? 100 : val;
                    },
                    runProgress() {
                        timer = setInterval(() => {
                            console.log("Progress");
                            if (this.progressVal >= 100) {
                                clearInterval(timer);
                            } else {
                                this.progress = this.progress + step;
                                this.leftTime--;
                            }
                        }, 1000);
                    },
                    updateProgress(progress = 0) {
                        if (timer) {
                            clearInterval(timer);
                        }
                        this.progress = progress;
                        this.runProgress();
                    },
                    setLeftTime(leftTime) {
                        this.leftTime = leftTime;
                    },
                    initData() {
                        // Alpine.store('loader').showLoader();
                        this.progress = @json($passedTimeProgress);
                        this.runProgress();
                        Livewire.on('examCompleted', () => {
                            this.showResult = true;
                            clearInterval(timer);
                            console.log('Exam Completed');
                        });
                        Livewire.on('examProgressUpdated', (event) => {
                            console.log('examProgressUpdated');
                            this.updateProgress(event.progress);
                            this.setLeftTime(event.leftTime)
                        });
                    }
                }));
            });
        </script>
    @endpush
</div>
