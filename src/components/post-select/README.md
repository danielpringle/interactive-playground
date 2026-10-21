# PostSelect

Provides a searchable select control for posts belonging to a supplied WordPress post type.

The component uses the WordPress core data store to retrieve posts and provides the selected post ID through the `onChange` callback.

## Usage

Import the component and pass the required post type and value.

```js
import PostSelect from '../../components/post-select/post-select';

export default function MyComponent( props ) {
	const {
		postType,
		postId,
		setPostId,
	} = props;

	return (
		<PostSelect
			postType={ postType }
			label="Select Post"
			help="Search for a post by name."
			value={ postId }
			onChange={ setPostId }
		/>
	);
}

```
Props
| Name       | Type       | Default     | Required | Description                                                             |
| ---------- | ---------- | ----------- | -------- | ----------------------------------------------------------------------- |
| `postType` | `string`   | `''`        | Yes      | The WordPress post type to search.                                      |
| `label`    | `string`   | `''`        | No       | Label displayed above the select control.                               |
| `help`     | `string`   | `''`        | No       | Help text displayed below the select control.                           |
| `value`    | `number`   | `0`         | No       | ID of the currently selected post.                                      |
| `onChange` | `function` | `undefined` | Yes      | Callback called when a post is selected. Receives the selected post ID. |

Example

Search for a post belonging to the page post type:
```js
<PostSelect
	postType="page"
	label="Select Page"
	help="Search for a page by name."
	value={ pageId }
	onChange={ setPageId }
/>
```

Behaviour
Searches posts as the user types.
Searches only within the supplied post type.
Returns up to 20 matching posts.
Results are ordered alphabetically by title.
Returns the selected post ID through onChange.
Does not handle persistence itself.
Can be used in blocks, document settings, or other React components.
Persistence

PostSelect is a controlled UI component and does not save the selected value.

The parent component is responsible for deciding how the value should be stored.

For example, a block can store the value using block attributes:

```js
<PostSelect
	postType="page"
	value={ pageId }
	onChange={ ( value ) => {
		setAttributes( {
			pageId: value,
		} );
	} }
/>
```

A Gutenberg document setting can store the value using post meta:

```js
editPost( {
	meta: {
		...meta,
		related_post_id: value,
	},
} );
```