# Gutenberg Settings

Provides the Gutenberg editor settings entry point for registering document settings and composing reusable components.

The settings entry point is responsible for:

* Registering the Gutenberg plugin.
* Rendering the document settings UI.
* Connecting reusable components to the WordPress editor.
* Reading and updating post meta.
* Coordinating related controls, such as selecting a post type and then selecting a post.

Reusable UI controls are located in `src/components/` and should not handle persistence themselves.

## File Structure

```text
src/
├── components/
│   ├── post-select/
│   ├── post-type-select/
│   └── settings-panel/
└── js/
    └── settings/
        ├── settings.js
        └── README.md
```

## Registration

The settings entry point registers a Gutenberg plugin using `registerPlugin()`.

```js
import { registerPlugin } from '@wordpress/plugins';

registerPlugin( 'dpuk-settings', {
	render: Settings,
} );
```

The plugin renders the `Settings` component inside the Gutenberg editor.

## Settings Panel

The reusable `SettingsPanel` component provides the document settings panel.

```js
import SettingsPanel from '../../components/settings-panel/settings-panel';

<SettingsPanel
	name="dpuk-settings"
	title="Settings"
>
	{/* Settings controls */}
</SettingsPanel>
```

`SettingsPanel` is responsible only for rendering the Gutenberg panel. Individual controls are responsible for their own UI.

## Reading Post Meta

Settings that need to persist against the current post can read the edited post meta from the Gutenberg editor store.

```js
import { useSelect } from '@wordpress/data';

const meta = useSelect(
	( select ) =>
		select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {},
	[]
);
```

The `meta` object contains the current values being edited in the Gutenberg editor.

For example:

```js
const postType = meta.related_post_type || '';
const postId = meta.related_post_id || 0;
```

## Updating Post Meta

Use the `core/editor` dispatch store to update the edited post.

```js
import { useDispatch } from '@wordpress/data';

const { editPost } = useDispatch( 'core/editor' );
```

A setting can then update post meta using `editPost()`:

```js
editPost( {
	meta: {
		...meta,
		related_post_type: value,
	},
} );
```

The updated value remains part of the Gutenberg editor state and is saved when the post is saved or published.

## Example: Post Type and Post Selection

The `PostTypeSelect` and `PostSelect` components can be combined to create a generic post relationship selector.

The selected post type and post ID are stored separately:

```text
related_post_type = "page"
related_post_id   = 123
```

This ensures that the relationship contains both the post type and the post ID.

```js
import { useSelect, useDispatch } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';

import SettingsPanel from '../../components/settings-panel/settings-panel';
import PostTypeSelect from '../../components/post-type-select/post-type-select';
import PostSelect from '../../components/post-select/post-select';

const Settings = () => {
	const { editPost } = useDispatch( 'core/editor' );

	const meta = useSelect(
		( select ) =>
			select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {},
		[]
	);

	const postType = meta.related_post_type || '';
	const postId = meta.related_post_id || 0;

	/**
	 * Handle a change to the related post type.
	 *
	 * Resets the selected post when the post type changes.
	 *
	 * @param {string} value Selected post type.
	 */
	const handlePostTypeChange = ( value ) => {
		editPost( {
			meta: {
				...meta,
				related_post_type: value,
				related_post_id: 0,
			},
		} );
	};

	/**
	 * Handle a change to the related post.
	 *
	 * @param {number} value Selected post ID.
	 */
	const handlePostChange = ( value ) => {
		editPost( {
			meta: {
				...meta,
				related_post_id: value || 0,
			},
		} );
	};

	return (
		<SettingsPanel
			name="dpuk-settings"
			title="Settings"
		>
			<PostTypeSelect
				label="Post type"
				value={ postType }
				onChange={ handlePostTypeChange }
			/>

			{ postType && (
				<PostSelect
					postType={ postType }
					label="Post"
					value={ postId }
					onChange={ handlePostChange }
				/>
			) }
		</SettingsPanel>
	);
};

registerPlugin( 'dpuk-settings', {
	render: Settings,
} );
```

### Resetting Related Values

When the selected post type changes, the previously selected post ID should be reset:

```js
related_post_type: value,
related_post_id: 0,
```

This prevents a post selected from the previous post type from remaining associated with the new post type.

For example, changing from:

```text
Page → About Us
```

to:

```text
Post → News Article
```

should not leave the ID of the `About Us` page selected.

## Registering Post Meta

Post meta used by Gutenberg must be registered in PHP and exposed through the REST API.

For example:

```php
register_post_meta(
	'post',
	'related_post_type',
	array(
		'type'         => 'string',
		'single'       => true,
		'show_in_rest' => true,
	)
);

register_post_meta(
	'post',
	'related_post_id',
	array(
		'type'         => 'integer',
		'single'       => true,
		'show_in_rest' => true,
	)
);
```

`show_in_rest` is required so the Gutenberg editor can read and update the meta through the REST API.

For production code, the meta registration should also include an appropriate `auth_callback`.

## Component Responsibilities

The settings entry point and reusable components have different responsibilities.

### Reusable Components

Components such as `PostTypeSelect` and `PostSelect`:

* Render the UI.
* Retrieve the data required to populate their controls.
* Accept a controlled `value`.
* Call `onChange` when the value changes.
* Do not decide where the value is stored.
* Do not call `editPost()` themselves.

### `settings.js`

The settings entry point:

* Composes the reusable components.
* Connects components to the Gutenberg editor.
* Reads post meta.
* Updates post meta.
* Coordinates related settings.
* Registers the Gutenberg plugin.

This keeps the reusable components independent of Gutenberg persistence and allows them to be reused in blocks or other React interfaces.

## Persistence

The reusable components do not save values themselves.

For example:

```js
<PostTypeSelect
	value={ postType }
	onChange={ handlePostTypeChange }
/>
```

The component reports the selected value through `onChange`.

The settings layer decides what to do with that value:

```js
const handlePostTypeChange = ( value ) => {
	editPost( {
		meta: {
			...meta,
			related_post_type: value,
		},
	} );
};
```

This separation keeps UI components storage-agnostic.

## Domain-Specific Relationships

The generic `related_post_type` and `related_post_id` pattern is useful when the relationship can point to different WordPress post types.

For a domain-specific relationship, use a semantic meta key instead.

For example, a My Local Wild activity that belongs to a place would use:

```text
place_id = 123
```

rather than:

```text
related_post_type = "mlw_place"
related_post_id   = 123
```

The appropriate persistence model depends on the domain and relationship being represented.

## Architecture

The overall relationship between the settings entry point and reusable components is:

```text
Gutenberg Editor
       │
       ▼
registerPlugin()
       │
       ▼
   settings.js
       │
       ├── reads post meta
       ├── updates post meta
       └── composes components
              │
              ├── SettingsPanel
              │
              ├── PostTypeSelect
              │
              └── PostSelect
```

The important boundary is:

```text
Reusable component
        │
        │ value / onChange
        ▼
settings.js
        │
        │ editPost()
        ▼
Gutenberg editor
        │
        ▼
WordPress post meta
```

The components provide the UI; `settings.js` provides the Gutenberg-specific integration and persistence.
