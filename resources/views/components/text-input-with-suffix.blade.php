@props(['disabled' => false])
<div class="flex flex-col">
    <strong id="subdomain-label" class="text-sm text-gray-500 dark:text-gray-400 pt-1 pb-1">{{ env('APP_URL') }}</strong>
    <input id="subdomain-input" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'class' =>
            'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm',
    ]) !!}
        onkeyup="updateSubdomainLabel()">
</div>
<script>
    let domain = @json(env('APP_URL'));
    let subdomainLabel = document.getElementById('subdomain-label');
    let input = document.getElementById('subdomain-input');
    function updateSubdomainLabel() {
        renderData();
    }
    document.addEventListener('DOMContentLoaded', function() {
        renderData();
    });
    renderData = () => {
        let subdomain = input.value.trim();
        subdomainLabel.textContent = domain.replace('//', `//${subdomain}.`);
    }
</script>
