import { TextControl, SelectControl } from '@wordpress/components';

import { Alert } from '@nachalo/wp-ui-components';

export default function Edit({ attributes, setAttributes }) {
  const { message, type } = attributes;

  return (
    <div>
      <TextControl
        label="Message"
        value={message}
        onChange={(value) => setAttributes({ message: value })}
        __next40pxDefaultSize={true}
        __nextHasNoMarginBottom={true}
      />

      <SelectControl
        label="Alert Type"
        value={type}
        options={[
          { label: 'Info', value: 'info' },
          { label: 'Success', value: 'success' },
          { label: 'Warning', value: 'warning' },
          { label: 'Error', value: 'error' },
        ]}
        onChange={(value) => setAttributes({ type: value })}
        __next40pxDefaultSize={true}
        __nextHasNoMarginBottom={true}
      />

      <Alert
        type={type}
        message={message}
      />
    </div>
  );
}