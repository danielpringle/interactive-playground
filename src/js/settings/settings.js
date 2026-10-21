import { useSelect, useDispatch } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';

import SettingsPanel from '../../components/settings-panel/settings-panel';
import PostTypeSelect from '../../components/post-type-select/post-type-select';
import PostSelect from '../../components/post-select/post-select';

const Settings = () => {
	/**
	 * WordPress editor dispatch actions.
	 *
	 * @type {Object}
	 */
	const { editPost } = useDispatch( 'core/editor' );

	/**
	 * Get the current edited post meta from the WordPress editor.
	 *
	 * @type {Object}
	 */
	const meta = useSelect(
		( select ) =>
			select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {},
		[]
	);

	/**
	 * Get the selected related post type and post ID.
	 *
	 * @type {string}
	 */
	const postType = meta.related_post_type || '';

	/**
	 * Get the selected related post ID.
	 *
	 * @type {number}
	 */
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