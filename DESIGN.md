# Design System

The UI uses Tailwind CSS 4 tokens and a small set of Blade components. There is no
component library, no Alpine, and no client-side framework.

## Tokens

Defined in `resources/css/app.css` under `@theme`:

- Brand: `brand-50` … `brand-900` (indigo).
- Surface: `surface`, `surface-muted`.
- Text: `text`, `muted`.
- Border: `border`.
- Status: `success`, `warning`, `danger`.
- Radius: `sm`, `md`, `lg`.
- Shadow: `shadow-card`.

Use semantic tokens (`bg-surface`, `text-muted`, `border-border`) instead of raw
Tailwind palette classes in views.

## Base styles

`@layer base` sets body, headings, links, focus rings, and antialiasing. Every
interactive element must remain keyboard-focusable.

## Component classes

`@layer components` defines:

- `.btn` + `.btn-primary`, `.btn-secondary`, `.btn-danger`, `.btn-ghost`, `.btn-sm`
- `.card`, `.card-body`
- `.input`, `.select`, `.textarea`, `.label`, `.help`, `.error`
- `.badge` + `.badge-success`, `.badge-warning`, `.badge-muted`
- `.alert` + `.alert-success`, `.alert-info`, `.alert-danger`
- `.table`
- `.page-header`, `.page-title`, `.page-description`
- `.empty-state`
- `.stat`, `.stat-label`, `.stat-value`
- `.nav-link`, `.nav-link-active`, `.sidebar-link`, `.sidebar-link-active`
- `.container-page`, `.container-admin`

## Blade components

All in `resources/views/components/ui/`:

| Component | Use |
|---|---|
| `x-ui.button` | Link or button with variant/size |
| `x-ui.card` | Surface with optional title, description, actions |
| `x-ui.field` | Label, input slot, hint, and error |
| `x-ui.alert` | Success, info, or danger message |
| `x-ui.badge` | Status or category label |
| `x-ui.page-header` | Page title, description, actions |
| `x-ui.empty-state` | Empty table/list state |
| `x-ui.stat` | Dashboard metric |
| `x-ui.avatar` | User image or initials |
| `x-ui.nav-link` | Public navigation link |
| `x-ui.sidebar-link` | Admin sidebar link |

## Layouts

- `layouts.app`: public navbar/footer, `container-page`.
- `layouts.admin`: CSS-only sidebar drawer, topbar, `container-admin`.
- `layouts.auth`: centered card for login/reset pages.

Mobile navigation uses native `<details>`; there is no JavaScript bundle.

## Rules

1. Do not duplicate long utility strings in views; use a component or a class.
2. Status colors use badges; do not color entire cards by status.
3. Forms always use `x-ui.field` so labels and errors are consistent.
4. Tables use `.table`; wrap them in an overflow container on small screens.
5. Empty results use `x-ui.empty-state`.
6. The styleguide at `/post/styleguide` is the visual reference.
