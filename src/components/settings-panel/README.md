# SettingsPanel

Provides a reusable wrapper around the WordPress `PluginDocumentSettingPanel` component.

Use `SettingsPanel` to create a document settings panel in the Gutenberg editor.

## Usage

```js
import SettingsPanel from '../../components/settings-panel/settings-panel';

<SettingsPanel
	name="dpuk-settings"
	title="Settings"
>
	{/* Settings controls */}
</SettingsPanel>
```

## Props

| Name        | Type                  | Default | Required | Description                                      |
| ----------- | --------------------- | ------- | -------- | ------------------------------------------------ |
| `name`      | `string`              | —       | Yes      | Unique name used to identify the settings panel. |
| `title`     | `string`              | —       | Yes      | Title displayed for the settings panel.          |
| `icon`      | `string \| Component` | —       | No       | Icon displayed alongside the panel title.        |
| `className` | `string`              | —       | No       | Additional CSS class applied to the panel.       |
| `children`  | `ReactNode`           | —       | No       | Content rendered inside the settings panel.      |

## Example

```js
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
```

## Behaviour

* Renders a Gutenberg document settings panel.
* Passes the supplied props to `PluginDocumentSettingPanel`.
* Renders child components inside the panel.
* Does not handle persistence or application state.
* Does not contain domain-specific settings logic.

## Responsibility

`SettingsPanel` is a presentation component.

It provides the panel container, while the consuming code is responsible for:

* Managing state.
* Reading post meta.
* Updating post meta.
* Handling persistence.
* Providing the controls displayed inside the panel.

For example, `settings.js` handles Gutenberg editor integration and persistence, while `SettingsPanel` only provides the UI container.
