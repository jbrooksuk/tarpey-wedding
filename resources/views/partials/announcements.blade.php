@if ($announcements->isNotEmpty())
<tab name="Updates">
    @foreach ($announcements as $announcement)
    <div class="update">
        <p class="text-xl mb-4">Update #{{ $loop->count - $loop->index }}</p>

        {{ $announcement->formatted_content }}

        @if (!$loop->last)
        <hr class="border-t border-gray-400 my-4">
        @endif
    </div>
    @endforeach
</tab>
@endif
