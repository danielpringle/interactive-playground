import { __experimentalUnitControl as UnitControl } from '@wordpress/components';
import SpacingIcon from './spacing-icon';

const getAutoLabel = (type, axis, side) => {
	const typeLabel = type === 'padding' ? 'Padding' : 'Margin';
	const axisLabel = axis === 'block' ? 'Block' : 'Inline';
	const sideLabel = side === 'start' ? 'Start' : 'End';

	return `${typeLabel} ${axisLabel} ${sideLabel}`;
};

export const SpacingUnitControl = ({
	type = 'padding',
	axis = 'block',
	side = 'start',

	label,
	help,

	value,
	onChange,

	units = [
		{ value: 'px', label: 'px' },
		{ value: 'rem', label: 'rem' },
	],
	min = 0,
}) => {
	const finalLabel = label || getAutoLabel(type, axis, side);

	return (
		<div className="dpuk-spacing-control">
			<div className="dpuk-spacing-control__header">
				<SpacingIcon axis={axis} side={side} />
				<span>{finalLabel}</span>
			</div>

			<UnitControl
				value={value}
				onChange={onChange}
				units={units}
				min={min}
				__next40pxDefaultSize
			/>

			{help && (
				<div className="dpuk-spacing-control__help">
					{help}
				</div>
			)}
		</div>
	);
};