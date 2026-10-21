# Alert Component

A reusable React component for displaying styled alert messages, supporting different types such as info, success, warning, and error.

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
The `Alert` component provides a simple way to display important messages to users, with visual styles that correspond to the alert type. It is suitable for use in WordPress block development or any React project.

---

## Props
| Name    | Type   | Required | Default | Description                                      |
|---------|--------|----------|---------|--------------------------------------------------|
| type    | string | No       | 'info'  | The alert style: 'info', 'success', 'warning', 'error' |
| message | string | Yes      | —       | The message text to display                      |

---

## Usage
Import and use the component in your React code:

```jsx
import Alert from './alert';

<Alert type="success" message="Your changes have been saved!" />
```

### In a WordPress Block
If using with a block, import from your shared components package:

```jsx
import { Alert } from '@nachalo/wp-block-components';

<Alert type={type} message={message} />
```

---

## Examples
### Info Alert
```jsx
<Alert type="info" message="This is an informational message." />
```

### Success Alert
```jsx
<Alert type="success" message="Operation completed successfully!" />
```

### Warning Alert
```jsx
<Alert type="warning" message="Please check your input." />
```

### Error Alert
```jsx
<Alert type="error" message="Something went wrong." />
```

---

## Accessibility
- The alert uses `role="alert"` for screen reader support.
- Ensure the message is clear and concise for all users.

---

## Styling
The component uses inline styles for background, border, and text color based on the alert type. You can override these styles by wrapping the component or passing additional props if extended.

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
