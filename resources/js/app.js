const confirmDialog = document.getElementById('confirm-dialog');
let pendingForm = null;

const openConfirm = (form, message, title = 'Confirm action') => {
    if (!confirmDialog) {
        if (window.confirm(message)) {
            form.submit();
        }

        return;
    }

    pendingForm = form;
    confirmDialog.querySelector('[data-confirm-title]').textContent = title;
    confirmDialog.querySelector('[data-confirm-message]').textContent = message;
    confirmDialog.showModal();
};

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !form.dataset.confirm) {
        return;
    }

    event.preventDefault();
    openConfirm(form, form.dataset.confirm, form.dataset.confirmTitle);
});

if (confirmDialog) {
    confirmDialog.querySelector('[data-confirm-accept]')?.addEventListener('click', () => {
        const form = pendingForm;
        pendingForm = null;
        confirmDialog.close();
        form?.submit();
    });

    confirmDialog.querySelectorAll('[data-dialog-close]').forEach((button) => {
        button.addEventListener('click', () => confirmDialog.close());
    });

    confirmDialog.addEventListener('click', (event) => {
        if (event.target === confirmDialog) {
            confirmDialog.close();
        }
    });
}

document.querySelectorAll('[data-dialog-open]').forEach((button) => {
    button.addEventListener('click', () => {
        const dialog = document.getElementById(button.dataset.dialogOpen);

        if (dialog) {
            dialog.showModal();
        }
    });
});

document.querySelectorAll('dialog').forEach((dialog) => {
    dialog.querySelectorAll('[data-dialog-close]').forEach((button) => {
        button.addEventListener('click', () => dialog.close());
    });

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();
        }
    });
});

document.querySelectorAll('[data-bulk-form]').forEach((form) => {
    const action = form.querySelector('[data-bulk-action]');
    const category = form.querySelector('[data-bulk-category]');

    const sync = () => {
        category?.classList.toggle('hidden', action?.value !== 'category');
    };

    action?.addEventListener('change', sync);
    sync();

    form.addEventListener('submit', (event) => {
        if (action?.value !== 'delete') {
            return;
        }

        event.preventDefault();
        openConfirm(form, form.dataset.bulkDeleteMessage || 'Delete the selected items?', 'Delete selected items');
    });
});

document.querySelectorAll('[data-select-all]').forEach((master) => {
    const table = master.closest('table');

    if (!table) {
        return;
    }

    const rows = () => Array.from(table.querySelectorAll('[data-select-row]'));

    master.addEventListener('change', () => {
        rows().forEach((checkbox) => {
            checkbox.checked = master.checked;
        });
    });

    rows().forEach((checkbox) => {
        checkbox.addEventListener('change', () => {
            const selected = rows().filter((item) => item.checked).length;
            master.checked = selected === rows().length;
            master.indeterminate = selected > 0 && selected < rows().length;
        });
    });
});

const formatSize = (bytes) => {
    if (bytes < 1024 * 1024) {
        return `${Math.ceil(bytes / 1024)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
};

document.querySelectorAll('[data-dropzone]').forEach((root) => {
    const input = root.querySelector('[data-dropzone-input]');
    const area = root.querySelector('[data-dropzone-area]');
    const preview = root.querySelector('[data-dropzone-preview]');
    const error = root.querySelector('[data-dropzone-error]');
    const form = root.closest('form');
    const maxBytes = Number(root.dataset.maxSizeMb || 4) * 1024 * 1024;
    const minWidth = Number(root.dataset.minWidth || 0);
    const minHeight = Number(root.dataset.minHeight || 0);
    const maxFiles = Number(root.dataset.maxFiles || 10);
    let files = [];
    let invalid = false;

    if (!input || !area || !preview || !error) {
        return;
    }

    const showError = (message) => {
        invalid = true;
        error.textContent = message;
        error.classList.remove('hidden');
    };

    const clearError = () => {
        invalid = false;
        error.textContent = '';
        error.classList.add('hidden');
    };

    const syncInput = () => {
        const transfer = new DataTransfer();
        files.forEach((file) => transfer.items.add(file));
        input.files = transfer.files;
    };

    const render = () => {
        preview.replaceChildren();
        clearError();

        if (files.length === 0) {
            return;
        }

        if (files.length > maxFiles) {
            showError(`You can upload at most ${maxFiles} images at a time.`);
        }

        files.forEach((file) => {
            if (!file.type.startsWith('image/')) {
                showError('Only JPG, PNG, and WebP images are allowed.');

                return;
            }

            if (file.size > maxBytes) {
                showError(`${file.name} is larger than ${root.dataset.maxSizeMb} MB.`);

                return;
            }

            const card = document.createElement('figure');
            card.className = 'dropzone-preview-card';

            const image = document.createElement('img');
            image.className = 'h-28 w-full object-cover';
            image.alt = file.name;
            const objectUrl = URL.createObjectURL(file);
            image.src = objectUrl;

            if (minWidth && minHeight) {
                image.addEventListener('load', () => {
                    if (image.naturalWidth < minWidth || image.naturalHeight < minHeight) {
                        showError(`${file.name} must be at least ${minWidth}×${minHeight} px.`);
                    }
                });
            }

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'absolute right-2 top-2 inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-900/80 text-xs font-semibold text-white';
            remove.setAttribute('aria-label', `Remove ${file.name}`);
            remove.textContent = '×';
            remove.addEventListener('click', () => {
                URL.revokeObjectURL(objectUrl);
                files = files.filter((selected) => selected !== file);
                syncInput();
                render();
            });

            const meta = document.createElement('figcaption');
            meta.className = 'border-t border-border px-3 py-2';
            const name = document.createElement('p');
            name.className = 'truncate text-xs font-medium';
            name.textContent = file.name;
            const size = document.createElement('p');
            size.className = 'text-[11px] text-muted';
            size.textContent = formatSize(file.size);
            meta.append(name, size);

            card.append(image, remove, meta);
            preview.append(card);
        });
    };

    const acceptFiles = (selected) => {
        files = root.dataset.multiple === 'true' ? Array.from(selected) : Array.from(selected).slice(0, 1);
        syncInput();
        render();
    };

    input.addEventListener('change', () => acceptFiles(input.files));

    ['dragenter', 'dragover'].forEach((eventName) => {
        area.addEventListener(eventName, (event) => {
            event.preventDefault();
            area.classList.add('dropzone-active');
        });
    });

    ['dragleave', 'drop'].forEach((eventName) => {
        area.addEventListener(eventName, (event) => {
            event.preventDefault();
            area.classList.remove('dropzone-active');
        });
    });

    area.addEventListener('drop', (event) => {
        if (event.dataTransfer?.files?.length) {
            acceptFiles(event.dataTransfer.files);
        }
    });

    form?.addEventListener('submit', (event) => {
        if (invalid) {
            event.preventDefault();
            showError('Please fix the selected files before uploading.');
        }
    });
});

document.querySelectorAll('[data-carousel]').forEach((root) => {
    const track = root.querySelector('[data-carousel-track]');
    const previous = root.querySelector('[data-carousel-prev]');
    const next = root.querySelector('[data-carousel-next]');
    const dots = root.querySelector('[data-carousel-dots]');
    const slides = track ? Array.from(track.children) : [];

    if (!track || !dots || slides.length === 0) {
        return;
    }

    const metrics = () => {
        const gap = parseFloat(window.getComputedStyle(track).columnGap || '0');
        const slideWidth = slides[0].getBoundingClientRect().width;
        const visible = Math.max(1, Math.floor((track.clientWidth + gap) / (slideWidth + gap)));
        const pageWidth = (slideWidth + gap) * visible;

        return {
            pageWidth,
            pages: Math.max(1, Math.ceil(slides.length / visible)),
        };
    };

    const currentPage = () => {
        const { pageWidth, pages } = metrics();

        return Math.min(pages - 1, Math.max(0, Math.round(track.scrollLeft / pageWidth)));
    };

    const scrollToPage = (page) => {
        const { pageWidth, pages } = metrics();

        track.scrollTo({
            left: Math.min(Math.max(page, 0), pages - 1) * pageWidth,
            behavior: 'smooth',
        });
    };

    const renderDots = () => {
        const { pages } = metrics();
        dots.replaceChildren();

        for (let page = 0; page < pages; page++) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'carousel-dot';
            dot.setAttribute('aria-label', `Go to featured page ${page + 1}`);
            dot.addEventListener('click', () => scrollToPage(page));
            dots.append(dot);
        }

        update();
    };

    const update = () => {
        const { pageWidth, pages } = metrics();
        const page = currentPage();

        Array.from(dots.children).forEach((dot, index) => {
            dot.classList.toggle('carousel-dot-active', index === page);
        });

        if (previous) {
            previous.disabled = page <= 0;
        }

        if (next) {
            next.disabled = page >= pages - 1 || track.scrollLeft >= track.scrollWidth - track.clientWidth - 4;
        }

        if (pageWidth === 0) {
            return;
        }
    };

    previous?.addEventListener('click', () => scrollToPage(currentPage() - 1));
    next?.addEventListener('click', () => scrollToPage(currentPage() + 1));
    track.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', renderDots);

    renderDots();
});
