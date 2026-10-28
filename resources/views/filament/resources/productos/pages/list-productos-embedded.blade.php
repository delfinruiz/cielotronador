<div class="space-y-4">
    <div class="flex flex-wrap items-center justify-end gap-2">
        <x-filament::actions :actions="$this->getCachedHeaderActions()" />
    </div>

    {{ $this->table }}
</div>
