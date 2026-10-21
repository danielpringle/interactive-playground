 /**
  * PostTypeSelect component.
  *
  * Provides a select control containing the available, viewable
  * WordPress post types.
  *
  * Internal WordPress post types that are not intended for selection
  * are excluded from the available options.
  */

import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const PostTypeSelect = ( {
	label = '',
	help = '',
	initialOptionLabel = __( 'Select', 'nachalo-scaffold' ),
	value = '',
	onChange,
} ) => {
	/**
	 * Retrieves the available WordPress post types.
	 */
	const postTypes = useSelect(
		( select ) =>
			select( coreStore ).getPostTypes( {
				per_page: -1,
			} ),
		[]
	);

	/**
	 * Post types that should not be available for selection.
	 */
	const excludedPostTypes = [
		'attachment',
		'wp_block',
		'wp_navigation',
		'wp_template',
		'wp_template_part',
	];

	/**
	 * Converts available post types into select options.
	 *
	 * Only viewable post types are included and internal WordPress
	 * post types are excluded.
	 */
	const options = [
		{
			value: '',
			label: initialOptionLabel,
		},
		...( postTypes || [] )
			.filter(
				( postType ) =>
					postType.viewable &&
					! excludedPostTypes.includes( postType.slug )
			)
			.map( ( postType ) => ( {
				value: postType.slug,
				label:
					postType.labels?.singular_name ||
					postType.name,
			} ) ),
	];

	return (
		<SelectControl
			label={ label }
			help={ help }
			value={ value }
			options={ options }
			onChange={ onChange }
			__next40pxDefaultSize
			__nextHasNoMarginBottom
		/>
	);
};

export default PostTypeSelect;