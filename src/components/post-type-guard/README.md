# PostTypeGuard Component

A reusable React component for conditionally rendering children based on the existence of a WordPress post type. Useful for block development and plugin UIs that depend on custom post types.

---

## Table of Contents
- [Description](#description)
- [Props](#props)
- [Usage](#usage)
- [Examples](#examples)
- [Accessibility](#accessibility)
- [Styling](#styling)
- [License](#license)

---

## Description
The `PostTypeGuard` component checks if a specified WordPress post type is registered and available. If the post type is loading, it shows a spinner. If the post type does not exist, it displays a warning message. If the post type exists, it renders its children.

---

## Props
| Name      | Type     | Required | Default              | Description                                      |
|-----------|----------|----------|----------------------|--------------------------------------------------|
| postType  | string   | Yes      | —                    | The slug of the post type to check.              |
| label     | string   | No       | 'Missing Dependency' | The label for the placeholder if missing.        |
| children  | ReactNode| No       | —                    | Content to render if the post type exists.       |

---

## Usage
Import and use the component in your React code:

```jsx
import { PostTypeGuard } from '@nachalo/wp-ui-components';

<PostTypeGuard postType="events" label="Events Block">
  <div>Events post type exists!</div>
</PostTypeGuard>
```

---

## Examples
### Guarding a Block UI
```jsx
<PostTypeGuard postType="book" label="Book Block">
  <div>Book post type is available.</div>
</PostTypeGuard>
```

### Custom Placeholder Label
```jsx
<PostTypeGuard postType="movie" label="Movie Block">
  <div>Movie post type is available.</div>
</PostTypeGuard>
```

---

## Accessibility
- Uses WordPress `Placeholder`, `Notice`, and `Spinner` components for accessible feedback.
- Warning message uses semantic HTML and ARIA roles via WordPress components.

---

## Styling
- Inherits styles from WordPress components (`Placeholder`, `Notice`, `Spinner`).
- To customize, wrap or override styles in your parent component.

---

## License
MIT

---

## Template for Other Components

Copy and adapt this format for documenting other components:
1. Description
2. Props table
3. Usage instructions
4. Examples
5. Accessibility notes
6. Styling notes
7. License
