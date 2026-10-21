import { Alert } from '../../components/alert/alert.js';

export default function save({ attributes }) {
  const { message, type } = attributes;

  return (
    <Alert
      type={type}
      message={message}
    />
  );
}