/**
 * Activity editor settings.
 *
 * Adds custom settings to the WordPress editor for Activity posts.
 *
 * Provides a searchable place selector and stores the selected place
 * post ID in the `place_id` post meta field.
 *
 * @package DPUK\InteractivePlayground
 */

/**
 * WordPress dependencies.
 */
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { ComboboxControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';
import { useState } from '@wordpress/element';

function ActivitySettings() {
	const [ search, setSearch ] = useState( '' );

	const meta = useSelect(
		( select ) =>
			select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {},
		[]
	);

	const places = useSelect(
		( select ) =>
			select( 'core' ).getEntityRecords( 'postType', 'treatments', {
				per_page: 20,
				search,
				orderby: 'title',
				order: 'asc',
			} ),
		[ search ]
	);

	const { editPost } = useDispatch( 'core/editor' );

	const options = ( places || [] ).map( ( place ) => ( {
		label: place.title.rendered,
		value: String( place.id ),
	} ) );

	const selectedPlace = meta.place_id
		? String( meta.place_id )
		: '';

	return (
		<PluginDocumentSettingPanel
			name="activity-settings"
			title="Activity Settings"
		>
			<ComboboxControl
				label="Place"
				value={ selectedPlace }
				options={ options }
				onFilterValueChange={ setSearch }
				onChange={ ( value ) => {
					editPost( {
						meta: {
							...meta,
							place_id: parseInt( value, 10 ) || 0,
						},
					} );
				} }
				help="Search for a place by name."
			/>
		</PluginDocumentSettingPanel>
	);
}

registerPlugin( 'activity-settings', {
	render: ActivitySettings,
} );
