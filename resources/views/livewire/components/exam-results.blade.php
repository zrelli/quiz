<div>
    @php
        $totalTests = count($allExamStatistics);
    @endphp
    @if ($totalTests >= 1)
        <div class="pb-1 text-lg underline">Recent exam attempts results</div>
        <table class="min-w-full divide-y divide-gray-200 mb-4">
            <thead class="bg-blue-500">
                <tr>
                    <th class="px-6 py-3 text-white text-left text-xs leading-4 font-medium  uppercase tracking-wider">
                        Score
                    </th>
                    <th class="px-6 py-3 text-white text-left text-xs leading-4 font-medium  uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-6 py-3 text-white text-left text-xs leading-4 font-medium  uppercase tracking-wider">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach (collect($allExamStatistics)->reverse() as $examStatistics)
                    @if ($examStatistics->is_closed)
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    {{ $examStatistics->score }}%
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                <div class="text-sm leading-5 text-gray-900">
                                    {{ $examStatistics->created_at->format('F j, Y, g:i a') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                @if ($examStatistics->isSuccessfulAttempt())
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Pass
                                    </span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Failed
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @else
                        <tr>

                            <td colspan="3" class="text-black p-1 text-center">In PROGRESS</td>

                        </tr>
                    @endif
                @endforeach
                <!-- More rows can be added here -->
            </tbody>
        </table>
    @endif
</div>
