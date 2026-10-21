import {
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';

import {
	InspectorControls,
} from '@wordpress/block-editor';

import {
	PanelBody,
} from '@wordpress/components';

import { SpacingUnitControl } from '../../components/spacing-unit-control/spacing-unit-control';







export default function Inspector( props ) {
	const { attributes, setAttributes } = props;

    /**
	 * List attributes as constants
	 */
	const {
		paddingBlockStart,
		paddingBlockEnd,
	} = attributes;

	return (
		<InspectorControls>
			<PanelBody title="Spacing">
			<SpacingUnitControl
				type="padding"
				axis="block"
				side="start"
				value={paddingBlockStart}
				onChange={(value) =>
					setAttributes({ paddingBlockStart: value })
				}
				help="Controls space above the inner blocks."
			/>
			<SpacingUnitControl
				type="padding"
				axis="block"
				side="end"
				value={paddingBlockEnd}
				onChange={(value) =>
					setAttributes({ paddingBlockEnd: value })
				}
				help="Controls space below the inner blocks."
			/>
			</PanelBody>
		</InspectorControls>
	);
}