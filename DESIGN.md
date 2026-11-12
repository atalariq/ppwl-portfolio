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

## Public page rhythm

- Every public page starts with `x-ui.page-header` and `mt-10` content.
- The page header uses an optional eyebrow, a `text-3xl sm:text-4xl` title, a `text-base` description, and a bottom divider.
- The public navbar is sticky; `html` reserves a stable scrollbar gutter so content does not shift between pages.
- Experience and Education share the `x-ui.entry-card` grid with About's card rhythm.
- Projects and the Home carousel share `x-project-card`.
- Projects use a three-column desktop grid; the carousel is manual scroll-snap with arrows and dots, never autoplay.
- Skills are grouped chips by category; numeric levels are not used.

## Layouts

- `layouts.app`: public navbar/footer, `container-page`.
- `layouts.admin`: CSS-only sidebar drawer, topbar, `container-admin`.
- `layouts.auth`: centered card for login/reset pages.

Mobile navigation uses native `<details>`; a small vanilla JS entry powers dialogs, quick edit, bulk actions, and upload previews. There is no client-side framework.

## Rules

1. Do not duplicate long utility strings in views; use a component or a class.
2. Status colors use badges; do not color entire cards by status.
3. Forms always use `x-ui.field` so labels and errors are consistent.
4. Tables use `.table`; wrap them in an overflow container on small screens.
5. Empty results use `x-ui.empty-state`.
6. Keep public pages on the shared page-header + card rhythm; there is no public styleguide route.
