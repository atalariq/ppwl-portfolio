<dialog id="confirm-dialog" class="modal">
    <div class="modal-card">
        <div class="flex items-start gap-3">
            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-50 text-danger">
                <x-ui.icon name="alert-triangle" class="h-4 w-4" />
            </span>
            <div>
                <h2 class="text-base font-semibold" data-confirm-title>Confirm action</h2>
                <p class="mt-1 text-sm text-muted" data-confirm-message>This action cannot be undone.</p>
            </div>
        </div>
        <div class="mt-6 flex justify-end gap-2">
            <button type="button" class="btn btn-secondary btn-sm" data-dialog-close>Cancel</button>
            <button type="button" class="btn btn-danger btn-sm" data-confirm-accept>Confirm</button>
        </div>
    </div>
</dialog>
