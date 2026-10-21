import { PluginDocumentSettingPanel } from '@wordpress/editor';

export default function SettingsPanel( {
	title,
	icon,
	children,
	className,
} ) {
	return (
		<PluginDocumentSettingPanel
			name="dpuk-settings"
			title={ title }
			icon={ icon }
			className={ className }
		>
			{ children }
		</PluginDocumentSettingPanel>
	);
}