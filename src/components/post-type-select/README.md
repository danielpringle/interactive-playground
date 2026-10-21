# PostTypeSelect

Provides a select control containing the available, viewable WordPress post types.

The component uses the WordPress core data store to retrieve registered post types and excludes internal WordPress post types that are not intended for selection.

## Usage

Import the component and pass the required props.

```js
import PostTypeSelect from '../../components/post-type-select/post-type-select';

export default function MyComponent( props ) {
	const {
		postType,
		setPostType,
	} = props;

	return (
		<PostTypeSelect
			label="Select Post Type"
			help="Choose the post type you want to use."
			initialOptionLabel="Select"
			value={ postType }
			onChange={ setPostType }
		/>
	);
}

| Name                 | Type       | Default     | Required | Description                                                                         |
| -------------------- | ---------- | ----------- | -------- | ----------------------------------------------------------------------------------- |
| `label`              | `string`   | `''`        | No       | Label displayed above the select control.                                           |
| `help`               | `string`   | `''`        | No       | Help text displayed below the select control.                                       |
| `initialOptionLabel` | `string`   | `'Select'`  | No       | Label displayed for the initial empty option.                                       |
| `value`              | `string`   | `''`        | No       | Slug of the currently selected post type.                                           |
| `onChange`           | `function` | `undefined` | Yes      | Callback called when a post type is selected. Receives the selected post type slug. |
```

## Example

Select a post type and store the value in component state:

```js
const [ postType, setPostType ] = useState( '' );

<PostTypeSelect
	label="Select Post Type"
	help="Choose the post type you want to use."
	value={ postType }
	onChange={ setPostType }
/>
```

Behaviour
Retrieves registered WordPress post types using the WordPress core data store.
Displays only viewable post types.
Excludes internal WordPress post types.
Uses the post type slug as the option value.
Uses the post type singular name as the option label where available.
Does not handle persistence itself.
Can be used in blocks, document settings, or other React components.
Excluded Post Types

The following post types are excluded by default:

attachment
wp_block
wp_navigation
wp_template
wp_template_part
Persistence

PostTypeSelect is a controlled UI component and does not save the selected value.

The parent component is responsible for deciding how the value should be stored.

For example, a block can store the value using block attributes:
```js
<PostTypeSelect
	value={ postType }
	onChange={ ( value ) => {
		setAttributes( {
			postType: value,
		} );
	} }
/>
```

A Gutenberg document setting can store the value using post meta:

```js
editPost( {
	meta: {
		...meta,
		related_post_type: value,
	},
} );
```