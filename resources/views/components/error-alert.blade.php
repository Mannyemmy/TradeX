<div>
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-transition class="p-4 rounded-lg bg-loss/10 border border-loss/20 mb-4" role="alert">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    @include('components.icons.exclamation-triangle', ['class' => 'w-5 h-5 text-loss'])
                    <p class="text-sm font-medium text-loss">Please fix the following errors:</p>
                </div>
                <button @click="show = false" class="text-loss/60 hover:text-loss transition">
                    @include('components.icons.x-mark', ['class' => 'w-4 h-4'])
                </button>
            </div>
            <ul class="list-disc list-inside space-y-1 pl-7">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-loss/90">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>