import React from 'react';
import { useSelector } from 'react-redux';
import Button from '@mui/material/Button';

const SubmitButton = ({ onClick, label }) => {
  const isSubmitting = useSelector((state) => state.auth.isSubmitting);
  return (
    <Button
      onClick={onClick}
      disabled={isSubmitting}
      variant="contained"
      color="primary"
      fullWidth
    >
      {label}
    </Button>
  );
};

export default SubmitButton;
